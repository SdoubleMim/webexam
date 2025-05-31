<?php 
include_once ROOT_PATH . '/views/partials/header.php';

// Add validation
if (!isset($post) || $post === null) {
    die('Post not found');
}
?>

<h2><?= htmlspecialchars($post->title ?? 'No Title') ?></h2>

<p><?= nl2br(htmlspecialchars($post->content ?? 'No content available')) ?></p>

<?php if (isset($post->user) && $post->user): ?>
    <p>Author: <?= htmlspecialchars($post->user->Name) ?></p>
<?php endif; ?>

<?php include_once ROOT_PATH . '/views/partials/footer.php'; ?>