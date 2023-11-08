<?php

namespace Soyhuce\Docker\Model;

class StreamResponse
{
    /** @var resource */
    private $curlMultiHandle;

    /** @var resource */
    private $curlHandle;

    private string $data = '';

    public function __construct()
    {
        $this->curlMultiHandle = curl_multi_init();
        $this->curlHandle = curl_init();

        curl_multi_add_handle($this->curlMultiHandle, $this->curlHandle);
    }

    public function __destruct()
    {
        curl_multi_remove_handle($this->curlMultiHandle, $this->curlHandle);
        curl_multi_close($this->curlMultiHandle);
    }

    /**
     * @return false|resource
     */
    public function getCurlHandle()
    {
        return $this->curlHandle;
    }

    public function readData(int $length = 0): string
    {
        $data = $this->data;
        if ($length) {
            $data = mb_substr($data, 0, $length);
            $this->data = mb_substr($this->data, $length);
        } else {
            $this->data = '';
        }

        return $data;
    }

    public function writeData(string $data): void
    {
        $this->data .= $data;
    }

    public function waitForHeader(): void
    {
        $active = null;
        do {
            curl_multi_exec($this->curlMultiHandle, $active);
        } while ($active && $this->getHeaderSize() === 0);
    }

    public function waitForBody(): void
    {
        $active = null;
        do {
            curl_multi_exec($this->curlMultiHandle, $active);
        } while ($active);
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return curl_getinfo($this->curlHandle, CURLINFO_HTTP_CODE);
    }

    /**
     * @return mixed
     */
    public function getHeaderSize()
    {
        return curl_getinfo($this->curlHandle, CURLINFO_HEADER_SIZE);
    }
}
