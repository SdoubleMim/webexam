<?php require __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-4">
    <h1 class="mb-4">User Details</h1>

    <?php if (isset($user) && is_object($user)): ?>
        <div class="card">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">ID</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($user->id ?? 'N/A') ?></dd>

                    <dt class="col-sm-3">Full Name</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($user->name ?? 'N/A') ?></dd>

                    <dt class="col-sm-3">Email</dt>
                    <dd class="col-sm-9"><?= htmlspecialchars($user->email ?? 'N/A') ?></dd>

                    <dt class="col-sm-3">Registration Date</dt>
                    <dd class="col-sm-9"><?= isset($user->created_at) ? date('Y-m-d H:i', strtotime($user->created_at)) : 'N/A' ?></dd>

                    <dt class="col-sm-3">Total Posts</dt>
                    <dd class="col-sm-9"><?= $user->posts_count ?? 0 ?></dd>
                </dl>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            User not found.
        </div>
    <?php endif; ?>

    <div class="mt-3">
        <a href="/webexam/users" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Users List
        </a>
    </div>
</div>

<?php require __DIR__ . '/../partials/footer.php'; ?>