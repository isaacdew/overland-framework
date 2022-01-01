<?php

namespace Overland\Core\Caching\Drivers;

use Overland\Core\Caching\CacheDriver;
use \Predis\Client;

class Redis extends CacheDriver
{
    protected $client;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->client = new Client(
            $app->config()->get('app.cache.options')
        );
    }

    public function get($key)
    {
        return unserialize($this->client->get($key));
    }

    public function has($key)
    {
        return (bool) $this->client->exists($key);
    }

    public function put($key, $value, $seconds = 0)
    {
        $this->client->set($key, serialize($value));

        if($seconds) {
            $this->client->expire($key, $seconds);
        }
    }

    public function forget($key)
    {
        return $this->client->del($key);
    }

    public function flush()
    {
        return $this->client->flushall();
    }
}
