<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>Student Name</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $index => $user): ?>
        <tr>
            <td><?= $index+1 ?></td>
            <td><?= htmlspecialchars($user->name) ?></td>
            <td><?= htmlspecialchars($user->email) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>