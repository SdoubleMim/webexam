<?php
// 1. اتصال به دیتابیس
$db = new PDO('mysql:host=localhost;dbname=webexam_database', 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 2. دریافت تمام پست‌ها با بازدیدها
$posts = $db->query("
    SELECT p.id, pv.views 
    FROM posts p
    JOIN post_views pv ON p.id = pv.post_id
    ORDER BY p.id
")->fetchAll(PDO::FETCH_OBJ);

$n = count($posts);
$A = array_fill(0, $n, array_fill(0, $n, 0));
$idToIndex = array_flip(array_column($posts, 'id'));

// 3. ایجاد روابط پایه اگر جدول related_posts خالی است
$hasRelations = $db->query("SELECT COUNT(*) as count FROM related_posts")->fetch(PDO::FETCH_OBJ)->count > 0;

if (!$hasRelations) {
    // ایجاد روابط تصادفی بین پست‌ها
    for ($i = 0; $i < $n * 2; $i++) {
        $post1 = rand(66, 215); // محدوده ID پست‌ها
        $post2 = rand(66, 215);
        if ($post1 != $post2) {
            $db->exec("INSERT INTO related_posts (post_1_id, post_2_id) VALUES ($post1, $post2)");
        }
    }
}

// 4. محاسبه ماتریس A با روش صحیح
foreach ($posts as $i => $postI) {
    $postId = $postI->id;
    
    $relations = $db->query("
        SELECT 
            IF(post_1_id = $postId, post_2_id, post_1_id) as related_id,
            IF(post_1_id = $postId, 
               (SELECT views FROM post_views WHERE post_id = post_2_id),
               (SELECT views FROM post_views WHERE post_id = post_1_id)
            ) as related_views
        FROM related_posts
        WHERE post_1_id = $postId OR post_2_id = $postId
    ")->fetchAll(PDO::FETCH_OBJ);
    
    if (empty($relations)) {
        $A[$i][$i] = 1.0; // خودارجاعی اگر رابطه‌ای ندارد
        continue;
    }
    
    // محاسبه مجموع بازدیدهای مرتبط
    $totalViews = array_sum(array_column($relations, 'related_views'));
    $totalViews = $totalViews > 0 ? $totalViews : 1;
    
    // پر کردن سطر ماتریس
    foreach ($relations as $rel) {
        $j = $idToIndex[$rel->related_id] ?? null;
        if ($j !== null && $j != $i) {
            $A[$i][$j] = $rel->related_views / $totalViews;
        }
    }
    
    // نرمال‌سازی نهایی سطر
    $rowSum = array_sum($A[$i]);
    if ($rowSum > 0) {
        foreach ($A[$i] as &$val) {
            $val /= $rowSum;
        }
    } else {
        $A[$i][$i] = 1.0;
    }
}

// 5. تابع محاسبه بردار ویژه بهبود یافته
function powerMethod(array $matrix): array {
    $n = count($matrix);
    $vector = array_fill(0, $n, 1.0);
    
    for ($iter = 0; $iter < 1000; $iter++) {
        $newVector = array_fill(0, $n, 0.0);
        
        // ضرب ماتریس در بردار
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $newVector[$i] += $matrix[$i][$j] * $vector[$j];
            }
        }
        
        // نرمال‌سازی
        $norm = sqrt(array_sum(array_map(fn($x) => $x * $x, $newVector)));
        $newVector = array_map(fn($x) => $x / $norm, $newVector);
        
        // بررسی همگرایی
        $diff = array_sum(array_map(fn($a, $b) => abs($a - $b), $newVector, $vector));
        if ($diff < 1e-12) break;
        
        $vector = $newVector;
    }
    
    return $vector;
}

// 6. محاسبه و نمایش نتایج
$eigenvector = powerMethod($A);

// نرمال‌سازی امتیازها به محدوده 0 تا 1
$minScore = min($eigenvector);
$maxScore = max($eigenvector);
$range = max($maxScore - $minScore, 0.000001);

$rankedPosts = [];
foreach ($posts as $index => $post) {
    $normalizedScore = ($eigenvector[$index] - $minScore) / $range;
    $rankedPosts[] = [
        'post_id' => $post->id,
        'score' => $normalizedScore,
        'views' => $post->views
    ];
}

usort($rankedPosts, fn($a, $b) => $b['score'] <=> $a['score']);

// نمایش نتایج
echo "<h2>رتبه‌بندی نهایی پست‌ها</h2>";
echo "<table border='1'><tr><th>رتبه</th><th>ID پست</th><th>امتیاز اهمیت</th><th>تعداد بازدیدها</th></tr>";
foreach ($rankedPosts as $rank => $post) {
    echo sprintf("<tr><td>%d</td><td>%d</td><td>%.6f</td><td>%d</td></tr>",
        $rank + 1,
        $post['post_id'],
        $post['score'],
        $post['views']
    );
}
echo "</table>";

// نمایش نمونه ماتریس برای دیباگ
echo "<h3>نمونه ماتریس A (5×5 اول)</h3>";
echo "<pre>";
for ($i = 0; $i < min(5, $n); $i++) {
    for ($j = 0; $j < min(5, $n); $j++) {
        echo number_format($A[$i][$j], 6) . " ";
    }
    echo "\n";
}
echo "</pre>";