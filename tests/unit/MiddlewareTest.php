<?php

use Overland\Core\Interfaces\Middleware as InterfacesMiddleware;
use Overland\Core\Middleware\Middleware;
use Overland\Core\Router\Route;
use Overland\Core\Router\RouteCollection;
use PHPUnit\Framework\TestCase;

/**
 * @covers Overland\Core\Middleware\Middleware
 */
class MiddlewareTest extends TestCase
{
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
