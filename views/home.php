<?php
// views/home.php
require_once __DIR__ . '/partials/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="display-4 text-purple mb-4">Welcome to WebExam Project</h1>
            <p class="lead text-light">
                A professional MVC framework implementation with authentication system
            </p>
            
            <div class="mt-5">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card bg-dark-purple h-100">
                            <div class="card-body">
                                <h5 class="card-title text-purple">📝 Posts</h5>
                                <p class="card-text">Manage and explore blog posts</p>
                                <a href="/posts" class="btn btn-sm btn-outline-purple">View Posts</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card bg-dark-purple h-100">
                            <div class="card-body">
                                <h5 class="card-title text-purple">👥 Users</h5>
                                <p class="card-text">View registered users</p>
                                <a href="/users" class="btn btn-sm btn-outline-purple">View Users</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-5">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/logout" class="btn btn-purple px-4">Logout</a>
                <?php else: ?>
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="/login" class="btn btn-purple px-4">Login</a>
                        <a href="/register" class="btn btn-outline-purple px-4">Register</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/partials/footer.php';
?>