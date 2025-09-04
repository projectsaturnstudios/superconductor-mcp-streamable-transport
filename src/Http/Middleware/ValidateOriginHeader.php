<?php

namespace Superconductor\Transports\StreamableHttp\Http\Middleware;

use Illuminate\Http\Request;

class ValidateOriginHeader
{
    public function handle(Request $request, callable $next)
    {
        // @todo - Implement origin header validation logic.

        return $next($request);
    }
}
