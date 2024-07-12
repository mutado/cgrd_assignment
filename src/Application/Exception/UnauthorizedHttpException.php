<?php

namespace App\Application\Exception;

class UnauthorizedHttpException extends \Exception
{

    public function __construct()
    {
        parent::__construct('Unauthorized', 401);
    }
}