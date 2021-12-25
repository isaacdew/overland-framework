<?php

namespace Overland\Tests\Unit\Middleware;

use Overland\Core\Interfaces\Middleware as InterfacesMiddleware;
use Overland\Core\Middleware\Middleware;
use Overland\Core\Router\Route;
use Overland\Core\Router\RouteCollection;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use WP_REST_Request;

/**
 * @covers \Overland\Core\Middleware\Middleware
 * @uses \Overland\Core\Interfaces\Collection
 * @uses \Overland\Core\Router\RouteCollection
 * @uses \Overland\Core\Router\Route
 */
class MiddlewareTest extends TestCase
{
    /**
     * @covers \Overland\Core\Middleware\Middleware::guard
     */
    public function test_guard_sets_routes()
    {
        $reflectdClass = new ReflectionClass(Middleware::class);

        $middleware = new Middleware();

        $routes = new RouteCollection([
            new Route('test', 'testing1', ['action' => fn () => 'test'], 'GET'),
            new Route('test', 'testing2', ['action' => fn () => 'test'], 'POST'),
            new Route('test', 'testing3', ['action' => fn () => 'test'], 'GET'),
        ]);

        $middleware->guard($routes);

        $this->assertSame($routes, $reflectdClass->getProperty('routes')->getValue($middleware));
    }

    /**
     * @covers \Overland\Core\Middleware\Middleware::__construct
     * @covers \Overland\Core\Middleware\Middleware::guard
     * @covers \Overland\Core\Middleware\Middleware::filterRequest
     * @covers \Overland\Core\Middleware\Middleware::routeMatch
     * @covers \Overland\Core\Middleware\Middleware::getMatchedRoute
     */
    public function test_it_can_match_route()
    {

        $middleware = new Middleware([
            FakeMiddleware::class
        ]);

        $routeMatch = new Route('test', 'testing3', ['action' => fn () => 'test'], 'GET');
        
        $routes = new RouteCollection([
            new Route('test', 'testing1', ['action' => fn () => 'test'], 'GET'),
            new Route('test', 'testing2', ['action' => fn () => 'test'], 'POST'),
            $routeMatch
        ]);

        
        $middleware->guard($routes);
        
        $request = new WP_REST_Request('GET', '/test/testing3');
    
        $middleware->filterRequest(null, null, $request);

        $this->assertSame($routeMatch, $middleware->getMatchedRoute());
    }
}

class FakeMiddleware implements InterfacesMiddleware
{
    public function handle($request)
    {
    }
}
