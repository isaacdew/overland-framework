<?php

namespace Overland\Tests\Unit\Caching;

use Mockery;
use Overland\Core\App;
use Overland\Core\Caching\Cache;
use Overland\Core\Caching\CacheDriverInterface;
use Overland\Core\Caching\CacheServiceProvider;
use Overland\Core\Caching\Drivers\Transient;
use Overland\Core\Config;
use Overland\Core\Facades\Cache as FacadesCache;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionObject;

/**
 * @covers \Overland\Core\Caching\CacheServiceProvider
 * @uses \Overland\Core\App
 * @uses \Overland\Core\Config
 * @uses \Overland\Core\Caching\Cache
 * @uses \Overland\Core\Interfaces\Facade
 * @uses \Overland\Core\Interfaces\ServiceProvider::__construct
 */
class CacheServiceProviderTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function test_it_registers_cache()
    {
        $driverMock = Mockery::mock('overload:FakeDriver', CacheDriverInterface::class);
        $app = new App(
            new Config([
                'app' => [
                    'cache' => [
                        'driver' => 'FakeDriver'
                    ]
                ]
            ])
        );

        $driverMock->shouldReceive('__construct')->with($app);

        $serviceProvider = new CacheServiceProvider($app);

        $serviceProvider->boot();

        $this->assertInstanceOf(Cache::class, $app['cache']);

        $this->assertSame($app, FacadesCache::getApp());
    }
}
