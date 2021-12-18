<?php

namespace Overland\Core\Router;

use Overland\Core\Interfaces\Collection;

class RouteCollection extends Collection
{

    public function findByName($name)
    {
        return $this->find(fn ($route) => $route->name() == $name);
    }

    public function whereHasMiddleware()
    {
        return new static(array_filter($this->items, fn ($route) => count($route->middleware())));
    }

    public function findRoute($path, $method)
    {
        return $this->find(function ($route) use ($path, $method) {
            return $route->getFullPath() == $path && $route->method() == $method;
        });
    }
}
