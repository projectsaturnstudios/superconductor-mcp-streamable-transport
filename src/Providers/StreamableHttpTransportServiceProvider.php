<?php

namespace Superconductor\Transports\StreamableHttp\Providers;


use ProjectSaturnStudios\LaravelDesignPatterns\Providers\BaseServiceProvider;
use Superconductor\Transports\StreamableHttp\Managers\StreamablePeerManager;

class StreamableHttpTransportServiceProvider extends BaseServiceProvider
{
    protected array $config = [
        'superconductor.transports.streamable-http' => __DIR__ . '/../../config/streamable.php',
    ];
    protected array $publishable_config = [
        ['key' => 'superconductor.transports.streamable-http', 'file_path' => 'streamable-http.php', 'groups' => ['superconductor', 'superconductor.transports', 'superconductor.transports.streamable']],
    ];
    protected array $commands = [];
    protected array $bootables = [
        StreamablePeerManager::class
    ];
    protected array $routes = [
        __DIR__ . '/../../routes/web.php'
    ];

}
