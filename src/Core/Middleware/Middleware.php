<?php

namespace Overland\Core\Middleware;

use Overland\Core\Router\Route;
use Overland\Core\Router\RouteCollection;
use WP_REST_Request;

class Middleware {
    protected WP_REST_Request $request;

    protected RouteCollection $routes;

    protected ?Route $matchedRoute = null;

    protected $middleware = [];

    public function __construct($middleware = null)
    {
        $this->middleware = $middleware ?? [];
        add_filter( 'rest_pre_dispatch', [$this, 'filterRequest'], 0, 3);
    }

    public function guard(RouteCollection $routes) {
        $this->routes = $routes;
        return $this;
    }


    public function filterRequest($result, $server, $request) {
        $this->request = $request;
        $this->matchedRoute = $this->routeMatch();
        if($this->matchedRoute) {
            foreach($this->matchedRoute->middleware() as $middleware) {
                // @codeCoverageIgnoreStart
                (new $this->middleware[$middleware])->handle($request);
                // @codeCoverageIgnoreEnd
            }
        }
    }

    public function getMatchedRoute() {
        return $this->matchedRoute;
    }

    protected function routeMatch() {
        $route = $this->request->get_route();
        return $this->routes->findRoute($route, $_SERVER['REQUEST_METHOD']);
    }
}

