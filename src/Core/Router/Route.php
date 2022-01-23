<?php

namespace Overland\Core\Router;

use InvalidArgumentException;
use Overland\Core\Facades\RouteBinding;
use WP_REST_Request;

class Route
{
    protected $basePath;
    protected $path;
    protected $compiledPath;
    protected $method;
    protected $paramCount;
    protected $attributes = [
        'action' => '',
        'middleware' => [],
        'prefix' => '',
        'name' => ''
    ];

    protected $allowedAttributs = [
        'action',
        'middleware',
        'prefix',
        'name',
        'method',
        'path',
        'bindings'
    ];

    protected $bindings = [];

    public function __construct($basePath, $path, $attributes, $method)
    {
        $this->basePath = $basePath;
        $this->path = isset($attributes['prefix']) ? trim($attributes['prefix'], '/') . '/' .  $path : $path;
        $this->attributes = array_merge($this->attributes, $attributes);
        $this->method = $method;

        // Here we support URI params
        $this->compiledPath = preg_replace_callback(
            '/{(.*?)}/',
            function ($matches) {
                $this->bindings[] = $matches[1];
                return "(?P<{$matches[1]}>\S+)";
            },
            $this->path,
            -1,
            $this->paramCount
        );
    }

    public function register()
    {
        register_rest_route($this->basePath, $this->compiledPath, array(
            'methods' => $this->method,
            'callback' => empty($this->bindings) ? $this->getActionCallback() : [$this, 'handleWithBindings'],
            'permission_callback' => '__return_true'
        ));
    }

    public function hasParams()
    {
        return $this->paramCount > 0;
    }

    public function getFullPath()
    {
        return '/' . $this->basePath . '/' . $this->path;
    }

    public function getCompiledPath()
    {
        return $this->compiledPath;
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
        } else if (is_array($this->attributes['action'])) {
            return $this->buildActionClass($this->attributes['action']);
        }

        return $this->attributes['action'];
    }

    protected function handleWithBindings(WP_REST_Request $request)
    {
        $action = $this->getActionCallback();

        $bindings = RouteBinding::resolve($this->bindings);

        $arguments = [];
        foreach($bindings as $key => $binding) {
            $arguments[] = new $binding($request[$key]);
        }

        $arguments[] = $request;

        if(is_array($action)) {
            return $action[0]->{$action[1]}(...$arguments);
        }

        return $action(...$arguments);
    }

    protected function buildActionClass(array $action)
    {
        [$controller, $method] = $action;

        $controller = str_starts_with($controller, 'Overland') ? $controller : "\Overland\App\Controllers\\{$controller}";

        return [new $controller, $method];
    }

    public function __call($name, $arguments)
    {
        if (!in_array($name, $this->allowedAttributs)) {
            throw new InvalidArgumentException("Attribute [{$name}] does not exist.");
        }

        if (empty($arguments)) {
            return $this->attributes[$name] ?? $this->{$name};
        }

        $this->attributes[$name] = $arguments[0];

        return $this;
    }
}
