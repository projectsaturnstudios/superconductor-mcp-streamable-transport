<?php

use Illuminate\Support\Facades\Route;

Route::post(
    streamable_messages_uri(),
    \Superconductor\Transports\StreamableHttp\Http\Controllers\StreamableHttpPostController::class.'@post'
)->middleware(streamable_messages_middleware());

Route::get(
    streamable_stream_uri(),
    \Superconductor\Transports\StreamableHttp\Http\Controllers\StreamableHttpStreamController::class.'@get'
)->middleware(streamable_stream_middleware());
