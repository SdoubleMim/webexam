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

// 3. ایجاد روابط قوی‌تر بین پست‌ها
$hasRelations = $db->query("SELECT COUNT(*) as count FROM related_posts")->fetch(PDO::FETCH_OBJ)->count > 0;

if (!$hasRelations) {
    // ایجاد روابط تصادفی قوی‌تر با وزن‌دهی بر اساس شباهت
    for ($i = 0; $i < $n * 5; $i++) { // تعداد روابط بیشتر
        $post1 = $posts[rand(0, $n-1)]->id;
        $post2 = $posts[rand(0, $n-1)]->id;
        
        if ($post1 != $post2) {
            // محاسبه وزن رابطه بر اساس اختلاف بازدیدها
            $views1 = $posts[$idToIndex[$post1]]->views;
            $views2 = $posts[$idToIndex[$post2]]->views;
            $weight = 1 / (1 + abs($views1 - $views2));
            
            $db->exec("INSERT INTO related_posts (post_1_id, post_2_id, weight) 
                      VALUES ($post1, $post2, $weight)");
        }
    }
}

// 4. محاسبه ماتریس A با روش بهبود یافته
foreach ($posts as $i => $postI) {
    $postId = $postI->id;
    $totalWeight = 0;
    
    // دریافت روابط با وزن
    $relations = $db->query("
        SELECT 
            IF(post_1_id = $postId, post_2_id, post_1_id) as related_id,
            weight
        FROM related_posts
        WHERE post_1_id = $postId OR post_2_id = $postId
    ")->fetchAll(PDO::FETCH_OBJ);
    
    if (empty($relations)) {
        // اگر رابطه‌ای ندارد، به همه پست‌ها وزن یکسان بدهید
        $weight = 1.0 / $n;
        for ($j = 0; $j < $n; $j++) {
            $A[$i][$j] = $weight;
        }
        continue;
    }
    
    // محاسبه مجموع وزن‌ها
    $totalWeight = array_sum(array_column($relations, 'weight'));
    
    // پر کردن سطر ماتریس
    foreach ($relations as $rel) {
        $j = $idToIndex[$rel->related_id] ?? null;
        if ($j !== null) {
            $A[$i][$j] = $rel->weight / $totalWeight;
        }
    }
    
    // اطمینان از stochastic بودن ماتریس
    $rowSum = array_sum($A[$i]);
    if ($rowSum == 0) {
        $A[$i][$i] = 1.0; // خودارجاعی اگر همه صفر هستند
    }
}

// 5. الگوریتم Power Method بهبود یافته
function powerMethod(array $matrix, float $damping = 0.85): array {
    $n = count($matrix);
    $vector = array_fill(0, $n, 1.0 / $n);
    $uniform = array_fill(0, $n, 1.0 / $n);
    
    for ($iter = 0; $iter < 1000; $iter++) {
        $newVector = array_fill(0, $n, 0.0);
        
        // ضرب ماتریس
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $newVector[$i] += $damping * $matrix[$i][$j] * $vector[$j];
            }
            $newVector[$i] += (1 - $damping) * $uniform[$i];
        }
        
        // نرمال‌سازی
        $norm = array_sum($newVector);
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

// نرمال‌سازی به محدوده 0-100 برای خوانایی بهتر
$minScore = min($eigenvector);
$maxScore = max($eigenvector);
$range = max($maxScore - $minScore, 0.000001);

$rankedPosts = [];
foreach ($posts as $index => $post) {
    $normalizedScore = 100 * ($eigenvector[$index] - $minScore) / $range;
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
    echo sprintf("<tr><td>%d</td><td>%d</td><td>%.2f</td><td>%d</td></tr>",
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


?>