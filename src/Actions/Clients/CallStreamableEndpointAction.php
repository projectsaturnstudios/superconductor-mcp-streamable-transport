<?php

namespace Superconductor\Transports\StreamableHttp\Actions\Clients;

use RuntimeException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

class CallStreamableEndpointAction
{
    protected GuzzleClient $client;

    private ?Response $response = null;

    private ?string $lastId = null;

    /**
     * Returns a streamable response or a 400 Bad Request from the given URL.
     * @param string $url
     * @param array $headers
     * @return static
     * @throws GuzzleException
     * @throws RuntimeException
     */
    public function handle(string $url, array $headers = [
        'Accept' => 'text/event-stream',
        'Cache-Control' => 'no-cache',
    ]): static
    {
        $this->client = new GuzzleClient([
            'headers' => [
                'Accept' => 'text/event-stream',
                'Cache-Control' => 'no-cache',
            ],
        ]);
        $this->connect($url);

        return $this;
    }

    /**
     * Connect to server.
     *
     * @param string $url
     * @throws RuntimeException
     * @throws GuzzleException
     */
    private function connect(string $url): void
    {
        $headers = [];
        if ($this->lastId) {
            $headers['Last-Event-ID'] = $this->lastId;
        }

        $this->response = $this->client->request('GET', $url, [
            'stream' => true,
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
