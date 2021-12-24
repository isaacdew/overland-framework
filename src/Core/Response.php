<?php

namespace Overland\Core;

class Response
{
    protected $body;

    protected bool $testing = false;

    public function __construct($body = null)
    {
        $this->body = $body;
    }

    public static function create($body = null)
    {
        return new static($body);
    }

    public function status($status)
    {
        http_response_code($status);

        return $this;
    }

    public function json()
    {
        $this->body = json_encode($this->body);

        return $this;
    }

    public function test() {
        $this->testing = true;

        return $this;
    }

    public function __destruct()
    {
        echo $this->body;

        // @codeCoverageIgnoreStart
        if(!$this->testing) {
            exit;
        }
        // @codeCoverageIgnoreEnd
    }
}
