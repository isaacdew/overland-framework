<?php

namespace Overland\Core\Authentication;

use Overland\Core\Facades\Auth;
use Overland\Core\Interfaces\ServiceProvider;

class AuthServiceProvider extends ServiceProvider {
    public function boot() {
        $this->app->singleton('auth', function() {
            return new Auth();
        });

        Auth::setApp($this->app);
    }
}
