<?php

namespace Soyhuce\Docker\Drivers;

abstract class DockerDriver
{
    /** @var array<mixed> */
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function prepareUrl(string $path, array $params = []): string
    {
        $url = $this->getUrl() . '/' . config('docker.version') . $path;
        $query = http_build_query($params);

        return mb_strlen($query) ? "{$url}?{$query}" : $url;
    }

    abstract public function send(string $path, array $params = [], ?array $data = null, array $headers = []): array;

    abstract public function asGet(): self;

    abstract public function asPost(): self;

    abstract public function asPut(): self;

    abstract public function asDelete(): self;

    abstract protected function getUrl(): string;
}
