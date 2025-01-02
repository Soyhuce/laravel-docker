<?php declare(strict_types=1);

namespace Soyhuce\Docker\Drivers;

use Illuminate\Support\Facades\Http;

class ApiDockerDriver extends DockerDriver
{
    private string $method = 'get';

    protected function getUrl(): string
    {
        return $this->config['url'];
    }

    /**
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $data
     * @param array<string, mixed> $headers
     * @return array<array-key, mixed>
     */
    public function send(string $path, array $params = [], ?array $data = null, array $headers = []): array
    {
        $response = Http::withHeaders(['Content-type' => 'application/json', ...$headers])
            ->{$this->method}(
                $this->prepareUrl($path, $params),
                $data
            )
            ->throw();

        return json_decode((string) $response->body(), true, 512, JSON_THROW_ON_ERROR);
    }

    public function asGet(): self
    {
        $this->method = 'get';

        return $this;
    }

    public function asPost(): self
    {
        $this->method = 'post';

        return $this;
    }

    public function asPut(): self
    {
        $this->method = 'put';

        return $this;
    }

    public function asDelete(): self
    {
        $this->method = 'delete';

        return $this;
    }
}
