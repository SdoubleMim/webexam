<?php include_once __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <h2>Posts List</h2>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <a href="/webexam/posts/create" class="btn btn-primary mb-3">Create New Post</a>
    <?php endif; ?>
    
    <div class="list-group">
        <?php foreach ($posts as $post): ?>
        <div class="list-group-item">
            <h5>
                <a href="/webexam/posts/<?= $post->id ?>">
                    <?= htmlspecialchars($post->title) ?>
                </a>
            </h5>
            <p class="mb-1">Author: <?= htmlspecialchars($post->user->name) ?></p>
            
            <?php if (isset($currentUser) && $currentUser === $post->user_id): ?>
                <div class="mt-2">
                    <a href="/webexam/posts/<?= $post->id ?>/edit" class="btn btn-sm btn-warning">Edit</a>
                    <form action="/webexam/posts/<?= $post->id ?>/delete" method="POST" style="display:inline">
                        <button type="submit" class="btn btn-sm btn-danger" 
                            onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include_once __DIR__ . '/../partials/footer.php'; ?>