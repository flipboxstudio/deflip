<?php

namespace Sys\Route;

use InvalidArgumentException;
use League\Route\Route as BaseRoute;

class Route extends BaseRoute
{
    /**
     * {@inheritdoc}
     */
    public function getCallable()
    {
        $callable = $this->callable;

        if (is_string($callable) && strpos($callable, '::') !== false) {
            $callable = explode('::', $callable);
        }

        if (is_array($callable) && isset($callable[0]) && is_object($callable[0])) {
            $callable = [$callable[0], $callable[1]];
        }

        if (is_array($callable) && isset($callable[0]) && is_string($callable[0])) {
            $class = $this->getContainer()->get($callable[0]);

            $callable = [$class, $callable[1]];
        }

        if (! is_callable($callable)) {
            throw new InvalidArgumentException('Could not resolve a callable for this route');
        }

        return $callable;
    }
}