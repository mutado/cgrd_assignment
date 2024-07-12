<?php

namespace App\Application\Middleware;

use App\Application\Exception\UnauthorizedHttpException;

class AuthMiddleware implements IMiddleware
{

    public function handle()
    {
        if (!isset($_SESSION['user_id'])) {
            throw new UnauthorizedHttpException();
        }
    }
}