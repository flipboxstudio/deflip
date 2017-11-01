<?php

use App\Http\Kernel;

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = new Kernel($app);

return $kernel->run();
