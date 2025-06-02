<?php
require __DIR__.'/vendor/autoload.php';
$config = require __DIR__.'/config/database.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Model\{User, Post, RelatedPost};

// Database setup
$capsule = new Capsule;
$capsule->addConnection($config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Generate 7-8 random posts per user
$users = User::all();
foreach ($users as $user) {
    $postCount = rand(7, 8);
    for ($i = 0; $i < $postCount; $i++) {
        Post::create([
            'title' => "Post " . ($i+1),
            'content' => "Sample content for post " . ($i+1) . " by user " . $user->name,
            'user_id' => $user->id
        ]);
    }
}

// Create random relationships between posts
$posts = Post::all();
foreach ($posts as $post) {
    $relatedCount = rand(1, 3);
    $relatedPosts = $posts->where('id', '!=', $post->id)->random($relatedCount);
    
    foreach ($relatedPosts as $relatedPost) {
        RelatedPost::firstOrCreate([
            'post_1_id' => $post->id,
            'post_2_id' => $relatedPost->id
        ]);
    }
}

echo "Test data generated successfully!";