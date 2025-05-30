<?php

// نمایش خطاها در محیط توسعه
error_reporting(E_ALL);
ini_set('display_errors', 1);

// تعریف مسیر root پروژه
define('ROOT_PATH', __DIR__);

// بارگذاری Composer
require ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/helper/functions.php';

// راه‌اندازی Eloquent
$config = require ROOT_PATH . '/config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Route;

// کنترلرها
use App\Controller\FrontController;
use App\Controller\UserController;
use App\Controller\PostController; // اگر داشتی

$capsule = new Capsule;
$capsule->addConnection($config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// تعریف مسیرها
$route = new Route();



// 📌 صفحه اصلی
$route->addRoute("GET", "/", [FrontController::class, 'home']);

// 📌 مسیرهای کاربران
$route->addRoute("GET", "/users", [UserController::class, 'index']);
$route->addRoute("GET", "/users/{id}", [UserController::class, 'show']);

// 📌 مسیرهای پست‌ها (در صورت داشتن کنترلر PostController)
$route->addRoute("GET", "/posts", [PostController::class, 'index']);
$route->addRoute("GET", "/posts/{id}", [PostController::class, 'show']);
$route->addRoute("GET", "/posts/create", [PostController::class, 'create']);
$route->addRoute("POST", "/posts/store", [PostController::class, 'store']);
$route->addRoute("GET", "/posts/{id}/edit", [PostController::class, 'edit']);
$route->addRoute("POST", "/posts/{id}/update", [PostController::class, 'update']);
$route->addRoute("GET", "/posts/{id}/delete", [PostController::class, 'delete']);


// 📌 مسیر تست
$route->addRoute("GET", "/test", function () {
    echo "Test route works!";
});

// اجرای سیستم مسیریابی
$route->dispatch();
