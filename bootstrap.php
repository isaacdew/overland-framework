<?php
/**
 * Creates the Overland Application Instance
 */

use Overland\Core\App;
use Overland\Core\Config;
use Overland\Core\Middleware\MiddlewareServiceProvider;
use Overland\Core\Router\RouterServiceProvider;

$config = new Config(
    require_once OVERLAND_PLUGIN_ROOT . 'config.php'
);
$app = new App($config);

$app->register(RouterServiceProvider::class);
$app->register(MiddlewareServiceProvider::class);
$app->boot();
