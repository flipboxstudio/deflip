<?php

namespace Sys\Providers;

use League\Plates\Engine;

class PlatesServiceProvider extends ServiceProvider
{
    protected $provides = [
        'League\Plates\Engine',
    ];

    public function register()
    {
        $this->container->share('League\Plates\Engine', function () {
            $templates = new Engine($this->container->get('app.templatesPath'));

            return $templates;
        });
    }
}
