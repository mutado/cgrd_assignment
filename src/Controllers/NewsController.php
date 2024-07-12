<?php

namespace App\Controllers;

use App\Application\Route\Response;

class NewsController
{
    public function store()
    {
        $title = $_POST['title'];
        $content = $_POST['content'];

        $connection = \App\Application\Database\PDOConnection::getInstance()->getConnection();
        $statement = $connection->prepare('INSERT INTO news (title, content) VALUES (:title, :content)');
        $statement->execute(['title' => $title, 'content' => $content]);

        return Response::redirect('/', ['success' => 'News created successfully!']);
    }

    public function update($id)
    {
        $title = $_POST['title'];
        $content = $_POST['content'];

        $connection = \App\Application\Database\PDOConnection::getInstance()->getConnection();
        $statement = $connection->prepare('UPDATE news SET title = :title, content = :content WHERE id = :id');
        $statement->execute(['title' => $title, 'content' => $content, 'id' => $id]);

        return Response::redirect('/', ['success' => 'News updated successfully!']);
    }

    public function destroy($id)
    {
        $connection = \App\Application\Database\PDOConnection::getInstance()->getConnection();
        $statement = $connection->prepare('DELETE FROM news WHERE id = :id');
        $statement->execute(['id' => $id]);

        return Response::redirect('/', ['success' => 'News deleted successfully!']);
    }
}