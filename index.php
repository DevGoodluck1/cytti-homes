<?php
/**
 * Index page - redirects to properties listing
 * This is the main entry point for the website
 */

// Start session
session_start();

// Redirect to properties listing page
header("Location: properties.html");
exit;
?>
