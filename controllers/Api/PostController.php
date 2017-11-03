<?php

namespace Controllers\Api;

use Sys\Api\Api;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PostController
{
    protected $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write(
            $this->api->post->all()->body()
        );

        return $response;
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response, int $id): ResponseInterface
    {
        $response->getBody()->write(
            $this->api->post->read($id)->body()
        );

        return $response;
    }
}
