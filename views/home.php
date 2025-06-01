<?php
// views/home.php
require_once __DIR__ . '/partials/header.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$baseUrl = '/webexam'; // Adjust if your project is in a subfolder
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 text-purple mb-4">Welcome to WebExam Project</h1>
            <p class="lead text-light">
                <?= $isLoggedIn ? 'Hello, ' . htmlspecialchars($_SESSION['user_name'] ?? 'User') . '!' : 'A professional MVC framework implementation with authentication system' ?>
            </p>
            
            <div class="mt-5">
                <div class="row g-4">
                    <!-- Posts Section -->
                    <div class="col-md-6">
                        <div class="card bg-dark-purple h-100">
                            <div class="card-body">
                                <h5 class="card-title text-purple">📝 Posts</h5>
                                <p class="card-text">Manage and explore blog posts</p>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="<?= $baseUrl ?>/posts" class="btn btn-sm btn-outline-purple">View Posts</a>
                                    <?php if ($isLoggedIn): ?>
                                        <a href="<?= $baseUrl ?>/posts/create" class="btn btn-sm btn-purple">New Post</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Users Section -->
                    <div class="col-md-6">
                        <div class="card bg-dark-purple h-100">
                            <div class="card-body">
                                <h5 class="card-title text-purple">👥 Users</h5>
                                <p class="card-text">View registered users</p>
                                <a href="<?= $baseUrl ?>/users" class="btn btn-sm btn-outline-purple">View Users</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Auth Section -->
            <div class="mt-5">
                <?php if ($isLoggedIn): ?>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="<?= $baseUrl ?>/dashboard" class="btn btn-purple px-4">Dashboard</a>
                        <a href="<?= $baseUrl ?>/logout" class="btn btn-outline-purple px-4">Logout</a>
                    </div>
                <?php else: ?>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="<?= $baseUrl ?>/login" class="btn btn-purple px-4">Login</a>
                        <a href="<?= $baseUrl ?>/register" class="btn btn-outline-purple px-4">Register</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/partials/footer.php';
?>