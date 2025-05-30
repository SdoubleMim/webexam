<?php require __DIR__ . '/../partials/header.php'; ?>

<h2>لیست کاربران</h2>

<table border="1" cellpadding="10" cellspacing="0">
    <thead>
        <tr>
            <th>شناسه</th>
            <th>نام</th>
            <th>ایمیل</th>
            <th>عملیات</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user->ID) ?></td>
                <td><?= htmlspecialchars($user->Name) ?></td>
                <td><?= htmlspecialchars($user->Email) ?></td>
                <td><a href="/webexam/users/<?= $user->ID ?>">نمایش</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require __DIR__ . '/../partials/footer.php'; ?>
