<?php

namespace Sys\Route;

use League\Route\RouteCollection as BaseRouteCollection;

class RouteCollection extends BaseRouteCollection
{
    /**
     * {@inheritdoc}
     */
    public function map($method, $path, $handler)
    {
        $path  = sprintf('/%s', ltrim($path, '/'));
        $route = (new Route)->setMethods((array) $method)->setPath($path)->setCallable($handler);

        $this->routes[] = $route;

        return $route;
    }
}