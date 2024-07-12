<?php

namespace App\Application\Route;

/**
 * @method static Route get(string $path, mixed $callback)
 * @method static Route post(string $path, mixed $callback)
 * @method static Route put(string $path, mixed $callback)
 * @method static Route patch(string $path, mixed $callback)
 * @method static Route delete(string $path, mixed $callback)
 */
class Route
{
    private \Closure $callback;
    private array $variables = [];
    private array $middlewares = [];
    private string|null $name = null;

    public static function fromAttributes(mixed $attributes): self
    {
        $route = new self();

        if (is_callable($attributes)) {
            $route->callback = $attributes;
        } else {
            // class method
            if (count($attributes) === 2) {
                $route->callback = function (...$variables) use ($attributes) {
                    $controller = new $attributes[0];
                    $method = $attributes[1];
                    return $controller->$method(...$variables);
                };
            } else {
                $route->callback = function () use ($attributes) {
                    echo $attributes;
                };
            }
        }

        return $route;
    }

    const array VALID_METHODS = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

    public static function __callStatic(string $name, array $arguments): self
    {
        if (in_array(strtoupper($name), self::VALID_METHODS)) {
            return RouteRegister::getInstance()->register(
                strtoupper($name),
                $arguments[0],
                static::fromAttributes($arguments[1])
            );
        }
        throw new \BadMethodCallException("Method $name does not exist");
    }

    public static function view(string $path, string $view)
    {
        return self::get($path, function () use ($view) {
            return Response::view($view);
        });
    }

    public function run()
    {
        return call_user_func($this->callback, ...$this->variables);
    }

    public function setVariables(array $variables)
    {
        $this->variables = $variables;
    }

    public function middleware(string|array $middleware): self
    {
        $this->middlewares = is_array($middleware) ? $middleware : [$middleware];

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function getName(): string|null
    {
        return $this->name;
    }
}