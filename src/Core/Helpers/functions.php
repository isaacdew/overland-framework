<?php

use Overland\Core\Facades\Route;
use Overland\Core\OverlandException;

function overland_route($name, $params = []) {
    $route = Route::getRoutes()->findByName($name);
    if(!$route) {
        throw new OverlandException("A route with the name '{$name}' does not exist.");
    }

    $fullPath = $route->getFullPath();
    if(empty($params) && $route->hasParams()) {
        throw new OverlandException('Missing route parameters.');
    }

    if(count($params)) {
        $regexArray = [];
        foreach($params as $param => $value) {
            $regexArray["/\{$param\}/"] = fn() => $value;
        }

        $fullPath = preg_replace_callback_array($regexArray, $fullPath);
    }
    return get_rest_url(null, $fullPath);
}
