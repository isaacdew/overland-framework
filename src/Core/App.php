<?php

namespace Overland\Core;

use ArrayAccess;

class App implements ArrayAccess
{
    protected $serviceProviders = [];
    protected $singletons = [];

    public function register($provider) {
        $this->serviceProviders[] = $provider;
    }

    public function boot() {
        if($this->singletons['config']) {
            $this->serviceProviders = array_merge($this->singletons['config']->get('app.serviceProviders'));
        }

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
}
