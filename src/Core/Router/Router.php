<?php

namespace Overland\Core\Router;

use Closure;
use Overland\Core\App;
use Overland\Core\Middleware;
use Overland\Core\Router\RouteRegistrar;

class Router {
    protected $basePath;

    protected App $app;
    protected RouteCollection $routes;

    protected array $groupAttributes = [];

    public function __construct($app)
    {
        $this->app = $app;
        $this->basePath = $app->config()->get('app.basePath');
        $this->routes = new RouteCollection();
    }

    public function group($attributes, $callback) {
        $this->groupAttributes = $attributes;
        $callback($this);

        $this->groupAttributes = [];
        return $this;
    }

    public function get($path, $action) {
        return $this->addRoute($path, $action, 'GET');
    }

    public function post($path, $action) {
        return $this->addRoute($path, $action, 'POST');
    }

    public function registerRoutes() {
        add_action('rest_api_init', function() {
            foreach($this->routes as $route) {
                $route->register();
            }
        });
    }

    public function getRoutes() {
        return $this->routes;
    }

    public function addRoute($path, $attributes, $method) {
        if(!is_array($attributes)) {
            $attributes = ['action' => $attributes];
        }
        if(!empty($this->groupAttributes)) {
            $attributes = array_merge($this->groupAttributes, $attributes);
        }
        $route = new Route($this->basePath, $path, $attributes, $method);
        $this->routes->push($route);
        return $route;
    }

    public function __call($method, $arguments)
    {
        return (new RouteRegistrar($this))->attribute($method, $arguments[0] ?? true);
    }
}
