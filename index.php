<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT_PATH', __DIR__);

require ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/helper/functions.php';

$config = require ROOT_PATH . '/config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Route;
use Core\Helpers\Redirect;
use Core\Helpers\Session;
$capsule = new Capsule;
$capsule->addConnection($config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize router with base path
$router = Route::getInstance('/webexam');


// Main Routes

$router->get('/related-posts', 'PostController@allRelations');
$router->get('/', 'FrontController@home');
$router->get('/users', 'UserController@index');
$router->get('/users/{id}', 'UserController@show');
$router->get('/posts', 'PostController@index');
$router->get('/posts/{id}/related', 'PostController@related');
$router->get('/posts/create', 'PostController@create');
$router->post('/posts', 'PostController@store');
$router->get('/posts/{id}/users', 'PostController@users');
$router->get('/posts/{id}', 'PostController@show');
$router->get('/posts/{id}/edit', 'PostController@edit');
$router->put('/posts/{id}', 'PostController@update');
$router->delete('/posts/{id}', 'PostController@delete');
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');
$router->get('/test', function () {
    echo "Test route works!";
});


// Category Routes
// Post Routes
$router->get('/posts', 'PostController@index');
$router->get('/posts/create', 'PostController@create');
$router->post('/posts', 'PostController@store');
$router->get('/posts/{id}', 'PostController@show');
$router->get('/posts/{id}/edit', 'PostController@edit');
$router->put('/posts/{id}', 'PostController@update');
$router->delete('/posts/{id}', 'PostController@delete');
$router->get('/posts/{id}/users', 'PostController@users');
$router->get('/posts/{id}/related', 'PostController@related');

$router->put('/posts/{id}', 'PostController@update');  // برای ویرایش
$router->delete('/posts/{id}', 'PostController@delete'); // برای حذف


// Dispatch the router
$router->dispatch();


// کد موقت برای پر کردن post_views
if (isset($_GET['fill_views'])) {
    $db = new PDO('mysql:host=localhost;dbname=webexam_database', 'username', 'password');
    
    // پاکسازی جدول
    $db->exec("TRUNCATE TABLE post_views");
    
    // دریافت تمام پست‌ها
    $posts = $db->query("SELECT id FROM posts")->fetchAll(PDO::FETCH_OBJ);
    
    // درج داده‌های تصادفی
    foreach ($posts as $post) {
        $views = rand(100, 1000);
        $db->exec("INSERT INTO post_views (post_id, views, created_at, updated_at) 
                  VALUES ($post->id, $views, NOW(), NOW())");
    }
    
    echo "Post views filled successfully!";
    exit;
}