<?php

use League\Route\RouteGroup;
use League\Route\Strategy\JsonStrategy;

$route->group('/api', function (RouteGroup $route) {
    $route->get('/posts', 'App\Http\Controllers\Api\PostController::index');
    $route->get('/posts/{id}', 'App\Http\Controllers\Api\PostController::show');
})->setStrategy(new JsonStrategy());
