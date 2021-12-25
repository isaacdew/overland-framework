<?php

namespace Overland\Tests\Unit\Middleware;

use Overland\Core\App;
use Overland\Core\Config;
use Overland\Core\Middleware\Middleware;
use Overland\Core\Middleware\MiddlewareServiceProvider;
use Overland\Core\Router\Router;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Overland\Core\Middleware\MiddlewareServiceProvider
 * @uses \Overland\Core\App
 * @uses \Overland\Core\Config
 * @uses \Overland\Core\Router\Router
 * @uses \Overland\Core\Router\RouteCollection
 * @uses \Overland\Core\Interfaces\Collection
 * @uses \Overland\Core\Interfaces\ServiceProvider
 * @uses \Overland\Core\Middleware\Middleware
 */
class MiddlewareServiceProviderTest extends TestCase
{
    public function test_it_creates_middleware_singleton()
    {
        $app = new App(
            new Config([])
        );

        $app->singleton('router', fn($app) => new Router($app));

        $serviceProvider = new MiddlewareServiceProvider($app);

        $serviceProvider->boot();

        $this->assertInstanceOf(Middleware::class, $app['middleware']);
    }
}
