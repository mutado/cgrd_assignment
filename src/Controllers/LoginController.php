<?php

namespace App\Controllers;

use App\Application\Route\Response;

class LoginController
{
    public function store()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $connection = \App\Application\Database\PDOConnection::getInstance()->getConnection();
        $statement = $connection->prepare('SELECT * FROM users WHERE username = :username');
        $statement->execute(['username' => $username]);
        $user = $statement->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            header('Location: /');
        } else {
            return Response::redirect('/auth/login', ['error' => 'Wrong Login Data!']);
        }
    }

    public function destroy()
    {
        unset($_SESSION['user_id']);
        return "Logged out successfully!";
    }
}