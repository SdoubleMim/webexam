<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4"><?= htmlspecialchars($title) ?></h2>
    
    <?php if ($relations->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Relation ID</th>
                        <th>Post 1</th>
                        <th>Post 2</th>
                        <th>Created at</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($relations as $relation): ?>
                        <tr>
                            <td><?= $relation->id ?></td>
                            <td>
                                <?php if ($relation->post1->id ?? false): ?>
                                    <a href="/webexam/posts/<?= $relation->post1->id ?>">
                                        <?= htmlspecialchars($relation->post1->title) ?>
                                    </a>
                                <?php else: ?>
                                    <?= htmlspecialchars($relation->post1->title) ?>
                                <?php endif; ?>
                                <small class="text-muted d-block">
                                    Author: <?= htmlspecialchars($relation->post1->user->name) ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($relation->post2->id ?? false): ?>
                                    <a href="/webexam/posts/<?= $relation->post2->id ?>">
                                        <?= htmlspecialchars($relation->post2->title) ?>
                                    </a>
                                <?php else: ?>
                                    <?= htmlspecialchars($relation->post2->title) ?>
                                <?php endif; ?>
                                <small class="text-muted d-block">
                                    Author: <?= htmlspecialchars($relation->post2->user->name) ?>
                                </small>
                            </td>
                            <td><?= $relation->created_at->format('Y/m/d H:i') ?></td>
                        
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            No relations found!
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>