<?php

namespace Sys\Exceptions;

class SimpleErrorHandler extends Handler
{
    public function handle()
    {
        echo 'Something goes wrong: '.
            env('APP_DEBUG', false)
                ? $this->exception->getMessage()
                : '';
    }
}
