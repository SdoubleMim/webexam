<?php
namespace App;

class Route
{
    private static $instance;
    private $routes = [];
    private $basePath = '';
    private $middlewares = [];
    private $groupStack = [];
    private $currentGroupMiddleware = [];

    private function __construct(string $basePath = '')
    {
        $this->basePath = rtrim($basePath, '/');
    }

    public static function getInstance(string $basePath = ''): self
    {
        if (!self::$instance) {
            self::$instance = new self($basePath);
        }
        return self::$instance;
    }

    // HTTP Method Routes
    public static function get(string $path, $handler, array $middlewares = []): void
    {
        self::registerRoute('GET', $path, $handler, $middlewares);
    }

    public static function post(string $path, $handler, array $middlewares = []): void
    {
        self::registerRoute('POST', $path, $handler, $middlewares);
    }

    public static function put(string $path, $handler, array $middlewares = []): void
    {
        self::registerRoute('PUT', $path, $handler, $middlewares);
    }

    public static function delete(string $path, $handler, array $middlewares = []): void
    {
        self::registerRoute('DELETE', $path, $handler, $middlewares);
    }

    // Route Groups
    public static function group(array $attributes, callable $callback): void
    {
        $instance = self::getInstance();
        
        // Save current group state
        $instance->groupStack[] = [
            'middleware' => $instance->currentGroupMiddleware,
            'prefix' => $attributes['prefix'] ?? ''
        ];

        // Apply new group attributes
        if (isset($attributes['middleware'])) {
            $instance->currentGroupMiddleware = array_merge(
                $instance->currentGroupMiddleware,
                (array)$attributes['middleware']
            );
        }

        $callback($instance);

        // Restore previous group state
        $previousGroup = array_pop($instance->groupStack);
        $instance->currentGroupMiddleware = $previousGroup['middleware'];
    }

    // Middleware Management
    public function addMiddleware(string $name, callable $middleware): void
    {
        $this->middlewares[$name] = $middleware;
    }

    // Route Dispatching
    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestUri = str_replace($this->basePath, '', $requestUri) ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) {
                continue;
            }

            $matches = [];
            $pattern = $this->buildRoutePattern($route['path']);

            if (preg_match($pattern, $requestUri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Apply middlewares
                foreach ($route['middlewares'] as $middlewareName) {
                    if (!isset($this->middlewares[$middlewareName])) {
                        $this->abort(500, "Middleware '{$middlewareName}' not found");
                    }

                    $middlewareResult = call_user_func($this->middlewares[$middlewareName], $params);
                    if ($middlewareResult !== true) {
                        return; // Middleware handled the response
                    }
                }

                $this->callHandler($route['handler'], $params);
                return;
            }
        }

        $this->abort(404);
    }

    // Helper Methods
    private static function registerRoute(string $method, string $path, $handler, array $middlewares): void
    {
        $instance = self::getInstance();
        
        // Apply group prefix if exists
        $prefix = '';
        if (!empty($instance->groupStack)) {
            $currentGroup = end($instance->groupStack);
            $prefix = $currentGroup['prefix'] ?? '';
        }

        $instance->routes[] = [
            'method' => strtoupper($method),
            'path' => $prefix . $path,
            'handler' => $handler,
            'middlewares' => array_merge($instance->currentGroupMiddleware, $middlewares),
            'is_dynamic' => strpos($path, '{') !== false
        ];
    }

    private function buildRoutePattern(string $path): string
    {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . str_replace('/', '\/', $pattern) . '$#';
    }

    private function callHandler($handler, array $params): void
    {
        try {
            if (is_string($handler)) {
                $this->callControllerMethod($handler, $params);
                return;
            }

            if (is_callable($handler)) {
                call_user_func_array($handler, $params);
                return;
            }

            $this->abort(500, 'Invalid route handler');
        } catch (\Throwable $e) {
            $this->abort(500, $e->getMessage());
        }
    }

    private function callControllerMethod(string $handler, array $params): void
    {
        if (!str_contains($handler, '@')) {
            $this->abort(500, 'Invalid handler format (Controller@method)');
        }

        [$controllerName, $methodName] = explode('@', $handler);
        $controllerClass = "App\\Controller\\{$controllerName}";

        if (!class_exists($controllerClass)) {
            $this->abort(500, "Controller {$controllerClass} not found");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $methodName)) {
            $this->abort(500, "Method {$methodName} not found in {$controllerClass}");
        }

        call_user_func_array([$controller, $methodName], $params);
    }

    private function abort(int $code, string $message = ''): void
    {
        http_response_code($code);
        
        $errorPage = __DIR__ . '/../views/errors/' . $code . '.php';
        if (file_exists($errorPage)) {
            require $errorPage;
        } else {
            echo "<h1>Error {$code}</h1>";
            if ($message) {
                echo "<p>{$message}</p>";
            }
        }
        exit;
    }
}