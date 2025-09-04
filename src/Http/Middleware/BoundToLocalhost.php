<?php

namespace Superconductor\Transports\StreamableHttp\Http\Middleware;

use Illuminate\Http\Request;

class BoundToLocalhost
{
    public function handle(Request $request, callable $next)
    {
        // @todo - Implement verification that the request is bound to localhost if env is local if enabled.

        return $next($request);
    }
}
