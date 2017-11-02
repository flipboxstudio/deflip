<?php

namespace Sys;

use League\Container\Container;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Http\Exception\NotFoundException;

class RouteStrategy extends ApplicationStrategy
{
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function getNotFoundDecorator(NotFoundException $exception)
    {
        $path = $this->container->get('Psr\Http\Message\ServerRequestInterface')->getUri()->getPath();
        $path = ('/' === $path) ? 'index' : $path;
        $templates = $this->container->get('League\Plates\Engine');

        if ($templates->exists($path)) {
            return function ($request, $response) use ($path, $templates) {
                $response->getBody()->write(
                    $templates->render($path)
                );

                return $response;
            };
        }

        if ($templates->exists('404')) {
            return function ($request, $response) use ($templates) {
                $response->getBody()->write(
                    $templates->render('404')
                );

                return $response;
            };
        }

        throw $exception;
    }
}
