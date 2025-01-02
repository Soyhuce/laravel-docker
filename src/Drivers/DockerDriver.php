<?php declare(strict_types=1);

namespace Soyhuce\Docker\Drivers;

abstract class DockerDriver
{
    /**
     * @param array<string, mixed> $config
     */
    public function __construct(
        protected array $config = [],
    ) {
    }

    /**
     * @param array<string, mixed> $params
     */
    public function prepareUrl(string $path, array $params = []): string
    {
        $url = $this->getUrl() . '/' . config('docker.version') . $path;
        $query = http_build_query($params);

        return mb_strlen($query) ? "{$url}?{$query}" : $url;
    }

    /**
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $data
     * @param array<string, mixed> $headers
     * @return array<array-key, mixed>
     */
    abstract public function send(string $path, array $params = [], ?array $data = null, array $headers = []): array;

    abstract public function asGet(): self;

    abstract public function asPost(): self;

    abstract public function asPut(): self;

    abstract public function asDelete(): self;

    abstract protected function getUrl(): string;
}
