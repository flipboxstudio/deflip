<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
}

$app = new Sys\Application(realpath(__DIR__.'/../'));

$app->router->group([
    'namespace' => 'Controllers',
], function ($router) {
    require __DIR__.'/../routes.php';
});

$app->run();
