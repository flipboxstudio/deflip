<?php

namespace Sys;

use Sys\Routing\Router;
use League\Plates\Engine;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\Debug\Debug;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Debug\ErrorHandler;
use Zend\Diactoros\Response as PsrResponse;
use Symfony\Component\Debug\DebugClassLoader;
use Symfony\Component\Debug\ExceptionHandler;
use Illuminate\Http\Response as IlluminateResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Application extends Container
{
    use Concerns\RoutesRequests;

    /**
     * Indicates if the class aliases have been registered.
     *
     * @var bool
     */
    protected static $aliasesRegistered = false;

    /**
     * The base path of the application installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The loaded service providers.
     *
     * @var array
     */
    protected $loadedProviders = [];

    /**
     * The service binding methods that have been executed.
     *
     * @var array
     */
    protected $ranServiceBinders = [];

    /**
     * The application namespace.
     *
     * @var string
     */
    protected $namespace;

    /**
     * The Router instance.
     *
     * @var \Sys\Routing\Router
     */
    public $router;

    /**
     * Create a new Lumen application instance.
     *
     * @param string|null $basePath
     */
    public function __construct($basePath = null)
    {
        if (!empty(env('APP_TIMEZONE'))) {
            date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));
        }

        if (env('APP_DEBUG')) {
            Debug::enable();
            ErrorHandler::register();
            ExceptionHandler::register();
            DebugClassLoader::enable();
        }

        $this->basePath = $basePath;

        $this->bootstrapContainer();
        $this->bootstrapRouter();
    }

    /**
     * Bootstrap the application container.
     */
    protected function bootstrapContainer()
    {
        static::setInstance($this);

        $this->instance('app', $this);
        $this->instance('Sys\Application', $this);

        $this->instance('path', $this->path());

        $this->registerContainerAliases();
    }

    /**
     * Bootstrap the router instance.
     */
    public function bootstrapRouter()
    {
        $this->router = new Router($this);
    }

    /**
     * Get or check the current application environment.
     *
     * @param  mixed
     *
     * @return string
     */
    public function environment()
    {
        $env = env('APP_ENV', 'production');

        if (func_num_args() > 0) {
            $patterns = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();

            foreach ($patterns as $pattern) {
                if (Str::is($pattern, $env)) {
                    return true;
                }
            }

            return false;
        }

        return $env;
    }

    /**
     * Register a service provider with the application.
     *
     * @param \Illuminate\Support\ServiceProvider|string $provider
     *
     * @return \Illuminate\Support\ServiceProvider
     */
    public function register($provider)
    {
        if (!$provider instanceof ServiceProvider) {
            $provider = new $provider($this);
        }

        if (array_key_exists($providerName = get_class($provider), $this->loadedProviders)) {
            return;
        }

        $this->loadedProviders[$providerName] = true;

        if (method_exists($provider, 'register')) {
            $provider->register();
        }

        if (method_exists($provider, 'boot')) {
            return $this->call([$provider, 'boot']);
        }
    }

    /**
     * Send exception to handler.
     *
     * @param Error|Exception $e
     */
    public function sendExceptionToHandler($e): IlluminateResponse
    {
        $viewEngine = $this->make('view');

        if (!$e instanceof HttpException) {
            if ($viewEngine->exists('500')) {
                return new IlluminateResponse(
                    $this->view->render('500'),
                    500
                );
            }

            throw $e;
        }

        if ($viewEngine->exists($e->getStatusCode())) {
            return new IlluminateResponse(
                $this->view->render($e->getStatusCode()),
                $e->getStatusCode(),
                $e->getHeaders()
            );
        }

        throw $e;
    }

    /**
     * Register a deferred provider and service.
     *
     * @param string $provider
     */
    public function registerDeferredProvider($provider)
    {
        return $this->register($provider);
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @param array  $parameters
     *
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        $abstract = $this->getAlias($abstract);

        if (array_key_exists($abstract, $this->availableBindings) &&
            !array_key_exists($this->availableBindings[$abstract], $this->ranServiceBinders)) {
            $this->{$method = $this->availableBindings[$abstract]}();

            $this->ranServiceBinders[$method] = true;
        }

        return parent::make($abstract, $parameters);
    }

    /**
     * Register container bindings for the application.
     */
    protected function registerRouterBindings()
    {
        $this->singleton('router', function () {
            return $this->router;
        });
    }

    /**
     * Prepare the given request instance for use with the application.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Illuminate\Http\Request
     */
    protected function prepareRequest(SymfonyRequest $request)
    {
        if (!$request instanceof Request) {
            $request = Request::createFromBase($request);
        }

        $request->setUserResolver(function ($guard = null) {
            return $this->make('auth')->guard($guard)->user();
        })->setRouteResolver(function () {
            return $this->currentRoute;
        });

        return $request;
    }

    /**
     * Register container bindings for the PSR-7 request implementation.
     */
    protected function registerPsrRequestBindings()
    {
        $this->singleton('Psr\Http\Message\ServerRequestInterface', function () {
            return (new DiactorosFactory())->createRequest($this->make('request'));
        });
    }

    /**
     * Register container bindings for the PSR-7 response implementation.
     */
    protected function registerPsrResponseBindings()
    {
        $this->singleton('Psr\Http\Message\ResponseInterface', function () {
            return new PsrResponse();
        });
    }

    /**
     * Register container bindings for the application.
     */
    protected function registerUrlGeneratorBindings()
    {
        $this->singleton('url', function () {
            return new Routing\UrlGenerator($this);
        });
    }

    /**
     * Register container bindings for the application.
     */
    protected function registerValidatorBindings()
    {
        $this->singleton('validator', function () {
            $this->register('Illuminate\Validation\ValidationServiceProvider');

            return $this->make('validator');
        });
    }

    /**
     * Register container bindings for the application.
     */
    protected function registerViewBindings()
    {
        $this->singleton('League\Plates\Engine', function () {
            return new Engine(
                $this->basePath('views')
            );
        });
    }

    /**
     * Register the facades for the application.
     *
     * @param bool  $aliases
     * @param array $userAliases
     */
    public function withFacades($aliases = true, $userAliases = [])
    {
        Facade::setFacadeApplication($this);

        if ($aliases) {
            $this->withAliases($userAliases);
        }
    }

    /**
     * Register the aliases for the application.
     *
     * @param array $userAliases
     */
    public function withAliases($userAliases = [])
    {
        $defaults = [
            'Illuminate\Support\Facades\URL' => 'URL',
            'Illuminate\Support\Facades\Validator' => 'Validator',
        ];

        if (!static::$aliasesRegistered) {
            static::$aliasesRegistered = true;

            $merged = array_merge($defaults, $userAliases);

            foreach ($merged as $original => $alias) {
                class_alias($original, $alias);
            }
        }
    }

    /**
     * Load the Eloquent library for the application.
     */
    public function withEloquent()
    {
        $this->make('db');
    }

    /**
     * Get the path to the application "app" directory.
     *
     * @return string
     */
    public function path()
    {
        return $this->basePath.DIRECTORY_SEPARATOR.'app';
    }

    /**
     * Get the base path for the application.
     *
     * @param string|null $path
     *
     * @return string
     */
    public function basePath($path = null)
    {
        if (isset($this->basePath)) {
            return $this->basePath.($path ? '/'.$path : $path);
        }

        if ($this->runningInConsole()) {
            $this->basePath = getcwd();
        } else {
            $this->basePath = realpath(getcwd().'/../');
        }

        return $this->basePath($path);
    }

    /**
     * Register the core container aliases.
     */
    protected function registerContainerAliases()
    {
        $this->aliases = [
            'Illuminate\Contracts\Foundation\Application' => 'app',
            'Illuminate\Container\Container' => 'app',
            'Illuminate\Contracts\Container\Container' => 'app',
            'request' => 'Illuminate\Http\Request',
            'Sys\Routing\Router' => 'router',
            'Sys\Routing\UrlGenerator' => 'url',
            'view' => 'League\Plates\Engine',
        ];
    }

    /**
     * The available container bindings and their respective load methods.
     *
     * @var array
     */
    public $availableBindings = [
        'router' => 'registerRouterBindings',
        'Psr\Http\Message\ServerRequestInterface' => 'registerPsrRequestBindings',
        'Psr\Http\Message\ResponseInterface' => 'registerPsrResponseBindings',
        'url' => 'registerUrlGeneratorBindings',
        'validator' => 'registerValidatorBindings',
        'Illuminate\Contracts\Validation\Factory' => 'registerValidatorBindings',
        'League\Plates\Engine' => 'registerViewBindings',
        'view' => 'registerViewBindings',
    ];
}
