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

$router = new Route('/webexam');

// Routes
$router->addRoute("GET", "/", "FrontController@home");
$router->addRoute("GET", "/users", "UserController@index");
$router->addRoute("GET", "/users/{id}", "UserController@show");
$router->addRoute("GET", "/posts", "PostController@index");
$router->addRoute("GET", "/posts/related", "PostController@related");
$router->addRoute("GET", "/posts/create", "PostController@create");
$router->addRoute("POST", "/posts", "PostController@store");
$router->addRoute("GET", "/posts/{id}", "PostController@show");
$router->addRoute("GET", "/posts/{id}/edit", "PostController@edit");
$router->addRoute("POST", "/posts/{id}", "PostController@update");
$router->addRoute("POST", "/posts/{id}/delete", "PostController@delete");
$router->addRoute("GET", "/login", "AuthController@login");
$router->addRoute("POST", "/login", "AuthController@login");
$router->addRoute("GET", "/register", "AuthController@register");
$router->addRoute("POST", "/register", "AuthController@register");
$router->addRoute("GET", "/logout", "AuthController@logout");
$router->addRoute("GET", "/test", function () {
    echo "Test route works!";
});

$router->dispatch();