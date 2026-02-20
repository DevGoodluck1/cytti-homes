<?php
/**
 * Configuration file for InfinityFree hosting
 * Fixed credentials - no environment variables needed
 * 
 * IMPORTANT: This file should be included at the TOP of any PHP file
 * that needs session or database access.
 */

// CRITICAL: Remove any output buffering first
while (ob_get_level()) {
    ob_end_clean();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);

// Set timezone
date_default_timezone_set('Africa/Nairobi');

// ============================================================
// SESSION FIX FOR INFINITYFREE
// ============================================================
// Set proper session save path for InfinityFree
$session_save_path = '/tmp';
if (!is_dir($session_save_path) || !is_writable($session_save_path)) {
    // Try alternative paths
    $alt_paths = [
        sys_get_temp_dir(),
        __DIR__ . '/sessions'
    ];
    foreach ($alt_paths as $path) {
        if (is_dir($path) && is_writable($path)) {
            $session_save_path = $path;
            break;
        }
    }
}

ini_set('session.save_handler', 'files');
ini_set('session.save_path', $session_save_path);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// InfinityFree Database Credentials
$host = "sql213.infinityfree.com";
$user = "if0_41198744";
$pass = "PD84JL9Doz";
$db   = "if0_41198744_cytti";
$port = 3306;

// Define constants for db_connect.php (PDO)
define('DB_HOST', $host);
define('DB_USER', $user);
define('DB_PASS', $pass);
define('DB_NAME', $db);
define('DB_PORT', $port);

// Database connection is now handled by db_connect.php
// DO NOT create a global $conn here - let the Database class handle it

// Debug function to log errors
function logDebug($message, $data = null) {
    $logMessage = date('Y-m-d H:i:s') . " - " . $message;
    if ($data !== null) {
        $logMessage .= " - " . print_r($data, true);
    }
    error_log($logMessage);
}

// Check if we're in debug mode
define('DEBUG_MODE', true);
?>
