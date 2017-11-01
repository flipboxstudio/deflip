<?php

namespace App\Providers;

use App\Services\Api\Api;
use Sys\Providers\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $provides = [
        Api::class,
    ];

    public function register()
    {
        $this->container->share(Api::class, function () {
            return new Api($this->container);
        });
    }
}
