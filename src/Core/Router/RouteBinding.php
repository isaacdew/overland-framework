<?php

namespace Overland\Core\Router;

class RouteBinding
{
    protected $bindings = [
        'post' => WP_Post::class,
        'user' => WP_User::class
    ];

    public function resolve($name)
    {
        if(is_array($name)) {
            return array_intersect_key($this->bindings, array_flip($name));
        }

        return $this->bindings[$name];
    }

    public function bind($name, $class)
    {
        $this->bindings[$name] = $class;
    }
}
