<?php

namespace Overland\Tests\Unit\Router;

use Overland\Core\Router\Route;
use Overland\Core\Router\RouteCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers Overland\Core\RouteCollection
 */
class RouteCollectionTest extends TestCase {

    public function test_find_route_by_name() {
        $route1 = new Route('test', 'testing', ['action' => fn() => null], 'GET');
        $route1->name('testRoute');
        $route2 = new Route('test', 'testing2', ['action' => fn() => null], 'GET');
        $route2->name('testRoute2');
        $routes = new RouteCollection([
            $route1,
            $route2
        ]);

        $this->assertEquals($route2, $routes->findByName('testRoute2'));
    }

    public function test_find_route() {
        $route1 = new Route('test', 'testing', ['action' => fn() => null], 'GET');
        $route2 = new Route('test', 'testing2', ['action' => fn() => null], 'GET');
        $routes = new RouteCollection([
            $route1,
            $route2
        ]);

        $this->assertEquals($route1, $routes->findRoute('/test/testing', 'GET'));
    }

    public function test_where_has_middleware() {
        $route1 = new Route('test', 'testing', ['action' => ['action' => fn() => null]], 'GET');
        $route2 = new Route('test', 'testing2', ['action' => fn() => null], 'GET');
        $route2->middleware(['auth']);
        $routes = new RouteCollection([
            $route1,
            $route2
        ]);

        $whereHasMiddleware = $routes->whereHasMiddleware();

        $this->assertContains($route2, $whereHasMiddleware);
        $this->assertNotContains($route1, $whereHasMiddleware);
    }
}