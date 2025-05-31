<?php require_once __DIR__. '/../partials/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card bg-dark text-white shadow-lg">
                <div class="card-header bg-purple">
                    <h3 class="text-center mb-0">Register</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control bg-dark text-white" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control bg-dark text-white" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control bg-dark text-white" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control bg-dark text-white" id="confirm_password" name="confirm_password" required>
                        </div>
                        <button type="submit" class="btn btn-purple w-100">Register</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="/login" class="text-purple">Already have an account?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ .'/../partials/footer.php'; ?>