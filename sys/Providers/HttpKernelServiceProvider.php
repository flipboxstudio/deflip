<?php

namespace Sys\Providers;

use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

class HttpKernelServiceProvider extends ServiceProvider
{
    protected $provides = [
        ServerRequestInterface::class,
        ServerRequest::class,
        Response::class,
        ResponseInterface::class,
        SapiEmitter::class,
    ];

    public function register()
    {
        $this->registerResponse();

        $this->registerRequest();

        $this->registerEventEmitter();
    }

    protected function registerRequest()
    {
        $this->container->share(ServerRequest::class, function () {
            return ServerRequestFactory::fromGlobals(
                $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
            );
        });

        $this->container->add(ServerRequestInterface::class, ServerRequest::class);
        $this->container->add('request', ServerRequest::class);
    }

    protected function registerResponse()
    {
        $this->container->share(Response::class);
        $this->container->add(ResponseInterface::class, Response::class);
        $this->container->add('response', Response::class);
    }

    protected function registerEventEmitter()
    {
        $this->container->share(SapiEmitter::class);
    }
}
