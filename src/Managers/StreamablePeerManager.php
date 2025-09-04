<?php

namespace Superconductor\Transports\StreamableHttp\Managers;

use Illuminate\Support\Manager;
use Illuminate\Container\Attributes\Singleton;
use Superconductor\Transports\StreamableHttp\Drivers\NativeStreamableDriver;

#[Singleton]
class StreamablePeerManager extends Manager
{
    public function createNativeDriver(): NativeStreamableDriver
    {
        return new NativeStreamableDriver($this->container);
    }

    public function getDefaultDriver(): string
    {
        return 'native';
    }

    public static function boot(): void
    {
        app()->singleton('streamable-http', function ($app) {
            $results = new static($app);

            // @todo - extension addon integration from other packages

            return $results;
        });

    }
}
