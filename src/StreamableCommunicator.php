<?php

namespace Superconductor\Transports\StreamableHttp;

use Superconductor\Rpc\DTO\Messages\RpcMessage;
use Superconductor\Transports\StreamableHttp\DTO\Servers\StreamableServerConfig;
use Superconductor\Transports\StreamableHttp\Actions\Clients\CallMessagesEndpointAction;

class StreamableCommunicator
{
    protected mixed $process = null;
    public function __construct(
        protected StreamableServerConfig $server
    ) {}

    public function send(RpcMessage $message, array $headers = []): array|bool
    {
        return (new CallMessagesEndpointAction)->handle(
            $this->server->url, $message->toJsonRpc(), array_merge($this->server->headers(), $headers)
        );
    }

}
