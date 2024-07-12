<?php

namespace App\Application\Middleware;

use App\Application\Route\Response;

class GuestMiddleware implements IMiddleware
{

    public function handle()
    {
        if (isset($_SESSION['user_id'])) {
            $connection = \App\Application\Database\PDOConnection::getInstance()->getConnection();
            $statement = $connection->prepare('SELECT * FROM users WHERE id = :id');
            $statement->execute(['id' => $_SESSION['user_id']]);
            $user = $statement->fetch();

            if ($user) {
                return Response::redirect('/');
            }
        }
    }
}