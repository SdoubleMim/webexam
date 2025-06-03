<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4"><?= htmlspecialchars($title ?? 'All Relations') ?></h2>
    
    <?php if (!empty($relations) && (is_array($relations) ? count($relations) > 0 : $relations->count() > 0)): ?>
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
                        <?php 
                        $post1 = is_object($relation) ? ($relation->post1 ?? null) : ($relation['post1'] ?? null);
                        $post2 = is_object($relation) ? ($relation->post2 ?? null) : ($relation['post2'] ?? null);
                        ?>
                        <tr>
                            <td><?= is_object($relation) ? $relation->id : $relation['id'] ?></td>
                            <td>
                                <?php if ($post1): ?>
                                    <a href="/webexam/posts/<?= is_object($post1) ? $post1->id : $post1['id'] ?>">
                                        <?= htmlspecialchars(is_object($post1) ? ($post1->title ?? 'Untitled Post') : ($post1['title'] ?? 'Untitled Post')) ?>
                                    </a>
                                    <small class="text-muted d-block">
                                        Author: <?= htmlspecialchars(is_object($post1) ? ($post1->user->name ?? 'Unknown') : ($post1['user']['name'] ?? 'Unknown')) ?>
                                    </small>
                                <?php else: ?>
                                    <span class="text-danger">Post not found</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($post2): ?>
                                    <a href="/webexam/posts/<?= is_object($post2) ? $post2->id : $post2['id'] ?>">
                                        <?= htmlspecialchars(is_object($post2) ? ($post2->title ?? 'Untitled Post') : ($post2['title'] ?? 'Untitled Post')) ?>
                                    </a>
                                    <small class="text-muted d-block">
                                        Author: <?= htmlspecialchars(is_object($post2) ? ($post2->user->name ?? 'Unknown') : ($post2['user']['name'] ?? 'Unknown')) ?>
                                    </small>
                                <?php else: ?>
                                    <span class="text-danger">Post not found</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (is_object($relation) ? isset($relation->created_at) : isset($relation['created_at'])): ?>
                                    <?= is_object($relation) ? $relation->created_at->format('Y/m/d H:i') : date('Y/m/d H:i', strtotime($relation['created_at'])) ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
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