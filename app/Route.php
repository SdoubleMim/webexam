<?php
namespace App;

class Route {
    private $routes = [];

    public function addRoute($method, $path, $handler) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function dispatch() {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove base path
        $basePath = '/webexam';
        $requestUri = str_replace($basePath, '', $requestUri);
        $requestUri = $requestUri ?: '/';

        foreach ($this->routes as $route) {
            // Method check
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            // Convert route pattern to regex
            $pattern = $this->convertToRegex($route['path']);

            if (preg_match($pattern, $requestUri, $matches)) {
                // حذف full match (index 0)
                array_shift($matches);

                // فقط مقادیر positional نگه‌دار (با کلید عددی)
                $matches = array_filter($matches, 'is_int', ARRAY_FILTER_USE_KEY);
                $matches = array_values($matches); // مرتب‌سازی مجدد از index صفر

                // Handle the route
                $this->handleRoute($route['handler'], $matches);
                return;
            }
        }

        // 404 Not Found
        $this->abort(404);
    }


    private function convertToRegex($path) {
        // Replace {param} with regex capture
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^\/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function handleRoute($handler, $params) {
        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
            return;
        }

        if (is_array($handler) && count($handler) === 2) {
            $className = $handler[0];
            $methodName = $handler[1];
            
            if (class_exists($className)) {
                $controller = new $className();
                
                if (method_exists($controller, $methodName)) {
                    call_user_func_array([$controller, $methodName], $params);
                    return;
                }
            }
        }

        $this->abort(500, 'Invalid route handler');
    }

    private function abort($code, $message = '') {
        http_response_code($code);
        die($message);
    }
}