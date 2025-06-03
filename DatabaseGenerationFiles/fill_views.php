<?php
// اتصال به دیتابیس
$db = new PDO('mysql:host=localhost;dbname=webexam_database', 'root', '');

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