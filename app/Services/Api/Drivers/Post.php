<?php

namespace App\Services\Api\Drivers;

class Post extends JsonPlaceholder
{
    public function basePath(): string
    {
        return '/posts';
    }
}
