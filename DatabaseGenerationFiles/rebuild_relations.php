<?php
use Carbon\Carbon;

require __DIR__.'/vendor/autoload.php';
$config = require __DIR__.'/config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Model\Post;
use App\Model\RelatedPost; // این خط اضافه شده

// Database setup
$capsule = new Capsule;
$capsule->addConnection($config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Verify table structure
$tableInfo = Capsule::select("SHOW COLUMNS FROM related_posts");
$columns = array_column($tableInfo, 'Field');

// Check if we should proceed
if (in_array('post_1_id', $columns) && in_array('post_2_id', $columns)) {
    // روش ۱: استفاده از مدل (ترجیحی)
    RelatedPost::truncate();
    $result = RelatedPost::generateRandomRelations();
    
    if ($result) {
        echo "<h2>روابط با موفقیت ایجاد شدند!</h2>";
    } else {
        echo "<h2>خطا: پست‌های کافی برای ایجاد رابطه وجود ندارد</h2>";
    }

    // روش ۲: استفاده مستقیم از Query Builder (جایگزین)
    /*
    // Clear existing relations
    Capsule::table('related_posts')->truncate();

    // Get all posts in random order
    $posts = Post::inRandomOrder()->get();
    $relationCount = 0;

    foreach ($posts as $post) {
        // Find 1-3 random related posts (excluding current post)
        $relatedPosts = Post::where('id', '!=', $post->id)
            ->inRandomOrder()
            ->limit(rand(1, 3))
            ->get();

        foreach ($relatedPosts as $relatedPost) {
            // جلوگیری از روابط تکراری
            $exists = RelatedPost::where(function($q) use ($post, $relatedPost) {
                $q->where('post_1_id', $post->id)
                  ->where('post_2_id', $relatedPost->id);
            })->orWhere(function($q) use ($post, $relatedPost) {
                $q->where('post_1_id', $relatedPost->id)
                  ->where('post_2_id', $post->id);
            })->exists();

            if (!$exists) {
                RelatedPost::create([
                    'post_1_id' => $post->id,
                    'post_2_id' => $relatedPost->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
                $relationCount++;
                
                echo "رابطه ایجاد شد: پست {$post->id} ↔ پست {$relatedPost->id}<br>";
            }
        }
    }

    echo "<h2>تعداد {$relationCount} رابطه با موفقیت ایجاد شد!</h2>";
    */
    
    // نمایش نمونه داده‌ها
    $sampleRelations = RelatedPost::with(['post1', 'post2'])
        ->limit(5)
        ->get();

    echo "<h3>نمونه روابط ایجاد شده:</h3>";
    foreach ($sampleRelations as $rel) {
        echo "رابطه #{$rel->id}: {$rel->post1->title} ↔ {$rel->post2->title}<br>";
    }
} else {
    die("خطا: ساختار جدول با ستون‌های مورد انتظار (post_1_id, post_2_id) مطابقت ندارد");
}