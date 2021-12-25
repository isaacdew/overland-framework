<?php

namespace Overland\Tests\Unit\Router;

use Overland\Core\App;
use Overland\Core\Config;
use Overland\Core\Facades\Route;
use Overland\Core\Router\Router;
use Overland\Core\Router\RouterServiceProvider;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Overland\Core\Router\RouterServiceProvider
 * @uses \Overland\Core\App
 * @uses \Overland\Core\Config
 * @uses \Overland\Core\Interfaces\Collection
 * @uses \Overland\Core\Interfaces\Facade
 * @uses \Overland\Core\Interfaces\ServiceProvider
 * @uses \Overland\Core\Router\Router
 */
class RouterServiceProviderTest extends TestCase
{
    public function test_it_registers_router()
    {
        $app = new App(
            new Config([
                'app' => [
                    'pluginRoot' => OVERLAND_PLUGIN_ROOT
                ]
            ])
        );

        $serviceProvider = new RouterServiceProvider($app);

        $serviceProvider->boot();

        $this->assertInstanceOf(Router::class, $app['router']);

        $this->assertSame($app, Route::getApp());
    }
}
