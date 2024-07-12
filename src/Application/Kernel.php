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

    private function handleRequest()
    {
        try {
            $method = $this->getRequestMethod();
            $route = RouteRegister::getInstance()->getRoute($method, $_SERVER['REQUEST_URI']);
            foreach ($route->getMiddlewares() as $middleware) {
                $middlewareInstance = new $this->middlewares[$middleware];
                $middlewareInstance->handle();
            }
            return $route->run();
        } catch (\Exception $e) {
            return Handler::handle($e);
        }
    }

    public function run(): ?Response
    {
        $response = $this->handleRequest();
        $this->cleanFlash();
        if (is_string($response)) {
            echo $response;
            return null;
        } else {
            return $response;
        }

    }

    private function cleanFlash()
    {
        if (isset($_SESSION['flash']))
            foreach ($_SESSION['flash'] as $key => $value) {
                if (!in_array($key, $_SESSION['new_flash'] ?? [])) {
                    unset($_SESSION['flash'][$key]);
                }
            }
        unset($_SESSION['new_flash']);
    }

    /**
     * Get hidden request method or http method.
     * @return void
     */
    private function getRequestMethod()
    {
        return $_POST['_method'] ?? $_GET['_method'] ?? $_SERVER['REQUEST_METHOD'];
    }

}