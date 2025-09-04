<?php

namespace Superconductor\Transports\StreamableHttp\Support\Facades;

use Illuminate\Support\Facades\Facade;

class StreamableHttp extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'streamable-http';
    }
}
