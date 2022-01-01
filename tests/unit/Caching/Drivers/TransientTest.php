<?php

namespace Overland\Tests\Unit\Caching\Drivers;

use Overland\Core\App;
use Overland\Core\Caching\Drivers\Transient;
use Overland\Core\Config;
use Overland\Tests\Traits\DatabaseTransactions;
use PHPUnit\Framework\TestCase;


/**
 * @covers \Overland\Core\Caching\Drivers\Transient
 * @uses \Overland\Core\App
 * @uses \Overland\Core\Config
 * @uses \Overland\Core\Caching\CacheDriver
 */
class TransientTest extends TestCase
{
    use DatabaseTransactions;

    protected $transientDriver;

    public function setUp(): void
    {
        $app = new App(
            new Config([])
        );

        $this->transientDriver = new Transient($app);
    }

    /**
     * @dataProvider values
     */
    public function test_it_can_save_and_get_values($value)
    {
        $this->transientDriver->put('test_key', $value);

        $this->assertEquals($value, $this->transientDriver->get('test_key'));

        delete_transient('test_key');
    }

    public function values()
    {
        return [
            'number' => [mt_rand()],
            'array' => [['test' => 'test']],
            'object' => [new \stdClass()],
            'string' => ['string']
        ];
    }

    public function test_it_can_forget_values()
    {
        $this->transientDriver->put('test_key', 'Testing');

        $this->assertTrue($this->transientDriver->has('test_key'));

        $this->transientDriver->forget('test_key');

        $this->assertFalse($this->transientDriver->has('test_key'));
    }
}
