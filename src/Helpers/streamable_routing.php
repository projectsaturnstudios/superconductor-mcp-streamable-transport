<?php

if(!function_exists('streamable_messages_uri'))
{
    /**
     * The Messages endpoint URI.
     *
     * @return string
     */
    function streamable_messages_uri(): string
    {
        return config('superconductor.transports.streamable-http.endpoints.messages.uri', '');
    }
}

if(!function_exists('streamable_messages_middleware'))
{
    /**
     * The Messages endpoint middleware.
     *
     * @return array
     */
    function streamable_messages_middleware(): array
    {
        return config('superconductor.transports.streamable-http.endpoints.messages.middleware', []);
    }
}



if(!function_exists('streamable_stream_uri'))
{
    /**
     * The Stream endpoint URI.
     *
     * @return string
     */
    function streamable_stream_uri(): string
    {
        return config('superconductor.transports.streamable-http.endpoints.stream.uri', '');
    }
}

if(!function_exists('streamable_stream_middleware'))
{
    /**
     * The Stream endpoint middleware.
     *
     * @return array
     */
    function streamable_stream_middleware(): array
    {
        return config('superconductor.transports.streamable-http.endpoints.stream.middleware', []);
    }
}
