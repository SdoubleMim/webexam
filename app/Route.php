<?php
namespace App;

class Route {
    private $routes = [];
    private $basePath = '';

    public function __construct($basePath = '') {
        $this->basePath = rtrim($basePath, '/');
    }

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler,
            'is_dynamic' => strpos($path, '{') !== false
        ];
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestUri = str_replace($this->basePath, '', $requestUri) ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) continue;

            if ($route['is_dynamic']) {
                $pattern = $this->convertToRegex($route['path']);
                if (preg_match($pattern, $requestUri, $matches)) {
                    $this->handleRoute($route['handler'], array_values(array_filter($matches, 'is_int', ARRAY_FILTER_USE_KEY)));
                    return;
                }
            } elseif ($route['path'] === $requestUri) {
                $this->handleRoute($route['handler'], []);
                return;
            }
        }
        $this->abort(404);
    }

    private function convertToRegex($path) {
        return '#^' . preg_replace('/\{(\w+)\}/', '(?P<$1>[^\/]+)', $path) . '$#';
    }

    private function handleRoute($handler, $params) {
        if (is_string($handler)) {
            if (!str_contains($handler, '@')) {
                $this->abort(500, 'فرمت هندلر نامعتبر (باید به صورت "Controller@method" باشد)');
            }
            
            [$controllerName, $methodName] = explode('@', $handler);
            $controllerClass = "App\\Controller\\$controllerName";
            
            if (!class_exists($controllerClass)) {
                $this->abort(500, "کنترلر $controllerClass یافت نشد");
            }
            
            $controller = new $controllerClass();
            if (!method_exists($controller, $methodName)) {
                $this->abort(500, "متد $methodName در کنترلر $controllerClass وجود ندارد");
            }
            
            call_user_func_array([$controller, $methodName], $params);
            return;
        }
        
        $this->abort(500, 'هندلر باید رشته یا callable باشد');
    }

    private function abort($code, $message = '') {
        http_response_code($code);
        $errorPage = __DIR__ . '/../views/' . $code . '.php';
        file_exists($errorPage) ? require $errorPage : exit($message);
    }
}