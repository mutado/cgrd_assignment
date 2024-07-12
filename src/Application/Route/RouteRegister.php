<?php

namespace App\Application\Route;

use App\Application\Exception\InvalidMethodHttpException;
use App\Application\Exception\NotFoundHttpException;

/**
 * The RouteRegister class is responsible for registering the routes.
 */
class RouteRegister
{
    /**
     * The instance of the RouteRegister class.
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * The routes array.
     * @var array<string, array<string, Route>>
     */
    private array $routes = [];

    /**
     * Get the instance of the RouteRegister class.
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Register a route.
     * @param string $method
     * @param string $uri
     * @param Route $route
     * @return Route
     */
    public function register(string $method, string $uri, Route $route): Route
    {
        $this->routes[$uri][$method] = $route;
        return $route;
    }

    /**
     * Get route matching the method and uri.
     * @param string $method
     * @param string $requestUri
     * @return Route
     */
    public function getRoute(string $requestMethod, string $requestUri): Route
    {
        if ($requestUri === '')
            $requestUri = '/';
        if ($requestUri !== '/')
            $requestUri = rtrim($requestUri, '/');
        $requestUri = explode('?', $requestUri)[0];
        $foundUri = false;

        foreach ($this->routes as $template => $routes) {
            // Convert template to regex
            $pattern = preg_replace('/\{[a-zA-Z_][a-zA-Z0-9_]*\}/', '([^/]+)', $template);
            $pattern = str_replace('/', '\/', $pattern);
            $pattern = '/^' . $pattern . '$/';

            // Match the route with the pattern
            if (preg_match($pattern, $requestUri, $matches)) {
                $foundUri = true;
                array_shift($matches); // Remove the full match
                foreach ($routes as $method => $route) {
                    if ($method !== $requestMethod) {
                        continue;
                    }

                    $variables = [];

                    // Extract variable names from the template
                    preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $template, $variableNames);
                    $variableNames = $variableNames[1];

                    // Combine variable names with their values
                    foreach ($variableNames as $index => $name) {
                        $variables[$name] = $matches[$index];
                    }

                    // Set the variables
                    $route->setVariables($variables);

                    return $route;
                }
            }
        }

        if ($foundUri) throw new InvalidMethodHttpException();
        else throw new NotFoundHttpException();
    }

    public function getNamedRoute($name, array $parameters = []): string
    {
        foreach ($this->routes as $template => $routes) {
            foreach ($routes as $method => $route) {
                if ($route->getName() === $name) {
                    $path = $template;
                    foreach ($parameters as $key => $value) {
                        $path = str_replace('{' . $key . '}', $value, $path);
                    }
                    return $path;
                }
            }
        }
        throw new NotFoundHttpException();
    }
}