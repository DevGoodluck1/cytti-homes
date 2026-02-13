<?php

$host = getenv("MYSQL_ADDON_HOST");
$user = getenv("MYSQL_ADDON_USER");
$pass = getenv("MYSQL_ADDON_PASSWORD");
$db   = getenv("MYSQL_ADDON_DB");
$port = getenv("MYSQL_ADDON_PORT");

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
