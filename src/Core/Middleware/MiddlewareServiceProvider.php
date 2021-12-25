<?php

namespace Overland\Core\Middleware;

use Overland\Core\Interfaces\ServiceProvider;

class MiddlewareServiceProvider extends ServiceProvider {
    public function boot() {
        $this->app->singleton('middleware', function($app) {
            $middleware = new Middleware($this->app->config()->get('app.middleware'));
            $middleware->guard($this->app['router']->getRoutes()->whereHasMiddleware());

            return $middleware;
        });
    }
}
