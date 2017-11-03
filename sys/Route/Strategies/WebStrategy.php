<?php

namespace Sys\Route\Strategies;

use League\Plates\Engine;
use Psr\Http\Message\ResponseInterface;
use League\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use League\Route\Strategy\ApplicationStrategy;
use League\Route\Http\Exception\NotFoundException;

class WebStrategy extends ApplicationStrategy
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getNotFoundDecorator(NotFoundException $exception)
    {
        $path = $this->container->get('Psr\Http\Message\ServerRequestInterface')->getUri()->getPath();
        $view = ('/' === $path) ? 'index' : $path;
        $templates = $this->container->get('League\Plates\Engine');

        if ($templates->exists($path)) {
            return $this->render($templates, $view);
        }

        if ($templates->exists('404')) {
            return $this->render($templates, '404');
        }

        throw $exception;
    }

    protected function render(Engine $templates, $view)
    {
        return function (
            ServerRequestInterface $request,
            ResponseInterface $response
        ) use ($templates, $view): ResponseInterface {
            $response->getBody()->write(
                $templates->render($view)
            );

            return $response;
        };
    }
}
