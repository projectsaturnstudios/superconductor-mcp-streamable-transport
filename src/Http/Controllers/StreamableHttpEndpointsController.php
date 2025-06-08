<?php

namespace Superconductor\StreamableHttp\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Superconductor\Contracts\Controllers\MCPEndpointsControllerContract;

class StreamableHttpEndpointsController implements MCPEndpointsControllerContract
{
    public function message(): Response
    {
        // Differing from SSE, Sessions are created or loaded here
        return response()->noContent(202);
    }

    public function stream(): StreamedResponse|JsonResponse
    {
        // Sessions are only loaded here
        // Sessions are only created, if the server doesn't use auth,
        // otherwise they are loaded from the request as the user
    }
}
