<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

define('ROOT_PATH', __DIR__);

require ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/helper/functions.php';

$config = require ROOT_PATH . '/config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Route;

$capsule = new Capsule;
$capsule->addConnection($config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize router with base path
$router = Route::getInstance('/webexam');

// Routes
Route::get('/', 'FrontController@home');
Route::get('/users', 'UserController@index');
Route::get('/users/{id}', 'UserController@show');
Route::get('/posts', 'PostController@index');
Route::get('/posts/{id}/related', 'PostController@related'); // تغییر مسیر به این شکل
Route::get('/posts/create', 'PostController@create');
Route::post('/posts', 'PostController@store');
Route::get('/posts/{id}/users', 'PostController@users');
Route::get('/posts/{id}', 'PostController@show');
Route::get('/posts/{id}/edit', 'PostController@edit');
Route::post('/posts/{id}', 'PostController@update');
Route::post('/posts/{id}/delete', 'PostController@delete');
Route::get('/login', 'AuthController@login');
Route::post('/login', 'AuthController@login');
Route::get('/register', 'AuthController@register');
Route::post('/register', 'AuthController@register');
Route::get('/logout', 'AuthController@logout');
Route::get('/test', function () {
    echo "Test route works!";
});

// Dispatch the router
$router->dispatch();