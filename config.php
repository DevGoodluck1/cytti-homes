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
// SESSION FIX FOR RENDER / CLOUD DEPLOYMENT
// ============================================================
// For Render and cloud platforms, use database-backed sessions or proper file handling
$session_save_path = session_save_path();

// Try to use /tmp or render's ephemeral disk
if (empty($session_save_path) || !is_dir($session_save_path) || !is_writable($session_save_path)) {
    // Use system temp directory
    $session_save_path = sys_get_temp_dir();
    if (!is_dir($session_save_path) || !is_writable($session_save_path)) {
        // Last resort: try creating a sessions directory
        $session_dir = __DIR__ . '/sessions';
        if (!is_dir($session_dir)) {
            @mkdir($session_dir, 0755, true);
        }
        if (is_dir($session_dir) && is_writable($session_dir)) {
            $session_save_path = $session_dir;
        }
    }
}

ini_set('session.save_handler', 'files');
ini_set('session.save_path', $session_save_path);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
// Use Lax for cross-domain redirects
ini_set('session.cookie_samesite', 'Lax');
// Increase session cookie lifetime
ini_set('session.cookie_lifetime', 86400); // 24 hours
ini_set('session.gc_maxlifetime', 86400);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database credentials are now handled via environment variables in db_connect.php
// For Docker/Render deployment, set these environment variables:
// DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT

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
