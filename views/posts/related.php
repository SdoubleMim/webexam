<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <?php if (isset($post)): ?>
        <h2><?= htmlspecialchars($title) ?></h2>
        
        <?php if (isset($relatedPosts) && $relatedPosts->count() > 0): ?>
            <div class="row">
                <?php foreach ($relatedPosts as $relatedPost): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($relatedPost->title) ?></h5>
                                <p class="card-text">
                                    By: <?= htmlspecialchars($relatedPost->user->name ?? 'Unknown') ?>
                                </p>
                                <a href="/webexam/posts/<?= $relatedPost->id ?>" class="btn btn-primary">
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
    <?php else: ?>
        <div class="alert alert-danger">Post not found</div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>