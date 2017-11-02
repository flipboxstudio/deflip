<?php

namespace Sys;

class HttpKernel
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function run()
    {
        $this->app->bootIfNotBooted();

        return $this->app->get('Zend\Diactoros\Response\EmitterInterface')->emit(
            $this->app->get('League\Route\RouteCollection')->dispatch(
                $this->app->get('Psr\Http\Message\ServerRequestInterface'),
                $this->app->get('Psr\Http\Message\ResponseInterface')
            )
        );
    }
}
