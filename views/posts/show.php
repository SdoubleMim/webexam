<div class="container mt-4">
    <?php if (isset($post) && is_object($post)): ?>
        <div class="card">
            <div class="card-body">
                <h1 class="card-title"><?= htmlspecialchars($post->title) ?></h1>
                <p class="card-text"><?= nl2br(htmlspecialchars($post->content)) ?></p>
                
                <!-- بخش جدید: اطلاعات تکمیلی -->
                <div class="mt-4 border-top pt-3">
                    <p class="text-muted small">
                        Author: <?= htmlspecialchars($post->user->name) ?><br>
                        Created: <?= $post->created_at ?><br>
                        Views: <?= $post->views->count() ?>
                    </p>
                    
                    <!-- بخش عملیات -->
                    <?php if (isset($canEdit) && $canEdit): ?>
                        <div class="btn-group mt-3">
                            <a href="/webexam/posts/<?= $post->id ?>/edit" class="btn btn-warning">Edit Post</a>
                            <form action="/webexam/posts/<?= $post->id ?>" method="POST" class="d-inline">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Are you sure?')">Delete Post</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">Post not found</div>
    <?php endif; ?>
</div>