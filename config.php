<?php

// Get Clever Cloud database environment variables
$host = getenv("MYSQL_ADDON_HOST");
$user = getenv("MYSQL_ADDON_USER");
$pass = getenv("MYSQL_ADDON_PASSWORD");
$db   = getenv("MYSQL_ADDON_DB");
$port = getenv("MYSQL_ADDON_PORT");

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
