<?php

require 'vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;

$config = require __DIR__ . '/config/database.php';
$capsule = new Capsule;
$capsule->addConnection($config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

use App\Model\User;

$users = User::all();

foreach ($users as $user) {
    echo $user->Name . "<br>";
}
