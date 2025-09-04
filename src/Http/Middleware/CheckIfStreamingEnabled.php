<?php

namespace Superconductor\Transports\StreamableHttp\Http\Middleware;

use Illuminate\Http\Request;

class CheckIfStreamingEnabled
{
    public function handle(Request $request, callable $next)
    {
        // @todo - Implement verification that the request is bound to localhost if env is local if enabled.
        $streaming_enabled = config('superconductor.transports.streamable.enable_sse', false);
        if(!$streaming_enabled) return response(null, 404);

        return $next($request);
    }

}
