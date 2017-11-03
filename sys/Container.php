<?php

namespace Sys;

use Exception;
use InvalidArgumentException;
use League\Container\ContainerInterface;
use League\Container\ContainerAwareInterface;
use Illuminate\Container\Container as IlluminateContainer;
use League\Container\ServiceProvider\ServiceProviderInterface;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

class Container implements ContainerInterface
{
    protected $providers = [];

    protected static $container = null;

    public function __construct()
    {
        if (!static::$container) {
            static::$container = new IlluminateContainer();
        }
    }

    public function add($alias, $concrete = null, $share = false)
    {
        static::$container->bind($alias, $concrete, $share);
    }

    public function share($alias, $concrete = null)
    {
        static::$container->singleton($alias, $concrete);
    }

    public function addServiceProvider($provider)
    {
        if (is_string($provider)) {
            $provider = new $provider();
        }

        if ($provider instanceof ContainerAwareInterface) {
            $provider->setContainer($this);
        }

        if ($provider instanceof BootableServiceProviderInterface) {
            $provider->boot();
        }

        if ($provider instanceof ServiceProviderInterface) {
            foreach ($provider->provides() as $service) {
                $this->providers[$service] = $provider;
            }

            return $this;
        }

        throw new InvalidArgumentException(
            "A service provider [{$provider}] must be a fully qualified class name or instance ".
            'of (\League\Container\ServiceProvider\ServiceProviderInterface)'
        );
    }

    public function inflector($type, callable $callback = null)
    {
        $callback = $callback ?: function () { };

        return static::$container->rebinding($type, $callback);
    }

    public function extend($alias)
    {
        throw new Exception('Method not implemented.');
    }

    public function call(callable $callable, array $args = [])
    {
        return static::$container->call($callback, $args);
    }

    public function get($abstract)
    {
        $this->registerProvider($abstract);

        return static::$container->make($abstract);
    }

    public function has($abstract)
    {
        return static::$container->bound($abstract);
    }

    protected function registerProvider($abstract)
    {
        if (!static::$container->bound($abstract) && array_key_exists($abstract, $this->providers)) {
            $this->providers[$abstract]->register();
        }
    }

    public function __call(string $method, array $args = [])
    {
        return call_user_func_array([static::$container, $method], $args);
    }
}
