<?php

namespace Overland\Core\Router;

class Route {
    protected $basePath;
    protected $path;
    protected $action;
    protected $method;
    protected $name = '';
    protected $attributes = [];
    protected $middleware = [];

    public function __construct($basePath, $path, $attributes, $method)
    { 
        $this->basePath = $basePath;
        $this->path = isset($attributes['prefix']) ? trim($attributes['prefix'], '/') . '/' .  $path : $path;
        $this->attributes = $attributes;
        $this->method = $method;
        $this->action = $attributes['action'];
        $this->middleware = $attributes['middleware'] ?? [];
    }

    public function register() {
        register_rest_route( $this->basePath, $this->path, array(
            'methods' => $this->method,
            'callback' => $this->actionInstance()
          ) );
    }

    public function getFullPath() {
        return '/' . $this->basePath . '/' . $this->path;
    }

    public function getMiddleware() {
        return $this->middleware;
    }

    public function prefix($prefix) {
        $this->path = trim($prefix, '/') . '/' . $this->path;

        return $this;
    }

    protected function actionInstance() {
        if(is_string($this->action) && str_contains($this->action, '@')) {
            [$controller, $method] = explode('@', $this->action);
    
            $controller = "\Overland\App\Controllers\\{$controller}";
    
            return [new $controller, $method];
        }

        return $this->action;
    }

    public function __call($name, $arguments)
    {
        if(empty($arguments)) {
            return $this->attributes[$name] ?? $this->{$name};
        }

        $this->attributes[$name] = $arguments[0];

        return $this;
    }
}
