<?php

namespace Superconductor\Transports\StreamableHttp\DTO\Servers;

use Spatie\LaravelData\Data;

class StreamableServerConfig extends Data
{

    public function __construct(
        public readonly string $url,
        public readonly array $headers = [],

    ) {

    }

    public function url(): string
    {
        return $this->url;
    }

    public function headers(): array
    {
        return $this->headers;
    }

}
