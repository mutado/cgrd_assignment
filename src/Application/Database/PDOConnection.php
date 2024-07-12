<?php

namespace App\Application\Database;

class PDOConnection
{
    private static ?PDOConnection $instance = null;
    private \PDO $connection;

    private function __construct()
    {
        $this->connection = new \PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    }

    public static function getInstance(): PDOConnection
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }
}