<?php

namespace Sys\Exceptions;

class SimpleErrorHandler extends Handler
{
    public function handle()
    {
        echo 'Something goes wrong';
    }
}