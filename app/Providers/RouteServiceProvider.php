<?php

namespace App\Providers;

use Sys\Providers\RouteServiceProvider as SysRouteServiceProvider;

class RouteServiceProvider extends SysRouteServiceProvider
{
    protected $controllers = [
        'App\Http\Controllers\SiteController',
        'App\Http\Controllers\Api\PostController',
    ];
}
