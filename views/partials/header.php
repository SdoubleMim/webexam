<!-- <!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>سیستم مدیریت کاربران</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">وب‌آزمون</a>
        </div>
    </nav>
    <div class="container mt-4"> -->

<!-- views/partials/header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <!-- Bootstrap 5 Dark Mode -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Purple Dark Theme -->
    <style>
        :root {
            --bs-dark: #1a1a2e;
            --bs-dark-rgb: 26, 26, 46;
            --bs-purple: #6a0dad;
            --bs-purple-rgb: 106, 13, 173;
        }
        
        body {
            background-color: var(--bs-dark);
            color: #f8f9fa;
        }
        
        .bg-purple {
            background-color: var(--bs-purple) !important;
        }
        
        .btn-purple {
            background-color: var(--bs-purple);
            color: white;
            border: none;
        }
        
        .btn-purple:hover {
            background-color: #5a0b9d;
            color: white;
        }
        
        .text-purple {
            color: var(--bs-purple) !important;
        }
        
        .card {
            border: 1px solid rgba(106, 13, 173, 0.3);
        }
        
        .form-control, .form-control:focus {
            background-color: rgba(26, 26, 46, 0.7);
            color: white;
            border-color: var(--bs-purple);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-purple">
        <div class="container">
            <a class="navbar-brand" href="/">My Blog</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/logout">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <main class="container py-4">