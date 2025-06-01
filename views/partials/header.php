<?php
// views/partials/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$baseUrl = '/webexam'; // Adjust if your project is in a subfolder
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebExam System</title>
    <!-- Bootstrap 5 Dark Mode -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Purple Dark Theme -->
    <style>
        :root {
            --bs-dark: #1a1a2e;
            --bs-dark-rgb: 26, 26, 46;
            --bs-purple: #6a0dad;
            --bs-purple-rgb: 106, 13, 173;
            --bs-dark-purple: #2a0a4a;
        }
        
        body {
            background-color: var(--bs-dark);
            color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .bg-purple {
            background-color: var(--bs-purple) !important;
        }
        
        .bg-dark-purple {
            background-color: var(--bs-dark-purple) !important;
            border: 1px solid var(--bs-purple);
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
        
        .btn-outline-purple {
            border-color: var(--bs-purple);
            color: var(--bs-purple);
        }
        
        .btn-outline-purple:hover {
            background-color: var(--bs-purple);
            color: white;
        }
        
        .text-purple {
            color: var(--bs-purple) !important;
        }
        
        .card {
            border: 1px solid rgba(106, 13, 173, 0.3);
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .form-control, .form-control:focus {
            background-color: rgba(26, 26, 46, 0.7);
            color: white;
            border-color: var(--bs-purple);
        }
        
        main {
            flex: 1;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-purple">
        <div class="container">
            <a class="navbar-brand" href="<?= $baseUrl ?>/">WebExam</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $baseUrl ?>/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $baseUrl ?>/posts">My Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $baseUrl ?>/logout">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $baseUrl ?>/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= $baseUrl ?>/register">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <main class="container py-4">