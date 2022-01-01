<?php

namespace Overland\Tests\Unit\Caching\Drivers;

use Mockery;
use Overland\Core\App;
use Overland\Core\Caching\Drivers\Redis;
use Overland\Core\Config;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \Overland\Core\Caching\Drivers\Redis
 * @uses \Overland\Core\App
 * @uses \Overland\Core\Config
 * @uses \Overland\Core\Caching\CacheDriver
 */
class RedisTest extends TestCase
{

    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    protected $predis;
    protected $app;
    protected $redisDriver;

    public function setUp(): void
    {
        $this->predis = new \Predis\Client([
            'scheme' => 'tcp',
            'host'   => '127.0.0.1',
            'port'   => 6379
        ]);

        $this->app = new App(
            new Config([
                'app' => [
                    'cache' => [
                        'options' => [
                            'scheme' => 'tcp',
                            'host'   => '127.0.0.1',
                            'port'   => 6379
                        ]
                    ]
                ]
            ])
        );

        $this->redisDriver = new Redis($this->app);
    }

    /**
     * @dataProvider values
     */
    public function test_it_can_save_and_get_values($value)
    {
        $this->redisDriver->put('test_key', $value);

        $this->assertEquals($value, $this->redisDriver->get('test_key'));
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
        $this->redisDriver->put('test_key', 'Testing');

        $this->assertTrue($this->redisDriver->has('test_key'));

        $this->redisDriver->forget('test_key');

        $this->assertFalse($this->redisDriver->has('test_key'));
    }

    public function test_it_calls_expire_if_seconds_are_set()
    {
        $redisDriver = new Redis($this->app);

        $reflection = new ReflectionClass(Redis::class);

        $predisMock = Mockery::mock(\Predis\Client::class);

        $reflection->getProperty('client')->setValue($redisDriver, $predisMock);

        $predisMock->shouldReceive('set')->once();
        $predisMock->shouldReceive('expire')->once()->with('test_key', DAY_IN_SECONDS);

        $redisDriver->put('test_key', 'anything', DAY_IN_SECONDS);
    }

    public function test_it_flushes_cache()
    {
        $this->redisDriver->put('test_key1', 'value');
        $this->redisDriver->put('test_key2', 'value');

        $this->assertTrue($this->redisDriver->has('test_key1'));
        $this->assertTrue($this->redisDriver->has('test_key2'));
        
        $this->redisDriver->flush();
        
        $this->assertFalse($this->redisDriver->has('test_key1'));
        $this->assertFalse($this->redisDriver->has('test_key2'));
    }

    public function tearDown(): void
    {
        $this->predis->flushAll();
    }
}
