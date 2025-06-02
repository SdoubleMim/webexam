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

// Related Posts Routes
$router->get('/posts/{id}/relations', 'RelatedPostController@manageRelations');
$router->post('/related-posts', 'RelatedPostController@storeRelation');
$router->delete('/related-posts/{id}', 'RelatedPostController@deleteRelation');



// Main Routes
// اضافه کردن این مسیر جدید:

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

// =====================================
// New Routes Added Below (Maintaining the same style)
// =====================================

// API Routes
$router->get('/api/posts', 'Api\PostController@index');
$router->get('/api/posts/{id}', 'Api\PostController@show');
$router->post('/api/posts', 'Api\PostController@store');
$router->put('/api/posts/{id}', 'Api\PostController@update');
$router->delete('/api/posts/{id}', 'Api\PostController@delete');

// Admin Routes
$router->get('/admin/dashboard', 'AdminController@dashboard');
$router->get('/admin/users', 'AdminController@users');
$router->get('/admin/posts', 'AdminController@posts');

// Profile Routes
$router->get('/profile', 'ProfileController@show');
$router->get('/profile/edit', 'ProfileController@edit');
$router->post('/profile/update', 'ProfileController@update');
$router->post('/profile/change-password', 'ProfileController@changePassword');

// Category Routes
$router->get('/categories', 'CategoryController@index');
$router->get('/categories/{id}', 'CategoryController@show');
$router->get('/categories/{id}/posts', 'CategoryController@posts');

// Search Route
$router->get('/search', 'SearchController@index');

// Contact Routes
$router->get('/contact', 'ContactController@show');
$router->post('/contact', 'ContactController@submit');

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

// Related Posts Routes
$router->get('/related-posts', 'PostController@allRelations');
$router->get('/posts/{id}/relations', 'RelatedPostController@manageRelations');
$router->post('/related-posts', 'RelatedPostController@storeRelation');
$router->delete('/related-posts/{id}', 'RelatedPostController@deleteRelation');




// Dispatch the router
$router->dispatch();