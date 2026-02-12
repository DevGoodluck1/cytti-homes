<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'cytti_homes');
define('DB_USER', 'root');
define('DB_PASS', '');

// Site configuration
define('SITE_NAME', 'Cytti Homes');
define('SITE_URL', 'http://localhost/Cytti');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS

session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
