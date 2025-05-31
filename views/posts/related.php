<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <h2 class="mb-4">روابط بین پست‌ها</h2>
    
    <?php if (count($relations) > 0): ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID رابطه</th>
                        <th>پست اول</th>
                        <th>پست دوم</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($relations as $relation): ?>
                    <tr>
                        <td><?= $relation->id ?></td>
                        <td>پست #<?= $relation->post1_id ?> (<?= $relation->post1->title ?? 'بدون عنوان' ?>)</td>
                        <td>پست #<?= $relation->post2_id ?> (<?= $relation->post2->title ?? 'بدون عنوان' ?>)</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">هیچ رابطه‌ای بین پست‌ها ثبت نشده است</div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>