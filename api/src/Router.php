<?php
declare(strict_types=1);
class Router {
    private array $routes = [];

    public function get(string $path, callable $handler): void {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void {
        $this->routes['POST'][$path] = $handler;
    }

    public function delete(string $path, callable $handler): void {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function dispatch(string $method, string $path): void {
        foreach ($this->routes[$method] ?? [] as $route => $handler) {
            $pattern = '#^' . preg_replace('#\{(\w+)\}#', '(?<$1>[^/]+)', $route) . '$#';
            if (preg_match($pattern, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $handler($params);
                return;
            }
        }
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}

?>