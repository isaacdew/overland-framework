<?php

namespace Overland\Core\Router;

class RouteBinding
{
    protected $bindings = [];

    public function __construct()
    {
        $this->bindings = [
            'post' => fn($value) => get_post($value),
            'user' => fn($value) => get_user_by('ID', $value),
        ];
    }

    public function resolve($name)
    {
        if(is_array($name)) {
            return array_intersect_key($this->bindings, array_flip($name));
        }

        return $this->bindings[$name];
    }

    public function bind($name, $callback)
    {
        $this->bindings[$name] = $callback;
    }
}
