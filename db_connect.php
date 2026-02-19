<?php
$host = "bno1flmhq62cjqyalvgw-mysql.services.clever-cloud.com";
$user = "uk4w2pl3ojqam4ea";
$pass = "WTJZIXFNl8rK5hSbsxup";
$dbname = "bno1flmhq62cjqyalvgw";
$port = 3306;

$conn = mysqli_connect($host, $user, $pass, $dbname, $port);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
