<?php

namespace Overland\Core\Facades;

use Overland\Core\Interfaces\Facade;

/**
 * @codeCoverageIgnore
 */
class Auth extends Facade {
    public static function getFacadeRoot() {
        return 'auth';
    }
}
