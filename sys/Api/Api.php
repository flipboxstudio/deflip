<?php

namespace Sys\Api;

use Exception;
use ArrayAccess;
use Illuminate\Contracts\Container\Container;

class Api implements ArrayAccess
{
    protected $container;

    protected $map = [
        'post' => Drivers\Post::class,
    ];

    protected $resolved = [
    ];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function registerDriver($name, $class)
    {
        $this->map[$name] = $class;

        return $this;
    }

    public function registerDrivers(array $drivers)
    {
        foreach ($drivers as $name => $class) {
            $this->registerDriver($name, $class);
        }

        return $this;
    }

    public function make($api)
    {
        if (array_key_exists($api, $this->resolved)) {
            return $this->resolved[$api];
        }

        if ($this->offsetExists($api)) {
            return $this->resolved[$api] = new $this->map[$api]();
        }

        throw new Exception("API {$api} has not been mapped in mapper.");
    }

    public function offsetExists($api)
    {
        return array_key_exists($api, $this->map);
    }

    public function offsetGet($api)
    {
        return $this->make($api);
    }

    public function offsetSet($api, $driver)
    {
        return $this->mak[$api] = $driver;
    }

    public function offsetUnset($api)
    {
        unset($this->map[$api]);
    }

    public function __get($api)
    {
        return $this->make($api);
    }

    public function __call($method, array $arguments = [])
    {
        $api = $this->{$method};

        $apiMethod = array_shift($arguments);

        return call_user_func_array([$api, $apiMethod], $arguments);
    }
}
