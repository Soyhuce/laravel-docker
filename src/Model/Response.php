<?php declare(strict_types=1);

namespace Soyhuce\Docker\Model;

use function array_slice;
use function count;

class Response
{
    /** @var array<string, mixed> */
    private array $header;

    private string $body;

    public function __construct(
        protected StreamResponse $response,
    ) {
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
            if (preg_match('#(.*?): (.*)#', $headerLine, $matches) !== false) {
                $this->header[mb_strtolower($matches[1])] = $matches[2];
            }
        }
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getData(): array
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
