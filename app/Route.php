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

        // حذف مسیر پوشه پروژه از URL
        $basePath = '/webexam'; // ← اگر پروژه در localhost/webexam است
        $requestUri = str_replace($basePath, '', $requestUri);
        $requestUri = $requestUri ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $requestMethod) continue;

            // تبدیل مسیرهای داینامیک {id} به regex
            $pattern = preg_replace('#\{[\w]+\}#', '([\w-]+)', $route['path']);
            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // حذف full match
                $handler = $route['handler'];

                // اگر handler تابع است
                if (is_callable($handler)) {
                    call_user_func_array($handler, $matches);
                    return;
                }

                // اگر handler کلاس + متد است
                $controller = new $handler[0];
                $method = $handler[1];
                call_user_func_array([$controller, $method], $matches);
                return;
            }
        }

        // نمایش صفحه 404
        http_response_code(404);
        $viewPath = __DIR__ . '/../views/404.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "صفحه مورد نظر یافت نشد!";
        }
    }
}
