<?php

namespace Overland\Core;

use ArrayAccess;
use Closure;

class App implements ArrayAccess
{
    protected $serviceProviders = [];
    protected $items = [];
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

        return $this;
    }

    public function singleton($name, $callback) {
        $this->bind($name, $callback, true);

        return $this;
    }

    public function bind($name, $callback, $singleton = false) {
        $this->items[$name] = [
            'value' => $singleton ? $callback($this) : $callback,
            'singleton' => $singleton
        ];

        return $this;
    }

    public function offsetExists($offset): bool
    {
        return isset($this->items[$offset]);
    }

    public function offsetGet($offset): mixed
    {
        if(isset($this->items[$offset]) && $this->items[$offset]['singleton']) {
            return $this->items[$offset]['value'];
        }

        if($this->items[$offset]['value'] instanceof Closure) {
            return $this->items[$offset]['value']($this);
        }
        return $this->items[$offset]['value'];
    }

    public function offsetSet($offset, $value): void
    {
        $this->items[$offset]['value'] = $value;
        $this->items[$offset]['singleton'] = false;
    }

    public function offsetUnset($offset): void
    {
        unset($this->items[$offset]);
    }

    public function config() {
        return $this->config;
    }
}
