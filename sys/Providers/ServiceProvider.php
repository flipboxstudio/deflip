<?php

namespace Sys\Providers;

use League\Container\ContainerInterface;
use League\Container\ContainerAwareInterface;
use League\Container\ServiceProvider\ServiceProviderInterface;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

abstract class ServiceProvider implements ContainerAwareInterface, ServiceProviderInterface, BootableServiceProviderInterface
{
    protected $container;

    protected $provides = [];

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function provides($service = null)
    {
        if (!$service) {
            return $this->provides;
        }

        if (!is_string($service)) {
            return false;
        }

        return in_array($service, $this->provides);
    }

    public function boot()
    {
    }

    public function register()
    {
    }
}
