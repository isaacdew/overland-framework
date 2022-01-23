<?php

namespace Overland\Core\Router;

use Overland\Core\Facades\Route;
use Overland\Core\Facades\RouteBinding as RouteBindingFacade;
use Overland\Core\Interfaces\ServiceProvider;
use WP_Post;
use WP_User;

class RouterServiceProvider extends ServiceProvider {
    public function boot() {
        // Register router
        $this->registerRouter();
    }

    protected function registerRouter() {
        $this->app->singleton('router', function ($app) {
            return new Router($app);
        });

        $this->app->singleton('routeBinding', function() {
            return new RouteBinding();
        });

        Route::setApp($this->app);
        RouteBindingFacade::setApp($this->app);
        require_once $this->app->config()->get('app.pluginRoot') . 'routes.php';

        $this->app['router']->registerRoutes();
    }
}
