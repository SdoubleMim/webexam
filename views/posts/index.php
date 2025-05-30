<?php include_once ROOT_PATH . '/views/partials/header.php'; ?>

<h2>لیست پست‌ها</h2>
<a href="/posts/create">ایجاد پست جدید</a>
<ul>
    <?php foreach ($posts as $post): ?>
        <li>
            <a href="/posts/<?= $post->id ?>"><?= htmlspecialchars($post->title) ?></a>
            (نویسنده: <?= htmlspecialchars($post->user->Name ?? 'نامشخص') ?>)
            - <a href="/posts/<?= $post->id ?>/edit">ویرایش</a>
            - <a href="/posts/<?= $post->id ?>/delete" onclick="return confirm('آیا مطمئن هستید؟')">حذف</a>
        </li>
    <?php endforeach; ?>
</ul>

<?php include_once ROOT_PATH . '/views/partials/footer.php'; ?>
