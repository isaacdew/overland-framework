<?php

namespace Overland\Core\Router;

use Overland\Core\Interfaces\Collection;

class RouteCollection extends Collection {

    public function findByName($name) {
        $filteredItems = array_filter($this->items, fn($route) => $route->name() == $name);
        return reset($filteredItems) ?? false;
    }

    public function whereHasMiddleware() {
        return new static(array_filter($this->items, fn($route) => count($route->middleware())));
    }

    public function find($path, $method) {
        $filteredItems = array_filter($this->items, function($route) use ($path, $method) {
            return $route->getFullPath() == $path && $route->method() == $method;
        });
        return reset($filteredItems) ?? false;
    }
}
