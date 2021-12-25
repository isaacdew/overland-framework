<?php

use Overland\Core\App;
use Overland\Core\Config;
use Overland\Core\Router\Router;
use Overland\Core\Router\RouteRegistrar;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Overland\Core\Router\RouteRegistrar
 * @uses \Overland\Core\App
 * @uses \Overland\Core\Config
 * @uses \Overland\Core\Interfaces\Collection
 * @uses \Overland\Core\Router\Router
 */
class RouteRegistrarTest extends TestCase
{
    protected $router;

    public function setUp(): void
    {
        $this->router = $this->getMockBuilder(Router::class)
            ->setConstructorArgs([
                new App(new Config([]))
            ])->onlyMethods(['group', 'addRoute'])
            ->getMock();
    }

    public function test_it_creates_group()
    {
        $registrar = new RouteRegistrar($this->router);

        $this->router->expects($this->once())->method('group');

        $registrar->group(function () {
        });
    }

    public function test_it_registers_route()
    {
        $registrar = new RouteRegistrar($this->router);

        $this->router->expects($this->once())->method('addRoute');

        $registrar->registerRoute('GET', '/testing', fn () => 'test');
    }

    /**
     * @dataProvider attributes
     */
    public function test_it_handles_attributes($attribute, $value)
    {
        $registrar = new RouteRegistrar($this->router);
        $registrar->{$attribute}($value);

        $reflection = new ReflectionClass(RouteRegistrar::class);

        $attributes = $reflection->getProperty('attributes')->getValue($registrar);

        $this->assertEquals($value, $attributes[$attribute]);
    }

    public function attributes()
    {
        return [
            'allows middleware attribute' => ['middleware', []],
            'allows name attribute' => ['name', 'test name'],
            'allows prefix attribute' => ['prefix', 'testing'],
        ];
    }

    /**
     * @dataProvider methods
     */
    public function test_it_passes_on_methods_to_router($method)
    {
        $registrar = new RouteRegistrar($this->router);

        $arguments = ['/test', fn() => 'test'];
        $this->router->expects($this->once())->method('addRoute')->with('/test', ['action' => fn() => 'test'], strtoupper($method));

        $registrar->{$method}(...$arguments);
    }

    public function methods()
    {
        return [
            'passes on get method to router' => ['get'],
            'passes on post method to router' => ['post']
        ];
    }

    public function test_it_throws_exeption_for_unknown_attributes()
    {
        $registrar = new RouteRegistrar($this->router);

        $this->expectException(BadMethodCallException::class);

        $registrar->someMethodThatDoesntExist();
    }
}
