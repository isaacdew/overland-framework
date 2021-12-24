<?php

namespace Overland\Core\Router;

use InvalidArgumentException;

class Route
{
    protected $basePath;
    protected $path;
    protected $method;
    protected $attributes = [
        'action' => '',
        'middleware' => [],
        'prefix' => '',
        'name' => ''
    ];

    public function __construct($basePath, $path, $attributes, $method)
    {
        $this->basePath = $basePath;
        $this->path = isset($attributes['prefix']) ? trim($attributes['prefix'], '/') . '/' .  $path : $path;
        $this->attributes = array_merge($this->attributes, $attributes);
        $this->method = $method;
    }

    public function register()
    {
        register_rest_route($this->basePath, $this->path, array(
            'methods' => $this->method,
            'callback' => $this->getActionCallback(),
            'permission_callback' => '__return_true'
        ));
    }

    public function getFullPath()
    {
        return '/' . $this->basePath . '/' . $this->path;
    }

    public function prefix($prefix)
    {
        $this->path = trim($prefix, '/') . '/' . $this->path;

        return $this;
    }

    protected function getActionCallback()
    {
        if (is_string($this->attributes['action']) && str_contains($this->attributes['action'], '@')) {
            return $this->buildActionClass(explode('@', $this->attributes['action']));
        }

        if(is_array($this->attributes['action'])) {
            return $this->buildActionClass($this->attributes['action']);
        }

        return $this->attributes['action'];
    }

    protected function buildActionClass(array $action) {
        [$controller, $method] = $action;

        $controller = str_starts_with($controller, 'Overland') ? $controller : "\Overland\App\Controllers\\{$controller}";

        return [new $controller, $method];
    }

    public function __call($name, $arguments)
    {

        if (empty($arguments)) {
            return $this->attributes[$name] ?? $this->{$name};
        }

        if(!in_array($name, array_keys($this->attributes))) {
            throw new InvalidArgumentException("Attribute [{$name}] does not exist.");
        }
        $this->attributes[$name] = $arguments[0];

        return $this;
    }
}
