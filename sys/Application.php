<?php

namespace Sys;

use Whoops\Run;
use League\Container\Container;
use Whoops\Handler\HandlerInterface;
use Whoops\Handler\PrettyPageHandler;
use Sys\Exceptions\SimpleErrorHandler;

class Application
{
    protected $rootPath;

    protected $container;

    protected $booted = false;

    protected $preloadedProviders = [
        'Sys\Providers\HttpKernelServiceProvider',
        'Sys\Providers\PlatesServiceProvider',
    ];

    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
        $this->container = new Container();
        $this->container->share(self::class, function () {
            return $this;
        });
        $this->container->share(Container::class, function () {
            return $this->container;
        });
    }

    public function bootIfNotBooted()
    {
        if (!$this->booted) {
            $this->boot();
        }
    }

    public function registerErrorHandler(string $handler)
    {
        $this->container->share(HandlerInterface::class, $handler)->withArguments([$this]);
    }

    protected function bootErrorHandler()
    {
        $errorHandler = new Run();

        $errorHandler->pushHandler(
            env('APP_DEBUG', false)
                ? new PrettyPageHandler()
                : $this->createDefaultNonDebugErrorHandler()
        );

        $errorHandler->register();
    }

    protected function createDefaultNonDebugErrorHandler(): HandlerInterface
    {
        if ($this->container->has(HandlerInterface::class)) {
            return $this->container->get(HandlerInterface::class);
        }

        return new SimpleErrorHandler($this);
    }

    protected function boot()
    {
        $this->bootErrorHandler();

        $this->registerPath();

        $this->registerPreloadedProviders();

        $this->boot = true;
    }

    protected function registerPath()
    {
        foreach ([
            'app.path' => '',
            'app.publicPath' => '/public',
            'app.routesPath' => '/routes',
            'app.templatesPath' => '/resources/views',
        ] as $pathName => $relativePath) {
            $this->container->share($pathName, function () use ($relativePath) {
                return $this->rootPath.$relativePath;
            });
        }
    }

    protected function registerPreloadedProviders()
    {
        foreach ($this->preloadedProviders as $provider) {
            $this->container->addServiceProvider($provider);
        }
    }

    public function __call($method, array $arguments = [])
    {
        return call_user_func_array([$this->container, $method], $arguments);
    }
}
