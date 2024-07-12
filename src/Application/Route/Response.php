<?php

namespace App\Application\Route;

class Response
{
    public static function redirect(string $url): Response
    {
        header("Location: $url");
        return new Response();
    }

    public static function view(string $view, array $data = []): Response
    {
        $twig = new \Twig\Environment(new \Twig\Loader\FilesystemLoader(__DIR__ . '/../../../views'));
        $twig->addFunction(new \Twig\TwigFunction('route', function ($name, $parameters = []) {
            return RouteRegister::getInstance()->getNamedRoute($name, $parameters);
        }));
        echo $twig->render($view . '.twig', $data);
        return new Response();
    }

    public function with(string $key, mixed $value): Response
    {
        $_SESSION[$key] = $value;
        return new Response();
    }
}