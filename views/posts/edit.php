<?php require 'views/partials/header.php'; ?>

<?php if (isset($post) && is_object($post)): ?>
    <h1>ویرایش پست</h1>
    <form action="/posts/update.php?id=<?= $post->id ?>" method="POST">
        <div>
            <label for="title">عنوان:</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($post->title) ?>" required>
        </div>
        <div>
            <label for="content">محتوا:</label>
            <textarea name="content" id="content" required><?= htmlspecialchars($post->content) ?></textarea>
        </div>
        <button type="submit">ذخیره</button>
    </form>
<?php else: ?>
    <p>پست مورد نظر برای ویرایش پیدا نشد.</p>
<?php endif; ?>

<?php require 'views/partials/footer.php'; ?>
