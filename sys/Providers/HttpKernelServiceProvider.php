<?php

namespace Sys\Providers;

use Zend\Diactoros\ServerRequestFactory;

class HttpKernelServiceProvider extends ServiceProvider
{
    protected $provides = [
        'Psr\Http\Message\ServerRequestInterface',
        'Psr\Http\Message\ResponseInterface',
        'Zend\Diactoros\Response\EmitterInterface',
    ];

    public function register()
    {
        $this->registerResponse();

        $this->registerRequest();

        $this->registerEventEmitter();
    }

    protected function registerRequest()
    {
        $this->container->share('Psr\Http\Message\ServerRequestInterface', function () {
            return ServerRequestFactory::fromGlobals(
                $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
            );
        });
    }

    protected function registerResponse()
    {
        $this->container->share('Psr\Http\Message\ResponseInterface', 'Zend\Diactoros\Response');
    }

    protected function registerEventEmitter()
    {
        $this->container->share('Zend\Diactoros\Response\EmitterInterface', 'Zend\Diactoros\Response\SapiEmitter');
    }
}
