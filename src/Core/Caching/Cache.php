<?php

namespace Overland\Core\Caching;

use InvalidArgumentException;

class Cache
{

    protected $driver;

    protected $passthru = [
        'get',
        'put',
        'has',
        'forget'
    ];

    public function __construct(CacheDriverInterface $driver)
    {
        $this->driver = $driver;
    }

    public function remember($key, $seconds, $callback)
    {
        if($this->driver->has($key)) {
            return $this->driver->get($key);
        }

        $value = $callback();
        $this->driver->put($key, $value, $seconds);

        return $value;
    }

    public function __call($name, $arguments)
    {
        if(!in_array($name, $this->passthru)) {
            throw new InvalidArgumentException("Attribute [{$name}] does not exist.");
        }

        $this->driver->{$name}(...$arguments);
    }
}
