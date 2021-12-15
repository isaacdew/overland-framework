<?php
/**
 * Don't touch :D
 */

use Overland\Core\App;
use Overland\Core\Config;
use Overland\Core\Middleware\MiddlewareServiceProvider;
use Overland\Core\Router\RouterServiceProvider;

$app = new App();

$app['config'] = new Config();
$serviceProviders = $app['config']->get('app.serviceProviders');

$app->register(RouterServiceProvider::class);
$app->register(MiddlewareServiceProvider::class);
$app->boot();
