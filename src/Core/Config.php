<?php

namespace Overland\Core;

class Config {
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function get($key) {
        $keys = explode('.', $key);
        $config = $this->config;
        foreach($keys as $key) {
            $config = $config[$key] ?? null;
        }
        return $config;
    }
}
