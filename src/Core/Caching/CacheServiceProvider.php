<?php

namespace Overland\Core\Caching;

use Overland\Core\Caching\Drivers\Redis;
use Overland\Core\Caching\Drivers\Transient;
use Overland\Core\Facades\Cache as CacheFacade;
use Overland\Core\Interfaces\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    protected array $drivers = [
        'transient' => Transient::class,
        'redis' => Redis::class
    ];

    public function boot()
    {
        $this->app->singleton('cache', function($app) {
            $driverName = $app->config()->get('app.cache.driver');
            $driver = $this->drivers[$driverName];
            return new Cache(new $driver($app));
        });

        CacheFacade::setApp($this->app);
    }
}
