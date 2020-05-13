<?php

namespace Soyhuce\Docker;

use Soyhuce\Docker\Model\Response;
use Soyhuce\Docker\Model\StreamResponse;

class DockerClient
{
    private string $version;

    private ?string $scheme;

    public function __construct(string $version, ?string $scheme = 'http')
    {
        $this->version = $version;
        $this->scheme = $scheme;
    }

    private function getUrl(string $path, array $params = []): string
    {
        if ($path[0] !== '/') {
            throw new \Exception("${path} must begin with /");
        }

        $version = $this->version ? '/' . $this->version : '';
        $url = "{$this->scheme}:${version}${path}";
        $query = http_build_query($params);

        return mb_strlen($query) ? "${url}?${query}" : $url;
    }

    private function makeRequest(string $url, ?callable $callback = null, ?array $data = null, array $headers = []): array
    {
        $response = new StreamResponse();

        /** @var resource $ch */
        $ch = $response->getCurlHandle();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_UNIX_SOCKET_PATH, config('docker.unix_socket'));
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, static function ($ch, $str) use ($response): int {
            $response->writeData($str);

            return mb_strlen($str);
        });

        if (is_callable($callback)) {
            call_user_func($callback, $ch);
        }

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $headers = array_merge([
            'Content-type: application/json',
            'Expect:',
        ], $headers);

        foreach ($headers as $numKey => $header) {
            $key = mb_strtolower(mb_substr($header, 0, mb_strpos($header, ':')));
            $headers[$key] = $header;
            unset($headers[$numKey]);
        }

        $headers = array_values($headers);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response->waitForHeader();

        return Response::make($response)->getData();
    }

    public function get(string $path, array $params = [], ?array $data = null, array $headers = []): array
    {
        $url = $this->getUrl($path, $params);

        return $this->makeRequest($url, null, $data, $headers);
    }

    public function delete(string $path, array $params = [], ?array $data = null, array $headers = []): array
    {
        $url = $this->getUrl($path, $params);
        $callback = static function ($ch) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        };

        return $this->makeRequest($url, $callback, $data, $headers);
    }

    public function post(string $path, array $params = [], array $data = [], array $headers = []): array
    {
        $url = $this->getUrl($path, $params);
        $callback = static function ($ch) {
            curl_setopt($ch, CURLOPT_POST, 1);
        };

        return $this->makeRequest($url, $callback, $data, $headers);
    }

    public function put(string $path, array $params = [], array $data = [], array $headers = []): array
    {
        $url = $this->getUrl($path, $params);
        $callback = static function ($ch) {
            curl_setopt($ch, CURLOPT_PUT, 1);
        };

        return $this->makeRequest($url, $callback, $data, $headers);
    }
}
