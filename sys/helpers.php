<?php

use Illuminate\Support\Str;
use Illuminate\Container\Container;

if (!function_exists('dd')) {
    function dd()
    {
        call_user_func_array('dump', func_get_args());

        die(1);
    }
}

if (!function_exists('abort')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param int    $code
     * @param string $message
     * @param array  $headers
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort($code, $message = '', array $headers = [])
    {
        return app()->abort($code, $message, $headers);
    }
}

if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param string $make
     *
     * @return mixed|\Sys\Application
     */
    function app($make = null)
    {
        if (is_null($make)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($make);
    }
}

if (!function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param string $path
     *
     * @return string
     */
    function base_path($path = '')
    {
        return app()->basePath().($path ? '/'.$path : $path);
    }
}

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if (false === $value) {
            return value($default);
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return;
        }

        if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

if (!function_exists('redirect')) {
    /**
     * Get an instance of the redirector.
     *
     * @param string|null $to
     * @param int         $status
     * @param array       $headers
     * @param bool        $secure
     *
     * @return \Sys\Http\Redirector|\Illuminate\Http\RedirectResponse
     */
    function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        $redirector = new Sys\Http\Redirector(app());

        if (is_null($to)) {
            return $redirector;
        }

        return $redirector->to($to, $status, $headers, $secure);
    }
}

if (!function_exists('response')) {
    /**
     * Return a new response from the application.
     *
     * @param string $content
     * @param int    $status
     * @param array  $headers
     *
     * @return \Illuminate\Http\Response|\Sys\Http\ResponseFactory
     */
    function response($content = '', $status = 200, array $headers = [])
    {
        $factory = new Sys\Http\ResponseFactory();

        if (0 === func_num_args()) {
            return $factory;
        }

        return $factory->make($content, $status, $headers);
    }
}

if (!function_exists('route')) {
    /**
     * Generate a URL to a named route.
     *
     * @param string $name
     * @param array  $parameters
     * @param bool   $secure
     *
     * @return string
     */
    function route($name, $parameters = [], $secure = null)
    {
        return app('url')->route($name, $parameters, $secure);
    }
}

if (!function_exists('url')) {
    /**
     * Generate a url for the application.
     *
     * @param string $path
     * @param mixed  $parameters
     * @param bool   $secure
     *
     * @return string
     */
    function url($path = null, $parameters = [], $secure = null)
    {
        return app('url')->to($path, $parameters, $secure);
    }
}
