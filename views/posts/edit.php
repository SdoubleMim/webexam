<?php include_once ROOT_PATH . '/views/partials/header.php'; ?>

<div class="container mt-4">
    <?php if (!isset($post)): ?>
        <div class="alert alert-danger">پست موردنظر وجود ندارد!</div>
    <?php else: ?>
        <h2>ویرایش پست</h2>
        <form action="/posts/<?= $post->id ?>/update" method="POST">
            <div class="form-group">
                <label>عنوان:</label>
                <input 
                    type="text" 
                    name="title" 
                    class="form-control"
                    value="<?= htmlspecialchars($post->title) ?>" 
                    required
                >
            </div>
            <div class="form-group">
                <label>محتوا:</label>
                <textarea 
                    name="content" 
                    class="form-control"
                    required
                ><?= htmlspecialchars($post->content) ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">ذخیره تغییرات</button>
        </form>
    <?php endif; ?>

    <a href="/posts" class="btn btn-secondary mt-3">بازگشت به لیست پست‌ها</a>
</div>

<?php include_once ROOT_PATH . '/views/partials/footer.php'; ?>