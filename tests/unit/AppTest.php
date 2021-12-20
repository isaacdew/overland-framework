<?php

namespace Overland\Tests\Unit;

use Overland\Core\App;
use Overland\Core\Config;
use Overland\Core\Interfaces\ServiceProvider;
use Overland\Core\OverlandException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Overland\Core\App
 * @uses \Overland\Core\Config
 * @uses \Overland\Core\OverlandException
 * @uses \Overland\Core\Interfaces\ServiceProvider
 */
class AppTest extends TestCase
{

    protected App $app;

    public function setUp(): void
    {
        $this->app = new App(new Config([]));
    }

    public function test_it_can_create_singleton()
    {
        $callback = fn () =>  10 + 10;

        $this->app->singleton('mySingleton', $callback);

        $this->assertEquals($callback(), $this->app['mySingleton']);
    }

    public function test_it_can_register_and_boot_service_providers()
    {

        $this->app->register(FakeServiceProvider::class);
        /**
         * If the App boot method calls the boot on our service provider, 
         * we should expect an exception stating the boot method has not been implemented
         */
        $this->expectException(OverlandException::class);

        $this->app->boot();
    }
}

// Create a FakeServiceProvider so we can expect an exception
class FakeServiceProvider extends ServiceProvider {}
