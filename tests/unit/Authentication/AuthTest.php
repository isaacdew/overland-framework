<?php

namespace Overland\Tests\Unit\Authentication;

use Overland\Core\App;
use Overland\Core\Authentication\Auth;
use Overland\Core\Config;
use Overland\Core\OverlandException;
use Overland\Core\Response;
use Overland\Tests\Traits\DatabaseTransactions;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Overland\Core\Authentication\Auth
 * @uses \Overland\Core\App
 * @uses \Overland\Core\Config
 * @uses \Overland\Core\Response
 */
class AuthTest extends TestCase
{
    use DatabaseTransactions;

    protected $auth;

    public function setUp(): void
    {
        $this->auth = new Auth(
            new App(
                new Config([
                    'app' => [
                        'secretKey' => 'testingKey'
                    ]
                ])
            )
        );
    }

    /**
     * @covers \Overland\Core\Authentication\Auth::sendCookie
     * @covers \Overland\Core\Authentication\Auth::generateToken
     * @covers \Overland\Core\Authentication\Auth::authenticate
     */
    public function test_it_authenticates_a_user()
    {
        $userId = wp_create_user('username', 'password', 'user@example.com');
        $auth = $this->getMockBuilder(Auth::class)
            ->setConstructorArgs([
                new App(new Config([]))
            ])
            ->onlyMethods(['sendCookie', 'generateToken'])
            ->getMock();

        $auth->expects($this->once())->method('generateToken')->with($userId);
        $auth->expects($this->once())->method('sendCookie');

        $auth->authenticate('username', 'password');
    }

    public function test_it_returns_forbidden_when_auth_fails()
    {
        $auth = $this->getMockBuilder(Auth::class)
            ->setConstructorArgs([
                new App(new Config([]))
            ])
            ->onlyMethods(['sendCookie', 'generateToken'])
            ->getMock();

        $this->assertInstanceOf(Response::class, $auth->authenticate('invalidusername', 'wrongPassword')->test());

        $this->assertEquals(403, http_response_code());
    }

    public function test_it_validates_token()
    {
        $userId = wp_create_user('username2', 'password', 'user2@example.com');

        $_COOKIE['overland_jwt_token'] = $this->auth->generateToken($userId);

        $this->auth->validateToken();

        $this->assertEquals($userId, get_current_user_id());
    }

    public function test_token_validation_fails_if_token_is_empty()
    {
        unset($_COOKIE['overland_jwt_token']);

        $this->assertInstanceOf(Response::class, $this->auth->validateToken()->test());

        $this->assertEquals(403, http_response_code());
    }


    public function test_token_validation_fails_if_decode_throws_exception()
    {
        $userId = wp_create_user('username3', 'password', 'user5@example.com');

        $_COOKIE['overland_jwt_token'] = $this->auth->generateToken($userId);

        // Create new auth instance to change the secretKey
        $auth2 = new Auth(
            new App(
                new Config([
                    'app' => [
                        'secretKey' => 'KeyTwo'
                    ]
                ])
            )
        );


        $this->assertInstanceOf(Response::class, $auth2->validateToken()->test());

        $this->assertEquals(403, http_response_code());
    }

    public function test_it_returns_forbidden_when_user_id_is_not_set()
    {

        $_COOKIE['overland_jwt_token'] = $this->auth->generateToken(null);

        $this->assertInstanceOf(Response::class, $this->auth->validateToken()->test());

        $this->assertEquals(403, http_response_code());
    }

    public function test_token_generation_fails_if_secret_is_empty()
    {
        $userId = wp_create_user('username4', 'password', 'user6@example.com');
        $auth = new Auth(new App(
            new Config([
                'app' => [
                    'secretKey' => null
                ]
            ])
        ));

        $this->expectException(OverlandException::class);

        $_COOKIE['overland_jwt_token'] = $auth->generateToken($userId);
    }
}
