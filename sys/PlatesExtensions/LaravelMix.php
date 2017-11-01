<?php

namespace Sys\PlatesExtensions;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;

class LaravelMix implements ExtensionInterface
{
    protected $publicPath;

    protected $engine;

    protected static $manifest = null;

    public function __construct(string $publicPath)
    {
        $this->publicPath = $publicPath;
    }

    public function register(Engine $engine)
    {
        $this->engine = $engine;

        $engine->registerFunction('mix', [$this, 'mix']);
    }

    public function mix(string $assetPath): string
    {
        if (!file_exists($this->publicPath.'/hot')) {
            return $this->manifest($assetPath);
        }

        return "http://localhost:8080{$assetPath}";
    }

    protected function manifest($assetPath)
    {
        if (static::$manifest === null) {
            static::$manifest = json_decode(file_get_contents($this->publicPath.'/mix-manifest.json'), true);
        }

        return static::$manifest[$assetPath];
    }
}
