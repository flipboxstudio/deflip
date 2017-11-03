<?php

namespace Sys\Api\Drivers;

use Zttp\Zttp;
use Zttp\ZttpResponse;

abstract class Driver
{
    public function all(array $query = []): ZttpResponse
    {
        return $this->sendGet('/', $query);
    }

    public function create(array $data, array $query = []): ZttpResponse
    {
        return $this->sendPost('/', $data, $query);
    }

    public function read($id, array $query = [])
    {
        return $this->sendGet($id, $query);
    }

    public function update($id, array $data, array $query = [])
    {
        return $this->sendPost($id, $data, $query);
    }

    public function delete($id, array $data, array $query = [])
    {
        return $this->sendDelete($id, $data, $query);
    }

    public function sendGet(string $path, array $query = []): ZttpResponse
    {
        return Zttp::asJson()->get($this->url($path, $query));
    }

    public function sendPost(string $path, array $data = [], array $query = []): ZttpResponse
    {
        return Zttp::asJson()->post(
            $this->url($path, $query),
            $data
        );
    }

    public function sendPut(string $path, array $data = [], array $query = []): ZttpResponse
    {
        return Zttp::asJson()->put(
            $this->url($path, $query),
            $data
        );
    }

    public function sendPatch(string $path, array $data = [], array $query = []): ZttpResponse
    {
        return Zttp::asJson()->patch(
            $this->url($path, $query),
            $data
        );
    }

    public function sendDelete(string $path, array $data = [], array $query = []): ZttpResponse
    {
        return Zttp::asJson()->delete(
            $this->url($path, $query),
            $data
        );
    }

    public function sendOptions(string $path, array $query = []): ZttpResponse
    {
        return Zttp::asJson()->options($this->url($path, $query));
    }

    public function upload(string $path, array $data = []): ZttpResponse
    {
        return Zttp::asMultipart()->post($this->url($path), $data);
    }

    protected function url(string $path, array $query = []): string
    {
        $url = preg_replace('/([^:])(\/{2,})/', '$1/', implode('/', [
            $this->baseUrl(),
            $this->basePath(),
            $path,
        ]));

        if (empty($query)) {
            return $url;
        }

        return implode('?', [
            $url,
            http_build_query($query),
        ]);
    }

    abstract public function baseUrl(): string;

    abstract public function basePath(): string;
}
