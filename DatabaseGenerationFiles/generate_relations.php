<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Model\{Post, RelatedPost};

// تنظیمات دیتابیس
$capsule = new Capsule;
$capsule->addConnection(require __DIR__ . '/config/database.php');
$capsule->setAsGlobal();
$capsule->bootEloquent();

// تولید روابط
RelatedPost::generateRandomRelations();

echo "Random post relations generated successfully!\n";
echo "Total relations created: " . RelatedPost::count() . "\n";