<?php

namespace Soyhuce\Docker;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Soyhuce\Docker\Drivers\Docker;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../assets/config.php' => config_path('docker.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/../assets/config.php',
            'docker'
        );
    }

    public function register(): void
    {
        $this->app->singleton(Docker::class);
    }
}
