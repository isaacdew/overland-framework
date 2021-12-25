<?php

namespace Overland\Core\Authentication;

use Firebase\JWT\JWT;
use Overland\Core\App;
use Overland\Core\OverlandException;
use Overland\Core\Response;

class Auth
{
    protected $user;

    protected $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function authenticate($username, $password)
    {
        $user = wp_authenticate($username, $password);

        if (is_wp_error($user)) {
            return $this->forbiddenResponse();
        }

        $token = $this->generateToken($user->data->ID);

        $this->sendCookie($token);
    }

    /**
     * @codeCoverageIgnore
     */
    protected function sendCookie($token) {
        setcookie('overland_jwt_token', $token, time() + (DAY_IN_SECONDS * 7), '/', '', true, true);
    } 

    public function generateToken($userId)
    {
        $secretKey = $this->getSecretKey();

        $time = time();
        $expiration = $time + (DAY_IN_SECONDS * 7);

        return JWT::encode([
            'iss' => get_bloginfo('url'),
            'iat' => $time,
            'nbf' => $time,
            'exp' => $expiration,
            'data' => [
                'user' => [
                    'id' => $userId
                ]
            ]
        ], $secretKey);
    }

    public function validateToken()
    {
        $token = $_COOKIE['overland_jwt_token'] ?? false;

        if (!$token) {
            return $this->forbiddenResponse();
        }

        $secretKey = $this->getSecretKey();

        try {
            $token = JWT::decode($token, $secretKey, ['HS256']);
        } catch (\Exception $e) {
            return $this->forbiddenResponse();
        }

        if($token->iss != get_bloginfo('url') || !isset($token->data->user->id)) {
            return $this->forbiddenResponse();
        }

        wp_set_current_user($token->data->user->id);
    }

    protected function getSecretKey() {
        $secretKey =  $this->app->config()->get('app.secretKey');

        if (!$secretKey) {
            throw new OverlandException('App key not defined! This is required for auth.');
        }

        return $secretKey;
    }

    protected function forbiddenResponse() {
        return Response::create()->status(403);
    }
}
