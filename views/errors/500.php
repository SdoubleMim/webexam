<?php include_once __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <div class="alert alert-danger">
        <h1>500 Server Error</h1>
        <p><?= htmlspecialchars($message ?? 'An internal server error occurred') ?></p>
        <a href="/webexam/" class="btn btn-primary">Return Home</a>
    </div>
</div>

<?php include_once __DIR__ . '/../partials/footer.php'; ?>