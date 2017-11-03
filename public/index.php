<?php

require_once __DIR__.'/../vendor/autoload.php';

use Dotenv\Dotenv;
use Sys\Application;
use Sys\HttpKernel;
use App\Providers\AppServiceProvider;
use App\Providers\RouteServiceProvider;

(new Dotenv(__DIR__.'/..'))->load();

$app = new Application(__DIR__.'/..');
$httpKernel = new HttpKernel($app);

return $httpKernel->run();
