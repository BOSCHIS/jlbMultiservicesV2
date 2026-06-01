<?php

class Router
{
    private array $routes = [];

    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);

        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo "Page introuvable";
            return;
        }

        [$controller, $function] = explode('@', $this->routes[$method][$path]);

        require_once __DIR__ . '/../Controller/' . $controller . '.php';

        $controllerInstance = new $controller();
        $controllerInstance->$function();
    }

    public function post(string $path, string $action): void
    {
        $this->routes['POST'][$path] = $action;
    }
}
