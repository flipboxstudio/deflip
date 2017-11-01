<?php

namespace Sys;

use Exception;
use Zend\Diactoros\Response;
use League\Route\RouteCollection;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response\SapiEmitter;

abstract class HttpKernel
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function run()
    {
        $this->app->bootIfNotBooted();

        return $this->app->get(SapiEmitter::class)->emit(
            $this->app->get(RouteCollection::class)->dispatch(
                $this->app->get(ServerRequest::class),
                $this->app->get(Response::class)
            )
        );
    }
}