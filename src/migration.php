<?php

include __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

use App\Application\Database\PDOConnection;

$connection = PDOConnection::getInstance()->getConnection();

$connection->exec('DROP TABLE IF EXISTS news');
$connection->exec('DROP TABLE IF EXISTS users');

$connection->exec('CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
)');

$connection->exec('CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL
)');

$connection->prepare(
    'INSERT INTO users (username, password) VALUES (:username, :password)',
)->execute([
    'username' => 'admin',
    'password' => password_hash('admin', PASSWORD_DEFAULT),
]);

foreach ([
             ['title' => 'First news', 'content' => 'This is the first news',],
             ['title' => 'Second news', 'content' => 'This is the second news',],
             ['title' => 'Third news', 'content' => 'This is the third news',],
         ] as $news) {
    $connection->prepare(
        'INSERT INTO news (title, content) VALUES (:title, :content)',
    )->execute($news);
}