<?php

if(!function_exists('streamable_sse_enabled'))
{
    /** @return bool */
    function streamable_sse_enabled(): bool
    {
        return config('mcp.protocols.streamable-http.streamable_sse_enabled', false);
    }
}
