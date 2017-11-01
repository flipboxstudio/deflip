<?php

namespace App\Http\Controllers\Api;

use App\Services\Api\Api;
use App\Http\Controllers\Controller;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class PostController extends Controller
{
    protected $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, array $params = []): ResponseInterface
    {
        $response->getBody()->write(
            $this->api->post->all()->body()
        );

        return $response;
    }

    public function show(ServerRequestInterface $request, ResponseInterface $response, array $params = []): ResponseInterface
    {
        $response->getBody()->write(
            $this->api->post->get($params['id'])->body()
        );

        return $response;
    }
}
