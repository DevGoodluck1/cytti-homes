<?php
/**
 * Configuration file for InfinityFree hosting
 * Fixed credentials - no environment variables needed
 * 
 * IMPORTANT: This file should be included at the TOP of any PHP file
 * that needs session or database access.
 */

// Start output buffering to prevent "headers already sent" errors
ob_start();

// Enable error reporting for debugging (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Set timezone
date_default_timezone_set('Africa/Nairobi');

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
