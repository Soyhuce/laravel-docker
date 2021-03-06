<?php

namespace Soyhuce\Docker\Model;

class Response
{
    protected StreamResponse $response;

    private array $header;

    private string $body;

    public function __construct(StreamResponse $response)
    {
        $this->response = $response;

        $this->parseHeaders();
    }

    public static function make(StreamResponse $streamResponse): self
    {
        return new self($streamResponse);
    }

    private function parseHeaders(): void
    {
        $headerLines = explode("\r\n", $this->response->readData($this->response->getHeaderSize()));
        $headerLines = array_slice($headerLines, 1, count($headerLines) - 3);
        $this->header = [];
        foreach ($headerLines as $headerLine) {
            preg_match('#(.*?): (.*)#', $headerLine, $matches);
            $this->header[mb_strtolower($matches[1])] = $matches[2];
        }
    }

    /**
     * @return array<mixed>
     */
    public function getData()
    {
        return json_decode($this->getBody(), true) ?? [];
    }

    public function getBody(): string
    {
        if (!isset($this->body)) {
            $this->response->waitForBody();
            $this->body = $this->response->readData();
        }

        return $this->body;
    }

    public function getResponse(): StreamResponse
    {
        return $this->response;
    }

    /**
     * @return mixed
     */
    public function getHeader(?string $key = null)
    {
        return $key ? $this->header[$key] : $this->header;
    }
}
