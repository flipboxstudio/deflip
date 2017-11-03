<?php

namespace Sys\Route\Providers;

use Sys\Route\RouteCollection;
use Sys\Providers\ServiceProvider;
use Sys\Route\Strategies\WebStrategy;

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

            $route->setStrategy(new WebStrategy($this->container));

            require_once $this->container->get('app.path').'/routes.php';

            return $route;
        });
    }

    protected function registerController()
    {
        foreach ($this->controllers as $controller) {
            $this->container->add($controller);
        }
    }
}
