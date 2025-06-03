<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php if (isset($post) && is_object($post)): ?>
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Edit Post</h3>
                    </div>
                    <div class="card-body">
                        <form action="/webexam/posts/<?= $post->id ?>" method="POST">
                            <input type="hidden" name="_method" value="PUT">
                            
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="<?= htmlspecialchars($post->title) ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="content" class="form-label">Content</label>
                                <textarea class="form-control" id="content" name="content" 
                                          rows="8" required><?= htmlspecialchars($post->content) ?></textarea>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Update Post</button>
                                <a href="/webexam/posts/<?= $post->id ?>" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">Post not found or you don't have permission to edit it</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>