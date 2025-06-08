<?php

namespace Superconductor\StreamableHttp\Providers;

use Illuminate\Support\ServiceProvider;

class SuperconductorStreamableHttpServiceProvider extends ServiceProvider
{
    protected array $config = [
        'mcp.protocols.streamable-http' => __DIR__ .'/../../config/mcp/protocols/streamable-http.php',
    ];

    public function register(): void
    {
        $this->registerConfigs();
    }

    public function boot(): void
    {
        $this->publishConfigs();
        $this->loadRoutesFrom(__DIR__.'/../../routes/streamable-http.php');
    }

    protected function publishConfigs() : void
    {
        $this->publishes($this->config, 'mcp');
    }

    protected function registerConfigs() : void
    {
        foreach ($this->config as $key => $path) {
            $this->mergeConfigFrom($path, $key);
        }
    }
}
