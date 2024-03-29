<?php

namespace Overland\Core\Caching;

/**
 * @codeCoverageIgnore
 */
abstract class CacheDriver implements CacheDriverInterface
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

    abstract public function flush();
}
