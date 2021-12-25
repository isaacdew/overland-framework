<?php

namespace Overland\Tests\Unit\Interfaces;

use Overland\Core\App;
use Overland\Core\Config;
use Overland\Core\Interfaces\ServiceProvider;
use Overland\Core\OverlandException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \Overland\Core\Interfaces\ServiceProvider
 * @uses \Overland\Core\App
 * @uses \Overland\Core\Config
 */
class ServiceProviderTest extends TestCase
{
    public function test_it_sets_app()
    {
        $app = new App(new Config([]));
        $serviceProvider = new FakeServiceProvider($app);

        $reflection = new ReflectionClass(FakeServiceProvider::class);

        $property = $reflection->getProperty('app')->getValue($serviceProvider);

        $this->assertSame($app, $property);
    }

    public function test_it_throws_exception_when_boot_is_not_implemented()
    {
        $app = new App(new Config([]));
        $serviceProvider = new FakeServiceProvider($app);

        $this->expectException(OverlandException::class);

        $serviceProvider->boot();
    }
}

class FakeServiceProvider extends ServiceProvider
{
}
