<?php

// Clever Cloud MySQL env variables
$host = $_ENV["MYSQL_ADDON_HOST"] ?? getenv("MYSQL_ADDON_HOST");
$user = $_ENV["MYSQL_ADDON_USER"] ?? getenv("MYSQL_ADDON_USER");
$pass = $_ENV["MYSQL_ADDON_PASSWORD"] ?? getenv("MYSQL_ADDON_PASSWORD");
$db   = $_ENV["MYSQL_ADDON_DB"] ?? getenv("MYSQL_ADDON_DB");
$port = $_ENV["MYSQL_ADDON_PORT"] ?? getenv("MYSQL_ADDON_PORT");

// Fallback default port
if (!$port) {
    $port = 3306;
}

// Stop if missing
if (!$host || !$user || !$db) {
    die("Database variables missing. Check Clever Cloud env vars.");
}

// Define constants for your PDO Database class
define("DB_HOST", $host);
define("DB_USER", $user);
define("DB_PASS", $pass);
define("DB_NAME", $db);
define("DB_PORT", $port);

// Create mysqli connection (if your old code uses $conn)
$conn = new mysqli($host, $user, $pass, $db, (int)$port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
