<?php include_once ROOT_PATH . '/views/partials/header.php'; ?>

<h2>ایجاد پست جدید</h2>
<form action="/posts/store" method="POST">
    <label>عنوان:</label>
    <input type="text" name="title" required><br>
    <label>محتوا:</label>
    <textarea name="content" required></textarea><br>
    <button type="submit">ذخیره</button>
</form>
<a href="/posts">بازگشت</a>

<?php include_once ROOT_PATH . '/views/partials/footer.php'; ?>
