<?php

namespace Overland\Tests\Unit\Authentication;

use Overland\Core\App;
use Overland\Core\Authentication\AuthServiceProvider;
use Overland\Core\Config;
use Overland\Core\Facades\Auth;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Overland\Core\Authentication\AuthServiceProvider
 * @uses \Overland\Core\App
 * @uses \Overland\Core\Config
 * @uses \Overland\Core\Authentication\Auth
 * @uses \Overland\Core\Interfaces\Facade
 * @uses \Overland\Core\Interfaces\ServiceProvider
 */
class AuthServiceProviderTest extends TestCase
{
    public function test_it_creates_auth_singleton()
    {
        $app = new App(new Config([]));

        $serviceProvider = new AuthServiceProvider($app);

        $serviceProvider->boot();

        $this->assertInstanceOf(\Overland\Core\Authentication\Auth::class, $app['auth']);

        $this->assertSame($app, Auth::getApp());
    }
}
