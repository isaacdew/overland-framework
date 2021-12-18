<?php

namespace Overland\Tests\Unit;

use Overland\Core\Authentication\Auth;
use Overland\Tests\Traits\DatabaseTransactions;
use PHPUnit\Framework\TestCase;

/**
 * @covers Overland\Core\Authentication\Auth
 */
class AuthTest extends TestCase {
    use DatabaseTransactions;
    
    public function test_it_authenticates_a_user() {
        $userId = wp_create_user('username', 'password', 'user@example.com');
        $auth = $this->getMockBuilder(Auth::class)
            ->onlyMethods(['sendCookie', 'generateToken'])
            ->getMock();

        $auth->expects($this->once())->method('generateToken')->with($userId);
        $auth->expects($this->once())->method('sendCookie');

        $auth->authenticate('username', 'password');
    }

    public function test_it_validates_token() {
        $userId = wp_create_user('username2', 'password', 'user2@example.com');
        $auth = new Auth();

        $_COOKIE['overland_jwt_token'] = $auth->generateToken($userId);

        $auth->validateToken();

        $this->assertEquals($userId, get_current_user_id());
    }
}
