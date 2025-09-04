<?php

namespace Superconductor\Transports\StreamableHttp\Actions\Clients;

use RuntimeException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Superconductor\Rpc\DTO\Messages\RpcMessage;

class CallMessagesEndpointAction
{
    protected GuzzleClient $client;

    private ?Response $response = null;

    private ?string $lastId = null;
    /**
     * Returns a streamable response, a 202 Accepted or a 400 Bad Request if there's an open stream, or a JSON-RPC 2 response from the given URL.
     * @param string $url
     * @param array $body
     * @param array $headers
     * @return bool|static|array
     * @throws GuzzleException
     * @throws RuntimeException
     */
    public function handle(string $url, array $body, array $headers = [
        'Accept' => 'application/json, text/event-stream',
        'Cache-Control' => 'no-cache',
    ]): bool|static|array
    {
        $this->client = new GuzzleClient([
            'headers' => $headers,
        ]);
        $this->connect($url, $body);

        if ($this->response->getStatusCode() === 202) {
            return true;
        }

        // a 200 could either be a stream or a JSON-RPC 2 response
        // return this if a stream, or otherwise return the response object
        if ($this->response->getStatusCode() === 200) {
            $contentType = $this->response->getHeaderLine('Content-Type');
            if (str_contains($contentType, 'text/event-stream')) {
                return $this;
            } elseif (str_contains($contentType, 'application/json')) {
                return [
                    'result' => json_decode((string) $this->response->getBody(), true),
                    'headers' => $this->response->getHeaders(),
                ];
            } else {
                throw new RuntimeException("Unexpected Content-Type: " . $contentType);
            }
        }

        if ($this->response->getStatusCode() === 400) {
            return false;
        }

        throw new RuntimeException("Unexpected response status: " . $this->response->getStatusCode());
    }

    /**
     * Connect to server.
     *
     * @param string $url
     * @throws RuntimeException
     * @throws GuzzleException
     */
    private function connect(string $url, array $body): void
    {
        $headers = [];
        if ($this->lastId) {
            $headers['Last-Event-ID'] = $this->lastId;
        }

        $this->response = $this->client->request('POST', $url, [
            //'stream' => true,
            'json' => $body,
            'headers' => $headers,
        ]);

        if ($this->response->getStatusCode() == 204) {
            throw new RuntimeException('Server forbid connection retry by responding 204 status code.');
        }
        elseif ($this->response->getStatusCode() == 400)
        {
            throw new RuntimeException('Bad Request - The server could not understand the request due to invalid syntax.');
        }
    }

    public function readEvents(): ?array
    {
        $results = null;

        return $results;
    }
}
