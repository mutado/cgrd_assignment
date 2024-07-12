<?php

namespace App\Application\Exception;

class InvalidMethodHttpException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Method not allowed', 405);
    }
}