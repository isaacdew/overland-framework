<?php

namespace Overland\Core\Facades;

use Overland\Core\Interfaces\Facade;

/**
 * @codeCoverageIgnore
 */
class Cache extends Facade
{
    public static function getFacadeRoot()
    {
        return 'cache';
    }
}
