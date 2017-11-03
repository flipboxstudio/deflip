<?php

use League\Route\RouteGroup;
use League\Route\Strategy\JsonStrategy;

$route->group('/api', function (RouteGroup $route) {
    $route->get('/posts', 'Controllers\Api\PostController::index');
    $route->get('/posts/{id}', 'Controllers\Api\PostController::show');
})->setStrategy(new JsonStrategy());
