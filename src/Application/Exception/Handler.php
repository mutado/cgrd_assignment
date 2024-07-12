<?php

namespace App\Application\Exception;

use App\Application\Route\Response;

class Handler
{
    public static function handle(\Exception $exception): ?Response
    {
        if ($exception instanceof UnauthorizedHttpException) {
            return Response::redirect('/auth/login');
        }
        print_r($exception->getMessage() . PHP_EOL);
        return null;
    }
}