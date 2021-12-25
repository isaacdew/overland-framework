<?php

namespace Overland\Core\Authentication;

use Overland\Core\Facades\Auth;
use Overland\Core\Interfaces\ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
    public function boot() {
        $this->app->singleton('auth', function($app) {
            return new \Overland\Core\Authentication\Auth($app);
        });

        Auth::setApp($this->app);
    }
}
