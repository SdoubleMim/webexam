<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <h2><?= htmlspecialchars($title) ?></h2>
    
    <?php if ($relatedPosts && count($relatedPosts) > 0): ?>
        <div class="row">
            <?php foreach ($relatedPosts as $post): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($post->title) ?></h5>
                            <p class="card-text">
                                By: <?= htmlspecialchars($post->user->name ?? 'Unknown') ?>
                            </p>
                            <a href="/posts/<?= $post->id ?>" class="btn btn-primary">
                                View Post
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No related posts found</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>