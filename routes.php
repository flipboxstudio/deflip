<?php

$router->group(['prefix' => '/api'], function ($router) {
    $router->get('/posts', 'Api\PostController@index');
    $router->get('/posts/{id}', 'Api\PostController@show');
});
