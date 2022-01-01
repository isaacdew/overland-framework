<?php

namespace Overland\Core\Caching;

interface CacheDriverInterface {
    public function get($key);
    public function put($key, $value, $seconds = 0);
    public function has($key);
    public function forget($key);
    public function flush();
}
