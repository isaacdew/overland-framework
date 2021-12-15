<?php

namespace Overland\Core\Authentication;

use Overland\Core\Facades\Auth;
use Overland\Core\Interfaces\Middleware;

class AuthMiddleware implements Middleware
{
    public function handle($request) {
        // Check auth and return 403 if auth fails
        Auth::validateToken();
    }
}

