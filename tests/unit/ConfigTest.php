<?php

use Overland\Core\Config;
use PHPUnit\Framework\TestCase;

/**
 * @covers Overland\Core\Config
 */
class ConfigTest extends TestCase
{
    public function test_can_find_value_by_dot_key()
    {
        $config = new Config([
            'app' => [
                'test' => 'passed'
            ]
        ]);

        $this->assertEquals('passed', $config->get('app.test'));
    }

    public function test_undefined_offset_returns_null()
    {
        $config = new Config([]);

        $this->assertNull($config->get('app.test'));
    }
}
