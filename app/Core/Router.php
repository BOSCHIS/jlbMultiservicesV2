<?php

class Router
{
    private array $routes = [];

    public function get(string $path, string $action): void
    {
        $this->routes['GET'][$path] = $action;
    }

    public function post(string $path, string $action): void
    {
        $this->routes['POST'][$path] = $action;
    }

    public function dispatch(string $uri, string $method): void
    {
        $path = parse_url($uri, PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            [$controller, $function] = explode('@', $this->routes[$method][$path]);

            require_once __DIR__ . '/../Controller/' . $controller . '.php';

            $controllerInstance = new $controller();
            $controllerInstance->$function();

            return;
        }

        if ($method === 'GET' && $this->tryDynamicCategory($path)) {
            return;
        }

        http_response_code(404);
        echo "Page introuvable";
    }

    private function tryDynamicCategory(string $path): bool
    {
        $slug = trim($path, '/');

        if ($slug === '') {
            return false;
        }

        if (str_contains($slug, '/')) {
            return false;
        }

        $reservedPaths = [
            'admin',
            'assets',
            'contact',
            'service',
            'entreprise',
            'mentions-legales',
            'politique-confidentialite',
            'favicon.ico'
        ];

        if (in_array($slug, $reservedPaths, true)) {
            return false;
        }

        require_once __DIR__ . '/../Controller/ServiceController.php';

        $controller = new ServiceController();

        return $controller->showDynamicCategory($slug);
    }
}
