<?php

namespace Soyhuce\Docker\Drivers;

use Illuminate\Support\Facades\Http;

class ApiDockerDriver extends DockerDriver
{
    private string $method = 'get';

    protected function getUrl(): string
    {
        return $this->config['url'];
    }

    public function send(string $path, array $params = [], ?array $data = null, array $headers = []): array
    {
        $response = Http
            ::withHeaders(array_merge([
                'Content-type' => 'application/json',
            ], $headers))
                ->{$this->method}(
                $this->prepareUrl($path, $params),
                $data
            )
                ->throw();

        return json_decode($response->body(), true);
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
