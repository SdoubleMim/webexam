<?php require __DIR__ . '/../partials/header.php'; ?>

<h1>مشخصات کاربر</h1>

<?php if (is_object($user)): ?>
    <ul>
        <li><strong>شناسه:</strong> <?= htmlspecialchars($user->ID) ?></li>
        <li><strong>نام:</strong> <?= htmlspecialchars($user->Name) ?></li>
        <li><strong>ایمیل:</strong> <?= htmlspecialchars($user->Email) ?></li>
    </ul>
<?php else: ?>
    <p>کاربر مورد نظر پیدا نشد.</p>
<?php endif; ?>

<a href="/webexam/users">بازگشت</a>

<?php require __DIR__ . '/../partials/footer.php'; ?>
