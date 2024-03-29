<?php

namespace Overland\Core\Router;

use Overland\Core\App;
use Overland\Core\Router\RouteRegistrar;

class Router
{
    protected $basePath;

    protected App $app;
    protected RouteCollection $routes;

    protected array $groupAttributes = [];

    public function __construct($app, $routes = null)
    {
        $this->app = $app;
        $this->basePath = $app->config()->get('app.basePath');
        $this->routes = $routes ?? new RouteCollection();
    }

    public function group($attributes, $callback)
    {
        $this->groupAttributes = $attributes;
        $callback($this);

        $this->groupAttributes = [];
        return $this;
    }

    public function registerRoutes()
    {
        add_action('rest_api_init', [$this, 'initAPI']);
        return $this;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function addRoute($path, $attributes, $method)
    {
        if (!is_array($attributes)) {
            $attributes = ['action' => $attributes];
        }
        if (!empty($this->groupAttributes)) {
            $attributes = array_merge($this->groupAttributes, $attributes);
        }
        $route = new Route($this->basePath, $path, $attributes, $method);
        $this->routes->push($route);
        return $route;
    }

    public function __call($method, $arguments)
    {
        return (new RouteRegistrar($this))->{$method}(...$arguments);
    }

    public function initAPI()
    {
        foreach ($this->routes as $route) {
            $route->register();
        }
    }
}
