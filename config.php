<?php
// Database configuration
define('DB_HOST', 'bno1flmhq62cjqyalvgw-mysql.services.clever-cloud.com');
define('DB_NAME', 'bno1flmhq62cjqyalvgw');
define('DB_USER', 'uk4w2pl3ojqam4ea');
define('DB_PASS', 'WTJZIXFNl8rK5hSbsxup');
define('DB_PORT', 3306);


// Site configuration
define('SITE_NAME', 'Cytti Homes');
define('SITE_URL', 'https://pamsimas.selur.my.id');

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0);

session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
