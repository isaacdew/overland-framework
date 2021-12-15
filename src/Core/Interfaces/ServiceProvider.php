<?php

namespace Overland\Core\Interfaces;

use Overland\Core\App;
use Overland\Core\OverlandException;

abstract class ServiceProvider {
    protected App $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function boot() {
        throw new OverlandException(get_called_class() . 'does not implement boot!');
    }
}
