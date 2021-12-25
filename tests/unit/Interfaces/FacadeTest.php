<?php

namespace Overland\Tests\Unit\Interfaces;

use Overland\Core\App;
use Overland\Core\Config;
use Overland\Core\Interfaces\Facade;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \Overland\Core\Interfaces\Facade
 * @covers \Overland\Core\Facades\Auth
 * @uses \Overland\Core\App
 * @uses \Overland\Core\Config
 */
class FacadeTest extends TestCase
{
    protected $app;

    public function setUp(): void
    {
        $this->app = new App(
            new Config([])
        );

        $this->app->singleton('fake', function ($app) {
            return $this->getMockBuilder(Fake::class)
                ->onlyMethods(['test'])
                ->getMock();
        });

        FakeFacade::setApp($this->app);
    }

    public function test_it_passes_on_method_calls()
    {
        $this->app['fake']->expects($this->once())->method('test');

        FakeFacade::test();
    }

    public function test_it_gets_app()
    {
        $this->assertSame($this->app, FakeFacade::getApp());
    }

    public function test_it_stores_resolved_instance()
    {
        $reflection = new ReflectionClass(FakeFacade::class);
        
        // Clear instances from previous tests
        $reflection->getProperty('resolvedInstance')->setValue(null);

        // Run to set instance
        FakeFacade::test();

        $resolveInstance = $reflection->getProperty('resolvedInstance')->getValue();

        $this->assertSame($this->app['fake'], $resolveInstance['fake']);

        $this->app['fake']->expects($this->once())->method('test');

        // Here we call it again since this time it'll use the resolved instance
        FakeFacade::test();
    }
}

class FakeFacade extends Facade
{
    protected static function getFacadeRoot()
    {
        return 'fake';
    }
}

class Fake
{
    public function test()
    {
    }
}
