<?php

namespace Sys\Providers;

use League\Plates\Engine;
use League\Plates\Extension\URI;
use League\Plates\Extension\Asset;
use Sys\PlatesExtensions\LaravelMix;
use Zend\Diactoros\ServerRequestFactory;

class PlatesServiceProvider extends ServiceProvider
{
    protected $provides = [
        Engine::class,
    ];

    public function register()
    {
        $this->container->share(Engine::class, function () {
            $templates = new Engine($this->container->get('app.templatesPath'));

            $templates->loadExtension(new Asset($this->container->get('app.publicPath')));
            $templates->loadExtension(new URI(ServerRequestFactory::marshalRequestUri($_SERVER)));
            $templates->loadExtension(new LaravelMix($this->container->get('app.publicPath')));

            return $templates;
        });
    }
}
