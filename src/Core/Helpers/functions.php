<?php

use Overland\Core\Facades\Route;
use Overland\Core\OverlandException;
use Overland\Core\View;

function overland_route($name, $params = [])
{
    $route = Route::getRoutes()->findByName($name);
    if (!$route) {
        throw new OverlandException("Could not find a route with the name '{$name}'.");
    }

    if (empty($params) && $route->hasParams()) {
        throw new OverlandException('Missing route parameters.');
    }

    $fullPath = $route->getFullPath();

    if (count($params)) {
        $regexArray = [];
        foreach ($params as $param => $value) {
            $regexArray["/\{$param\}/"] = fn () => $value;
        }

        $fullPath = preg_replace_callback_array($regexArray, $fullPath);
    }
    return get_rest_url(null, $fullPath);
}

function overland_view($path, $params = [])
{
    return View::make($path, $params);
}
