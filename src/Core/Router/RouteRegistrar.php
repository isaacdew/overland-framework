<?php

namespace Overland\Core\Router;

use BadMethodCallException;
use InvalidArgumentException;

class RouteRegistrar {
    protected array $attributes = [];

    protected array $methods = [
        'get',
        'post',
        'put',
        'delete',
        'options'
    ];

    protected array $allowedAttributes = [
        'middleware',
        'name',
        'prefix'
    ];

    protected $router;

    public function __construct($router)
    {
        $this->router = $router;
    }

    public function group($callback) {
        return $this->router->group($this->attributes, $callback);
    }

    public function attribute($key, $value) {
        if (! in_array($key, $this->allowedAttributes)) {
            throw new InvalidArgumentException("Attribute [{$key}] does not exist.");
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    public function registerRoute($method, $uri, $action) {
        $action = array_merge($this->attributes, ['action' => $action]);

        return $this->router->addRoute($uri, $action, strtoupper($method));
    }

    public function __call($method, $arguments)
    {
        if(in_array($method, $this->methods)) {
            return $this->registerRoute($method, ...$arguments);
        }

        if(in_array($method, $this->allowedAttributes)) {
            return $this->attribute($method, $arguments[0] ?? true);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}
