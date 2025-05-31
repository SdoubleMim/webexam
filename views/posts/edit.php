<?php
include_once ROOT_PATH . '/views/partials/header.php';

// Add null checks before accessing properties
if (!isset($post) || $post === null) {
    die('Post not found or invalid');
}
?>

<div class="form-group">
    <label for="title">Title</label>
    <input type="text" name="title" value="<?= htmlspecialchars($post->title ?? '') ?>">
</div>

<div class="form-group">
    <label for="content">Content</label>
    <textarea name="content"><?= htmlspecialchars($post->content ?? '') ?></textarea>
</div>

<?php include_once ROOT_PATH . '/views/partials/footer.php'; ?>