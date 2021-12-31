<?php

namespace Overland\Core\Caching;

abstract class CacheDriver
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    abstract public function get($key);

    abstract public function put($key, $value, $seconds = 0);

    abstract public function has($key);

    abstract public function forget($key);
}
