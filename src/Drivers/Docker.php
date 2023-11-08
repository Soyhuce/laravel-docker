<?php

namespace Soyhuce\Docker\Drivers;

use InvalidArgumentException;

class Docker
{
    /** @var array<mixed> */
    private array $drivers = [];

    public function driver(?string $name = null): DockerDriver
    {
        $name ??= $this->getDefaultDriver();

        return $this->drivers[$name] = $this->get($name);
    }

    protected function getDefaultDriver(): string
    {
        return config('docker.driver');
    }

    protected function get(string $name): DockerDriver
    {
        return $this->drivers[$name] ?? $this->resolve($name);
    }

    protected function resolve(string $name): DockerDriver
    {
        $config = $this->getConfig($name);

        $driverMethod = 'create' . ucfirst($name) . 'Driver';

        if (method_exists($this, $driverMethod)) {
            return $this->{$driverMethod}($config);
        }

        throw new InvalidArgumentException("Driver [{$name}] is not supported.");
    }

    protected function getConfig(string $name): ?array
    {
        return config("docker.drivers.{$name}");
    }

    protected function createSocketDriver(array $config): DockerDriver
    {
        return new SocketDockerDriver($config);
    }

    protected function createApiDriver(array $config): DockerDriver
    {
        return new ApiDockerDriver($config);
    }
}
