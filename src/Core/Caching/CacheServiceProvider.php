<?php

namespace Overland\Core\Caching;

use Overland\Core\Caching\Drivers\Transient;
use Overland\Core\Facades\Cache;
use Overland\Core\Interfaces\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->singleton('cache', function($app) {
            $driverName = $app->config()->get('app.cache.driver');
            $driver = $this->getDriver($driverName);
            return new $driver($app);
        });

        Cache::setApp($this->app);
    }

    public function getDriver($name)
    {
        $mapping = [
            'transient' => Transient::class
        ];

        return $mapping[$name];
    }
}