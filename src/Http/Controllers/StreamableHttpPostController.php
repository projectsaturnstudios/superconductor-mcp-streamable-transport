<?php

namespace Superconductor\Transports\StreamableHttp\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Superconductor\Mcp\Servers\MCPServer;
use Superconductor\Mcp\Servers\ServerRoute;
use Superconductor\Mcp\Support\Facades\MCPServers;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcNotification;
use Superconductor\Rpc\DTO\Messages\Incoming\RpcRequest;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcError;
use Superconductor\Rpc\DTO\Messages\Outgoing\RpcResult;
use Superconductor\Rpc\DTO\Messages\RpcMessage;
use Superconductor\Rpc\Enums\RPCErrorCode;
use Superconductor\Rpc\Support\Facades\RPC;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StreamableHttpPostController
{
    public function post(Request $request): Response|StreamedResponse|JsonResponse
    {
        $has_active_session = !empty($request->header('Mcp-Session-Id'));
        $session_id = $has_active_session ? $request->header('Mcp-Session-Id') : new_uuid4();
        $server_to_use = $request->get('using') ?? 'default';
        $streaming_enabled = config('superconductor.transports.streamable.enable_sse', false);
        if($streaming_enabled) $this->stream($request->all(), $session_id, $has_active_session, $server_to_use);

        return $this->json($request->all(), $session_id, $server_to_use);
    }

    private function accept(): Response
    {
        return response(null, 202);
    }

    private function stream(array $request, string $session_id, bool $has_active_session, string $server_to_use): StreamedResponse
    {

    }

    private function json(array $request, string $session_id, string $server_to_use): JsonResponse|Response
    {
        $servers = MCPServers::getServers();
        if(!isset($servers[$server_to_use]))
        {
            $error = new RpcError(0, RPCErrorCode::SERVER_ERROR, "MCP Server '{$server_to_use}' not found.");
            return response()->json($error->toJsonRpc(), 500, ['Mcp-Session-Id' => $session_id]);
        }

        /** @var ServerRoute $server_route */
        $server_route = $servers[$server_to_use];
        /** @var MCPServer $mcp_server */
        $mcp_server = new $server_route->class_name();

        logger()->log('info', 'StreamableHttpPostController - Message Received', [
            'message' => $request,
        ]);

        $message = RpcMessage::fromJsonRpc($request);
        if($message instanceof RpcRequest)
        {

            $message = $message->additional([
                'server' => &$mcp_server,
            ]);
            /** @var RpcResult|RpcError $result */
            $result = RPC::call($message);
            if($result instanceof RpcResult)
            {
                return response()->json($result->toJsonRpc(), 200, ['Mcp-Session-Id' => $session_id]);
            }
            else
            {
                return $this->accept();
            }
        }
        elseif($message instanceof RpcNotification)
        {
            $message = $message->additional([
                'server' => &$mcp_server
            ]);

            RPC::notify($message);

            return $this->accept();

        }
        elseif($message instanceof RpcError)
        {
            $error = new RpcError(0, RPCErrorCode::PARSE_ERROR, "Invalid RpcError message received.");
            return response()->json($error->toJsonRpc(), 500, ['Mcp-Session-Id' => $session_id]);
        }
        elseif($message instanceof RpcResult)
        {
            $error = new RpcError(0, RPCErrorCode::PARSE_ERROR, "Invalid JSONRPC message received.");
            return response()->json($error->toJsonRpc(), 500, ['Mcp-Session-Id' => $session_id]);
        }
        else
        {
            $error = new RpcError(0, RPCErrorCode::PARSE_ERROR, "Invalid JSONRPC message received.");
            return response()->json($error->toJsonRpc(), 500, ['Mcp-Session-Id' => $session_id]);
        }
    }
}
