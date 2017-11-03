<?php

namespace Sys\Api\Drivers;

use Zttp\Zttp;
use Zttp\ZttpResponse;

abstract class Driver
{
    public function all(array $query = []): ZttpResponse
    {
        return $this->get('/', $query);
    }

    public function get(string $path, array $query = []): ZttpResponse
    {
        return Zttp::asJson()->get($this->url($path), $query);
    }

    public function post(string $path, array $data = [], array $query = []): ZttpResponse
    {
        return Zttp::asJson()->post(
            $this->buildQueryString(
                $this->url($path),
                $query
            ),
            $data
    );
    }

    public function upload(string $path, array $data = []): ZttpResponse
    {
        return Zttp::asMultipart()->post($this->url($path), $data);
    }

    protected function url(string $path): string
    {
        return preg_replace('/([^:])(\/{2,})/', '$1/', implode('/', [
            $this->baseUrl(),
            $this->basePath(),
            $path,
        ]));
    }

    protected function buildQueryString(string $url, array $query)
    {
        if (empty($query)) {
            return $url;
        }

        return implode('?', [
            $url,
            http_build_query($query)
        ]);
    }

    abstract public function baseUrl(): string;

    abstract public function basePath(): string;
}
