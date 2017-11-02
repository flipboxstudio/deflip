<?php

namespace Sys\Providers;

use ReflectionClass;
use Sys\RouteStrategy;
use ReflectionParameter;
use League\Route\RouteCollection;

class RouteServiceProvider extends ServiceProvider
{
    protected $controllers = [];

    protected $provides = [
        'League\Route\RouteCollection',
    ];

    public function register()
    {
        $this->registerRoute();

        $this->registerController();
    }

    protected function registerRoute()
    {
        $this->container->share('League\Route\RouteCollection', function () {
            $route = new RouteCollection($this->container);

            $route->setStrategy(new RouteStrategy($this->container));

            require_once $this->container->get('app.routesPath').'/web.php';

            return $route;
        });
    }

    protected function registerController()
    {
        foreach ($this->controllers as $controller) {
            $this->container->add($controller)->withArguments(
                array_map(function (ReflectionParameter $parameter): string {
                    return $parameter->getType()->getName();
                }, (new ReflectionClass($controller))->getConstructor()->getParameters())
            );
        }
    }
}
