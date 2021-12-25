<?php

namespace Overland\Tests\Unit\Authentication;

use Overland\Core\App;
use Overland\Core\Authentication\AuthMiddleware;
use Overland\Core\Config;
use Overland\Core\Facades\Auth;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Overland\Core\Authentication\AuthMiddleware
 * @uses \Overland\Core\App
 * @uses \Overland\Core\Authentication\Auth
 * @uses \Overland\Core\Config
 * @uses \Overland\Core\Facades\Auth
 * @uses \Overland\Core\Interfaces\Facade
 */
class AuthMiddlewareTest extends TestCase
{
    public function test_it_validates_token()
    {
        $app = new App(
            new Config([])
        );

        $app->singleton('auth', function ($app) {
            return $this->getMockBuilder(\Overland\Core\Authentication\Auth::class)
                ->setConstructorArgs([$app])
                ->onlyMethods(['validateToken'])
                ->getMock();
        });

        Auth::setApp($app);

        $app['auth']->expects($this->once())->method('validateToken');

        $authMiddleware = new AuthMiddleware();

        $authMiddleware->handle([]);
    }
}
