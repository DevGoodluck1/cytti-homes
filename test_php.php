<?php
/**
 * Simple PHP Test File for Clever Cloud
 * This file tests if PHP is working correctly on the server
 */

// Display PHP version
echo "PHP Version: " . phpversion() . "\n";

// Display loaded extensions
echo "\nLoaded Extensions:\n";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $ext) {
    echo "- " . $ext . "\n";
}

// Check required extensions
echo "\nRequired Extensions Check:\n";
$required_extensions = ['mysqli', 'pdo', 'pdo_mysql', 'mbstring', 'json', 'curl'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "[OK] $ext is loaded\n";
    } else {
        echo "[FAIL] $ext is NOT loaded\n";
    }
}

// Display server info
echo "\nServer Information:\n";
echo "Server API: " . php_sapi_name() . "\n";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";

// Display environment variables (if set)
echo "\nClever Cloud Environment Variables:\n";
$env_vars = ['MYSQL_ADDON_HOST', 'MYSQL_ADDON_USER', 'MYSQL_ADDON_DB', 'MYSQL_ADDON_PORT'];
foreach ($env_vars as $var) {
    $value = getenv($var);
    if ($value) {
        echo "$var = $value\n";
    } else {
        echo "$var = NOT SET\n";
    }
}

echo "\nSITE WORKING\n";
?>
