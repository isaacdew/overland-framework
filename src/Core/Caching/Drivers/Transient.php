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
        return (bool) get_transient($key);
    }

    public function flush()
    {
        // Need to clear object cache
        wp_cache_flush();

        // Then clear transients from database
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE `option_name` LIKE ('%\_transient\_%')");
    }
}
