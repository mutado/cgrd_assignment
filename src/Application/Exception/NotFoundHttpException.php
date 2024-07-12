<?php

namespace App\Application\Exception;

class NotFoundHttpException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Route not found', 404);
    }
}