<?php

namespace Sys\Exceptions;

use Sys\Application;
use Whoops\RunInterface;
use Whoops\Exception\Inspector;
use Whoops\Handler\HandlerInterface;

abstract class Handler implements HandlerInterface
{
    protected $app;

    protected $run;

    protected $exception;

    protected $inspector;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function setRun(RunInterface $run)
    {
        $this->run = $run;
    }

    public function setException($exception)
    {
        $this->exception = $exception;
    }

    public function setInspector(Inspector $inspector)
    {
        $this->inspector = $inspector;
    }

    abstract public function handle();
}
