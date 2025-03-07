<?php declare(strict_types=1);

namespace Soyhuce\Docker;

use Exception;
use Soyhuce\Docker\Model\Response;
use Soyhuce\Docker\Model\StreamResponse;
use function is_callable;

class DockerClient
{
    public function __construct(
        private string $version,
        private ?string $scheme = 'http',
    ) {
    }

    /**
     * @param array<string, mixed> $params
     */
    private function getUrl(string $path, array $params = []): string
    {
        if ($path[0] !== '/') {
            throw new Exception("{$path} must begin with /");
        }

        $version = $this->version ? '/' . $this->version : '';
        $url = "{$this->scheme}:{$version}{$path}";
        $query = http_build_query($params);

        return mb_strlen($query) ? "{$url}?{$query}" : $url;
    }

    /**
     * @param array<string, mixed>|null $data
     * @param array<string, mixed> $headers
     * @return array<array-key, mixed>
     */
    private function makeRequest(string $url, ?callable $callback = null, ?array $data = null, array $headers = []): array
    {
        $response = new StreamResponse();

        /** @var resource $ch */
        $ch = $response->getCurlHandle();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_UNIX_SOCKET_PATH, config('docker.unix_socket'));
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, static function ($ch, $str) use ($response): int {
            $response->writeData($str);

            return mb_strlen($str);
        });

        if (is_callable($callback)) {
            $callback($ch);
        }

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_THROW_ON_ERROR));
        }

        $headers = ['Content-type: application/json', 'Expect:', ...$headers];

        foreach ($headers as $numKey => $header) {
            $key = mb_strtolower(mb_substr((string) $header, 0, mb_strpos((string) $header, ':')));
            $headers[$key] = $header;
            unset($headers[$numKey]);
        }

        $headers = array_values($headers);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response->waitForHeader();

        return Response::make($response)->getData();
    }

    /**
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $data
     * @param array<string, mixed> $headers
     * @return array<array-key, mixed>
     */
    public function get(string $path, array $params = [], ?array $data = null, array $headers = []): array
    {
        $url = $this->getUrl($path, $params);

        return $this->makeRequest($url, null, $data, $headers);
    }

    /**
     * @param array<string, mixed> $params
     * @param array<string, mixed>|null $data
     * @param array<string, mixed> $headers
     * @return array<array-key, mixed>
     */
    public function delete(string $path, array $params = [], ?array $data = null, array $headers = []): array
    {
        $url = $this->getUrl($path, $params);
        $callback = static function ($ch): void {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        };

        return $this->makeRequest($url, $callback, $data, $headers);
    }

    /**
     * @param array<string, mixed> $params
     * @param array<string, mixed> $data
     * @param array<string, mixed> $headers
     * @return array<array-key, mixed>
     */
    public function post(string $path, array $params = [], array $data = [], array $headers = []): array
    {
        $url = $this->getUrl($path, $params);
        $callback = static function ($ch): void {
            curl_setopt($ch, CURLOPT_POST, true);
        };

        return $this->makeRequest($url, $callback, $data, $headers);
    }

    /**
     * @param array<string, mixed> $params
     * @param array<string, mixed> $data
     * @param array<string, mixed> $headers
     * @return array<array-key, mixed>
     */
    public function put(string $path, array $params = [], array $data = [], array $headers = []): array
    {
        $url = $this->getUrl($path, $params);
        $callback = static function ($ch): void {
            curl_setopt($ch, CURLOPT_PUT, true);
        };

        return $this->makeRequest($url, $callback, $data, $headers);
    }
}
