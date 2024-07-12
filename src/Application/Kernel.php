<?php

namespace App\Application;

use App\Application\Exception\Handler;
use App\Application\Route\Response;
use App\Application\Route\RouteRegister;

/**
 * The Kernel class is the entry point of the application.
 * It is responsible for loading the routes, handling the request and sending the response.
 */
class Kernel
{
    private array $middlewares = [];

    public static function create()
    {
        return new self();
    }

    public function withMiddlewares(array $array)
    {
        $this->middlewares = $array;

        return $this;
    }

    public function withRoutes($directory)
    {
        require_once $directory;

        return $this;
    }

    public function run(): ?Response
    {
//        $tbp = new \PDO('mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
        try {
            $route = RouteRegister::getInstance()->getRoute($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
            foreach ($route->getMiddlewares() as $middleware) {
                $middlewareInstance = new $this->middlewares[$middleware];
                $middlewareInstance->handle();
            }
            return $route->run();
        } catch (\Exception $e) {
            return Handler::handle($e);
        }
    }

}