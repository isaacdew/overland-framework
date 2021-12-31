<?php

namespace Overland\Core\Caching\Drivers;

use Overland\Core\Caching\CacheDriver;

class Transient extends CacheDriver
{
    public function get($key)
    {
        return get_transient($key);
    }

    public function put($key, $value, $seconds = 0)
    {
        return set_transient($key, $value, $seconds);
    }

    public function forget($key)
    {
        return delete_transient($key);
    }

    public function has($key)
    {
        global $wpdb;

        return $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}options WHERE option_name = '_transient_timeout_{$key}' AND option_value > NOW()");
    }
}
