<?php

namespace Overland\Tests\Unit\Router;

use Overland\Core\Router\Route;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \Overland\Core\Router\Route
 * @uses \Overland\Core\Interfaces\Collection::rewind
 * @uses \Overland\Core\Interfaces\Collection::valid
 * @uses \Overland\Core\Router\Router::initAPI
 */
class RouteTest extends TestCase {
    public function test_it_registers_itself() {
        $path = 'test9';

        $route = new Route('testing', $path, ['uses' => fn() => 'test'], 'GET');

        add_action('rest_api_init', function() use ($route) {
            $route->register();
        });

        $rest = rest_get_server();

        $registeredRoutes = $rest->get_routes('testing');

        $this->assertTrue(isset($registeredRoutes[$route->getFullPath()]));
    }

    public function test_can_set_prefix() {
        $route = new Route('testing', 'test', ['uses' => fn() => 'test'], 'POST');

        $route->prefix('prefix');

        $this->assertEquals('/testing/prefix/test', $route->getFullPath());
    }

    /**
     * @dataProvider actions
     */
    public function test_get_action_callback_handles_string($action) {
        $reflectedClass = new ReflectionClass(Route::class);

        $getActionCallback = $reflectedClass->getMethod('getActionCallback');
        $getActionCallback->setAccessible(true);

        $route = new Route('testing', 'test', ['action' => $action], 'POST');
        $action = $getActionCallback->invoke($route);

        if(is_array($action)) {
            $value = $action[0]->{$action[1]}();
        }

        $value = $action();

        $this->assertEquals('callback works!', $value);
    }

    public function actions() {
        return [
            'action is a class method' => ['Overland\Tests\Unit\Router\FakeController@fake'],
            'action is a closure' => [fn() => 'callback works!'],
            'action is an array' => [[FakeController::class, 'fake']]
        ];
    }
}

class FakeController {
    public function fake() {
        return 'callback works!';
    }
}
