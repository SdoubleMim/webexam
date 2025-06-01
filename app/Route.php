<?php
namespace App;

class Route {
    private static $instance;
    private $routes = [];
    private $basePath = '';

    public function __construct($basePath = '') {
        $this->basePath = rtrim($basePath, '/');
    }

    public static function getInstance($basePath = '') {
        if (!self::$instance) {
            self::$instance = new self($basePath);
        }
        return self::$instance;
    }

    public static function get($path, $handler) {
        self::getInstance()->addRoute('GET', $path, $handler);
    }

    public static function post($path, $handler) {
        self::getInstance()->addRoute('POST', $path, $handler);
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
        // اصلاح الگوی regex برای تطبیق بهتر
        return '#^' . preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path) . '$#';
    }

    private function handleRoute($handler, $params) {
        try {
            if (is_string($handler)) {
                if (!str_contains($handler, '@')) {
                    $this->abort(500, 'Invalid handler format');
                }
                
                [$controllerName, $methodName] = explode('@', $handler);
                $controllerClass = "App\\Controller\\$controllerName";
                
                if (!class_exists($controllerClass)) {
                    $this->abort(500, "Controller $controllerClass not found");
                }
                
                $controller = new $controllerClass();
                if (!method_exists($controller, $methodName)) {
                    $this->abort(500, "Method $methodName not found");
                }
                
                call_user_func_array([$controller, $methodName], $params);
                return;
            }
            
            if (is_callable($handler)) {
                call_user_func_array($handler, $params);
                return;
            }
            
            $this->abort(500, 'Invalid handler');
        } catch (\Exception $e) {
            $this->abort(500, $e->getMessage());
        }
    }

    // private function handleRoute($handler, $params) {
    //     if (is_string($handler)) {
    //         if (!str_contains($handler, '@')) {
    //             $this->abort(500, 'Invalid handler format (should be "Controller@method")');
    //         }
            
    //         [$controllerName, $methodName] = explode('@', $handler);
    //         $controllerClass = "App\\Controller\\$controllerName";
            
    //         if (!class_exists($controllerClass)) {
    //             $this->abort(500, "Controller $controllerClass not found");
    //         }
            
    //         $controller = new $controllerClass();
    //         if (!method_exists($controller, $methodName)) {
    //             $this->abort(500, "Method $methodName does not exist in $controllerClass");
    //         }
            
    //         call_user_func_array([$controller, $methodName], $params);
    //         return;
    //     }
        
    //     if (is_callable($handler)) {
    //         call_user_func_array($handler, $params);
    //         return;
    //     }
        
    //     $this->abort(500, 'Handler must be string or callable');
    // }

    private function abort($code, $message = '') {
        http_response_code($code);
        $errorPage = __DIR__ . '/../views/' . $code . '.php';
        if (file_exists($errorPage)) {
            require $errorPage;
        } else {
            echo "<h1>Error $code</h1>";
            if ($message) {
                echo "<p>$message</p>";
            }
        }
        exit;
    }
}