<?php

namespace Overland\Core\Authentication;

use Firebase\JWT\JWT;
use Overland\Core\OverlandException;

class Auth
{
    protected $user;

    public function authenticate($username, $password)
    {
        $user = wp_authenticate($username, $password);

        if (is_wp_error($user)) {
            $this->forbiddenResponse();
        }

        $token = $this->generateToken($user->data->ID);

        setcookie('overland_jwt_token', $token, time() + (DAY_IN_SECONDS * 7), '/', '', true, true);
    }

    protected function generateToken($userId)
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
            $this->forbiddenResponse();
        }

        $secretKey = $this->getSecretKey();

        try {
            $token = JWT::decode($token, $secretKey, ['HS256']);
        } catch (\Exception $e) {
            $this->forbiddenResponse();
        }

        if($token->iss != get_bloginfo('url') || !isset($token->data->user->id)) {
            $this->forbiddenResponse();
        }

        wp_set_current_user($token->data->user->id);
    }

    protected function getSecretKey() {
        $secretKey = defined('OVERLAND_APP_KEY') ? OVERLAND_APP_KEY : false;

        if (!$secretKey) {
            throw new OverlandException('App key not defined! This is required for auth.');
        }

        return $secretKey;
    }

    protected function forbiddenResponse() {
        status_header(403);
        exit;
    }
}
