<?php

use Overland\Core\Facades\Route;

function overland_route($name) {
    $route = Route::getRoutes()->findByName($name);

    return get_site_url() . $route->getFullPath();
}
