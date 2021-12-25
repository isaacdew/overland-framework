<?php

namespace Overland\Core\Facades;

use Overland\Core\Interfaces\Facade;

/**
 * @codeCoverageIgnore
 */
class Route extends Facade {
    protected static function getFacadeRoot()
    {
        return 'router';
    }
}
