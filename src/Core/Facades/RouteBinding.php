<?php

namespace Overland\Core\Facades;

use Overland\Core\Interfaces\Facade;

/**
 * @codeCoverageIgnore
 */
class RouteBinding extends Facade {
    protected static function getFacadeRoot()
    {
        return 'routeBinding';
    }
}
