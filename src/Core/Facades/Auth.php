<?php

namespace Overland\Core\Facades;

use Overland\Core\Interfaces\Facade;

class Auth extends Facade {
    public static function getFacadeRoot() {
        return 'auth';
    }
}
