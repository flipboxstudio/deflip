<?php

use Dotenv\Dotenv;
use Sys\Application;
use App\Exceptions\Handler;
use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;

(new Dotenv(__DIR__.'/..'))->load();

$app = new Application(__DIR__.'/..');

$app->registerErrorHandler(Handler::class);

$app->addServiceProvider(AppServiceProvider::class);
$app->addServiceProvider(RouteServiceProvider::class);

return $app;
