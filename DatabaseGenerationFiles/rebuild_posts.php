<?php
require __DIR__.'/vendor/autoload.php';
$config = require __DIR__.'/config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Model\User;
use App\Model\Post;

// تنظیمات دیتابیس
$capsule = new Capsule;
$capsule->addConnection($config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// بازسازی پست‌ها برای هر کاربر
$users = User::all();
foreach ($users as $user) {
    $postCount = rand(5, 7);
    for ($i = 1; $i <= $postCount; $i++) {
        Post::create([
            'title' => 'Post ' . $i . ' by ' . $user->name,
            'content' => 'این پست به صورت خودکار بازسازی شده است',
            'user_id' => $user->id,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
    echo "پست‌ها برای کاربر {$user->name} بازسازی شدند<br>";
}
echo "عملیات با موفقیت انجام شد!";