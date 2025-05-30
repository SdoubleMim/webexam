<?php
/**
 * Helper Functions for WebExam Project
 * Place this file in: `app/helper/functions.php`
 */

// ------ توابع اصلی سیستم ------

/**
 * Render a view with optional data
 * @param string $path (e.g., 'home', 'users/profile')
 * @param array $data (optional) Data to pass to the view
 * @throws Exception if view not found
 */
function view(string $path, array $data = []): void {
    $fullPath = ROOT_PATH . '/views/' . ltrim($path, '/') . '.php';
    
    if (!file_exists($fullPath)) {
        throw new Exception("View file not found: {$fullPath}");
    }

    extract($data, EXTR_SKIP); // Convert array keys to variables
    require_once $fullPath;
}

/**
 * Redirect to a URL
 * @param string $url (e.g., '/users', '/login')
 * @param int $statusCode (HTTP status code, default: 303)
 */
function redirect(string $url, int $statusCode = 303): void {
    header('Location: ' . $url, true, $statusCode);
    die();
}

/**
 * Check if user is logged in (requires session_start())
 */
function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}



/**
 * Get database connection (for raw queries)
 */
function db(): \Illuminate\Database\Connection {
    global $capsule;
    return $capsule->getConnection();
}