<?php

namespace Superconductor\Transports\StreamableHttp\Drivers;

use Illuminate\Contracts\Container\Container;
use Superconductor\Transports\StreamableHttp\DTO\Servers\StreamableServerConfig;
use Superconductor\Transports\StreamableHttp\StreamableCommunicator;


class NativeStreamableDriver
{
    public function __construct(
        protected Container $app
    ) {}

    public function client(StreamableServerConfig $server): StreamableCommunicator
    {
        return new StreamableCommunicator($server);
    }
}
