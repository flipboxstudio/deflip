<?php

namespace App\Services\Api\Drivers;

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

    public function post(string $path, array $data = []): ZttpResponse
    {
        return Zttp::asJson()->post($this->url($path), $data);
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

    abstract public function baseUrl(): string;

    abstract public function basePath(): string;
}
