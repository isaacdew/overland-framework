<?php

namespace Overland\Tests\Unit\Caching;

use InvalidArgumentException;
use Overland\Core\Caching\Cache;
use Overland\Core\Caching\CacheDriver;
use PHPUnit\Framework\TestCase;
use \Mockery;

/**
 * @covers \Overland\Core\Caching\Cache
 */
class CacheTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function test_it_can_remember()
    {
        $driverMock = Mockery::mock(CacheDriver::class);
        $driverMock->shouldReceive('has')->once()->with('key')->andReturn(false);
        $driverMock->shouldReceive('put')->once()->with('key', 'test', DAY_IN_SECONDS);
        
        $cache = new Cache($driverMock);
        
        $value1 = $cache->remember('key', DAY_IN_SECONDS, function() {
            return 'test';
        });
        $driverMock->shouldReceive('has')->once()->with('key')->andReturn(true);
        $driverMock->shouldReceive('get')->once()->with('key')->andReturn('test');

        $value2 = $cache->remember('key', DAY_IN_SECONDS, function() {
            return 'test';
        });

        $this->assertEquals($value1, $value2);
    }

    /**
     * @dataProvider driverMethods
     */
    public function test_it_can_passthru_methods_to_driver($method, $arguments)
    {
        $driverMock = Mockery::mock(CacheDriver::class);
        $driverMock->shouldReceive($method)->once()->with(...$arguments);

        $cache = new Cache($driverMock);

        $cache->{$method}(...$arguments);
    }

    public function driverMethods()
    {   
        return [
            ['put', ['key', 'value']],
            ['get', ['key']],
            ['has', ['key']],
            ['forget', ['key']],
            ['flush', []]
        ];
    }

    public function test_it_throws_exception_when_an_invalid_method_is_called()
    {
        $driverMock = Mockery::mock(CacheDriver::class);

        $cache = new Cache($driverMock);

        $this->expectException(InvalidArgumentException::class);

        $cache->invalidMethod();
    }
}
