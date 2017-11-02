<?php

require_once __DIR__.'/../vendor/autoload.php';

use Sys\HttpKernel;

$app = require_once(__DIR__.'/../bootstrap/app.php');

$httpKernel = new HttpKernel($app);

return $httpKernel->run();
