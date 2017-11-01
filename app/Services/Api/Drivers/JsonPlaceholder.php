<?php

namespace App\Services\Api\Drivers;

abstract class JsonPlaceholder extends Driver
{
    public function baseUrl(): string
    {
        return 'https://jsonplaceholder.typicode.com/';
    }

    public function basePath(): string
    {
        return '';
    }
}
