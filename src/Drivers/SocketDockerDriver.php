<?php declare(strict_types=1);

namespace Soyhuce\Docker\Drivers;

use Exception;
use Soyhuce\Docker\Model\Response;
use Soyhuce\Docker\Model\StreamResponse;

class SocketDockerDriver extends DockerDriver
{
    /** @var resource */
    private $handle;

    private StreamResponse $response;

    public function prepareRequest(): void
    {
        $this->response = new StreamResponse();
        $this->handle = $this->response->getCurlHandle();
    }

    protected function getUrl(): string
    {
        return 'http:/';
    }

    /**
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $data
     * @param array<string, mixed> $headers
     * @return array<array-key, mixed>
     */
    public function send(string $path, array $params = [], ?array $data = null, array $headers = []): array
    {
        curl_setopt($this->handle, CURLOPT_URL, $this->prepareUrl($path, $params));
        curl_setopt($this->handle, CURLOPT_HEADER, true);
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->handle, CURLOPT_UNIX_SOCKET_PATH, $this->config['unix_socket']);
        curl_setopt($this->handle, CURLOPT_WRITEFUNCTION, function ($ch, $str): int {
            $this->response->writeData($str);

            return mb_strlen($str);
        });

        if ($data !== null) {
            curl_setopt($this->handle, CURLOPT_POSTFIELDS, json_encode($data, JSON_THROW_ON_ERROR));
        }

        $headers = ['Content-type: application/json', ...$headers];

        curl_setopt($this->handle, CURLOPT_HTTPHEADER, $headers);

        $this->response->waitForHeader();

        return $this->prepareResponse();
    }

    public function asGet(): self
    {
        $this->prepareRequest();

        curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, 'GET');

        return $this;
    }

    public function asPost(): self
    {
        $this->prepareRequest();

        curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, 'POST');

        return $this;
    }

    public function asPut(): self
    {
        $this->prepareRequest();

        curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, 'PUT');

        return $this;
    }

    public function asDelete(): self
    {
        $this->prepareRequest();

        curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, 'DELETE');

        return $this;
    }

    /**
     * @return array<array-key, mixed>
     */
    protected function prepareResponse(): array
    {
        if ($this->response->getStatus() === 0) {
            throw new Exception('Docker is not running');
        }

        $response = new Response($this->response);
        $data = $response->getData();

        if ((int) mb_substr((string) $this->response->getStatus(), 0, 1) !== 2) {
            $message = data_get($data, 'message', 'Internal Server Error');

            throw new Exception($message, $this->response->getStatus());
        }

        return $data;
    }
}
