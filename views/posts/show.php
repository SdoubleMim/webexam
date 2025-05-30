<?php include_once ROOT_PATH . '/views/partials/header.php'; ?>

<h2><?= htmlspecialchars($post->title) ?></h2>
<p><?= nl2br(htmlspecialchars($post->content)) ?></p>
<p><strong>نویسنده:</strong> <?= htmlspecialchars($post->user->Name ?? 'نامشخص') ?></p>
<a href="/posts">بازگشت</a>

<?php include_once ROOT_PATH . '/views/partials/footer.php'; ?>
