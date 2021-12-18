<?php

namespace Overland\Core\Interfaces;

use Overland\Core\App;
use Overland\Core\OverlandException;

abstract class Facade {

    protected static App $app;

    protected static $resolvedInstance;

    public static function __callStatic($name, $arguments)
    {
        $root = static::getFacadeRoot();

        return static::getResolvedInstance($root)->$name(...$arguments);
    }

    public static function setApp(App $app) {
        static::$app = $app;
    }

    protected static function getResolvedInstance($name) {
        if(isset(static::$resolvedInstance[$name])) {
            return static::$resolvedInstance[$name];
        }
        return static::$resolvedInstance[$name] = static::$app[$name];
    }

    protected static function getFacadeRoot() {
        throw new OverlandException(get_called_class() . 'does not implement getFacadeRoot!');
    }
}
