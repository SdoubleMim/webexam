<?php require 'views/partials/header.php'; ?>

<?php if (isset($post) && is_object($post)): ?>
    <div class="post">
        <h1><?= htmlspecialchars($post->title) ?></h1>
        <p><?= nl2br(htmlspecialchars($post->content)) ?></p>
    </div>
<?php else: ?>
    <p>پست مورد نظر پیدا نشد.</p>
<?php endif; ?>

<?php require 'views/partials/footer.php'; ?>
