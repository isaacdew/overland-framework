<?php

namespace Overland\Tests\Unit\Router;

use Overland\Core\App;
use Overland\Core\Config;
use Overland\Core\Router\Route;
use Overland\Core\Router\RouteCollection;
use Overland\Core\Router\Router;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \Overland\Core\Router\Router
 * @uses \Overland\Core\Router\Route
 * @uses \Overland\Core\Router\RouteCollection
 * @uses \Overland\Core\Router\RouteRegistrar
 * @uses \Overland\Core\Interfaces\Collection
 * @uses \Overland\Core\App
 * @uses \Overland\Core\Config
 */
class RouterTest extends TestCase
{
    public function setUp(): void
    {
        $this->app = new App(
            new Config([
                'app' => [
                    'basePath' => '/test/'
                ]
            ])
        );
    }

    /**
     * @covers \Overland\Core\Router\Router::getRoutes
     * @covers \Overland\Core\Router\Router::addRoute
     * @dataProvider methods
     */
    public function test_it_creates_routes($method)
    {
        $router = new Router($this->app);

        $router->addRoute('/testing', fn () => 'test', $method);

        $routes = $router->getRoutes();

        $this->assertCount(1, $routes);
        $this->assertContainsOnlyInstancesOf(Route::class, $routes);
        $this->assertEquals($method, $routes[0]->method());
    }

    public function test_it_can_group_routes()
    {
        $router = new Router($this->app);
        $callback = fn () => 'test';

        $router->prefix('/group')->group(function ($router) use ($callback) {
            $router->get('/1', $callback);
            $router->post('/2', $callback);
        });

        $router->get('/3', $callback);

        $routes = $router->getRoutes();

        $this->assertStringStartsWith('group', $routes[0]->path());
        $this->assertStringStartsWith('group', $routes[1]->path());
        $this->assertStringStartsNotWith('group', $routes[2]->path());
    }
    
    /**
     * @covers \Overland\Core\Router\Router::__construct
     * @covers \Overland\Core\Router\Router::addRoute
     * @covers \Overland\Core\Router\Router::registerRoutes
     * @covers \Overland\Core\Router\Router::initAPI
     */
    public function test_it_registers_routes()
    {
        $router = new Router($this->app);
        
        $router->addRoute('/testing', fn () => 'test', 'GET');
        
        remove_all_actions('rest_api_init');
        $router->registerRoutes();

        $this->assertTrue(has_action('rest_api_init'));
    }

    public function test_api_init_callback() {
        $reflectedClass = new ReflectionClass(Router::class);
        $initAPI = $reflectedClass->getMethod('initAPI');
        $initAPI->setAccessible(true);

        $route = $this->getMockBuilder(Route::class)
            ->setConstructorArgs([
                'basepath',
                'test',
                ['uses' => fn() => 'test'],
                'POST'
            ])
            ->onlyMethods(['register'])
            ->getMock();

        $route->expects($this->once())->method('register');
        
        $router = new Router($this->app, new RouteCollection([$route]));

        $initAPI->invokeArgs($router, []);
    }

    public function methods()
    {
        return [
            ['GET'],
            ['POST']
        ];
    }
}
