<?php
// نمایش خطاها در محیط توسعه
error_reporting(E_ALL);
ini_set('display_errors', 1);

// تعریف مسیر root پروژه
define('ROOT_PATH', __DIR__);

// بارگذاری Composer و فایل‌های ضروری
require ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/helper/functions.php';

// راه‌اندازی Eloquent
$config = require ROOT_PATH . '/config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Route;

// Initialize database connection
$capsule = new Capsule;
$capsule->addConnection($config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create router instance
$router = new Route('/webexam'); // اگر پروژه در زیرپوشه است، آدرس را اصلاح کنید

// ==================== Route Definitions ====================

// 📌 صفحه اصلی
$router->addRoute("GET", "/", "FrontController@home");

// 📌 مسیرهای کاربران
$router->addRoute("GET", "/users", "UserController@index");
$router->addRoute("GET", "/users/{id}", "UserController@show");

// 📌 مسیرهای پست‌ها
$router->addRoute("GET", "/posts", "PostController@index");
$router->addRoute("GET", "/posts/create", "PostController@create");
$router->addRoute("POST", "/posts", "PostController@store");
$router->addRoute("GET", "/posts/{id}", "PostController@show");
$router->addRoute("GET", "/posts/{id}/edit", "PostController@edit");
$router->addRoute("POST", "/posts/{id}", "PostController@update");
$router->addRoute("POST", "/posts/{id}/delete", "PostController@delete");

// 📌 مسیرهای احراز هویت
$router->addRoute("GET", "/login", "AuthController@login");
$router->addRoute("POST", "/login", "AuthController@login");
$router->addRoute("GET", "/register", "AuthController@register");
$router->addRoute("POST", "/register", "AuthController@register");
$router->addRoute("GET", "/logout", "AuthController@logout");

// 📌 مسیر تست
$router->addRoute("GET", "/test", function () {
    echo "Test route works!";
});

// اجرای سیستم مسیریابی
$router->dispatch();