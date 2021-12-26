<?php

namespace Overland\Tests\Unit\Helpers;

use Overland\Core\App;
use Overland\Core\Config;
use Overland\Core\Facades\Route;
use Overland\Core\OverlandException;
use Overland\Core\Router\Router;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 * @uses \Overland\Core\App
 * @uses \Overland\Core\Config
 * @uses \Overland\Core\Facades\Route
 * @uses \Overland\Core\Interfaces\Collection
 * @uses \Overland\Core\Interfaces\Facade
 * @uses \Overland\Core\Router\Router
 * @uses \Overland\Core\Router\Route
 * @uses \Overland\Core\Router\RouteCollection
 */
class RouteFunctionTest extends TestCase
{
    public function setUp(): void
    {
        $app = new App(new Config([
            'app' => [
                'basePath' => 'myplugin'
            ]
        ]));

        $app->singleton('router', function($app) {
            return new Router($app);
        });

        Route::setApp($app);
    }

    /**
     * @covers ::overland_route
     */
    public function test_throws_exception_when_route_not_found()
    {
        $this->expectException(OverlandException::class);

        overland_route('route-that-doesnt-exist');
    }

    /**
     * @covers ::overland_route
     */
    public function test_throws_exception_when_params_are_needed_but_not_supplied()
    {
        Route::get('test/{id}', fn() => '')->name('test-route');
        
        $this->expectException(OverlandException::class);
        
        overland_route('test-route');
    }
    
    /**
     * @covers ::overland_route
     */
    public function test_it_generates_correct_value()
    {
        Route::get('test/{id}', fn() => '')->name('test-route');

        $this->assertEquals(get_rest_url(null, 'myplugin/test/1'), overland_route('test-route', ['id' => 1]));
    }
}
