<?php

namespace Overland\Core;

use ArrayAccess;

class App implements ArrayAccess
{
    protected $serviceProviders = [];
    protected $singletons = [];
    protected $config;

    public function __construct(Config $config)
    {   
        $this->config = $config;
        $this->serviceProviders = $this->config->get('app.serviceProviders') ?? [];
    }

    public function register($provider) {
        $this->serviceProviders[] = $provider;
    }

    public function boot() {
        foreach($this->serviceProviders as $provider) {
            $provider = new $provider($this);
            $provider->boot();
        }
    }

    public function singleton($name, $callback) {
        $this->singletons[$name] = $callback($this);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->singletons[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        return $this->singletons[$offset];
    }

    public function offsetSet($offset, $value): void
    {
        $this->singletons[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        unset($this->singletons[$offset]);
    }

    public function config() {
        return $this->config;
    }
}
