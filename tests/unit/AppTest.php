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
        $this->app->boot();

        $this->assertEquals('fired service provider', $this->app['fakeServiceProvider']);
    }

    public function test_it_implements_array_access() {
        $this->app['test'] = 'foo';

        $this->assertEquals('foo', $this->app['test']);

        unset($this->app['test']);

        $this->assertTrue(!isset($this->app['test']));
    }

    public function test_it_can_bind() {
        $closure = fn() => 'test bind';

        $this->app->bind('testBinding', $closure);

        $this->assertEquals('test bind', $this->app['testBinding']);
    }

    public function test_it_returns_config() {
        $config = new Config([]);

        $app = new App($config);

        $this->assertSame($config, $app->config());
    }
}

// Create a FakeServiceProvider so we can expect an exception
class FakeServiceProvider extends ServiceProvider {
    public function boot() {
        $this->app->singleton('fakeServiceProvider', function() {
            return 'fired service provider';
        });
    }
}
