<?php

namespace App\Controllers;

use App\Application\Route\Response;

class HomeController
{
    public function index()
    {
        $connection = \App\Application\Database\PDOConnection::getInstance()->getConnection();
        $statement = $connection->prepare('SELECT * FROM news');
        $statement->execute();

        $news = $statement->fetchAll();
        return Response::view('home', ['news' => $news]);
    }
}