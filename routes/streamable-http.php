<?php

use Illuminate\Support\Facades\Route;

Route::prefix('mcp')->group(function() {
    if(streamable_sse_enabled())
        Route::get('/', \Superconductor\StreamableHttp\Http\Controllers\StreamableHttpEndpointsController::class.'@stream')
            ->middleware([
                ...config('mcp.endpoints.middleware.starting', []),
            ]);
logger()->log('info', "Incoming request in routes", request()->all());
    Route::post('/', \Superconductor\StreamableHttp\Http\Controllers\StreamableHttpEndpointsController::class.'@message')
        ->middleware([
            ...config('mcp.endpoints.middleware.starting', []),
            ...[
                \Superconductor\Http\Middleware\CreateOrLoadMCPSession::class,
            ],
            ...config('mcp.endpoints.middleware.message', []),
        ]);
});
