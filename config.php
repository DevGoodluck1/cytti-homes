<?php
/**
 * Configuration file for InfinityFree hosting
 * Fixed credentials - no environment variables needed
 */

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

// Create mysqli connection (for backward compatibility)
$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
