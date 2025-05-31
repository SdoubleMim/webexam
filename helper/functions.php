<?php
/**
 * Helper Functions for WebExam Project
 * Place this file in: `app/helper/functions.php`
 */

// ------ Core System Functions ------

/**
 * Render a view with optional data
 */
function view(string $viewPath, array $data = []) {
    // آرایه داده‌ها را به متغیرهای جداگانه تبدیل می‌کند
    extract($data);
    
    // فایل view را include می‌کند (با پسوند php و مسیر کامل)
    include ROOT_PATH . "/views/{$viewPath}.php";
}


/**
 * Abort with HTTP status code
 */
function abort($code, $message = '')
{
    http_response_code($code);
    require "views/404.php"; // می‌تونی فایل خاص برای 403، 500 و غیره هم بسازی
    die;
}


/**
 * Redirect to URL
 */
function redirect(string $url, int $statusCode = 303): void {
    header('Location: ' . $url, true, $statusCode);
    exit();
}

/**
 * Check authentication status
 */
function is_logged_in(): bool {
    return !empty($_SESSION['user_id']);
}

/**
 * Get old form input value
 */
function old(string $key, string $default = ''): string {
    return htmlspecialchars($_POST[$key] ?? $default);
}

/**
 * Get database connection
 */
function db(): \Illuminate\Database\Connection {
    global $capsule;
    return $capsule->getConnection();
}