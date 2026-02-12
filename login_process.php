<?php
require_once 'config.php';
require_once 'db_connect.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Sanitize and validate inputs
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validation
    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!validateEmail($email)) {
        $errors['email'] = 'Please enter a valid email address';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    }

    // If validation errors, redirect back
    if (!empty($errors)) {
        $error_string = http_build_query($errors);
        header("Location: login.php?errors=1&$error_string");
        exit;
    }

    // Get user from database
    try {
        $user = Database::getInstance()->fetchOne(
            "SELECT id, username, email, password FROM users WHERE email = ?",
            [$email]
        );

        if (!$user || !verifyPassword($password, $user['password'])) {
            $errors['general'] = 'Invalid email or password';
            $error_string = http_build_query($errors);
            header("Location: login.php?errors=1&$error_string");
            exit;
        }

        // Start session
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];

        // Redirect to dashboard
        header('Location: dashboard.php');
        exit;

    } catch (Exception $e) {
        $errors['general'] = 'Login failed. Please try again.';
        $error_string = http_build_query($errors);
        header("Location: login.php?errors=1&$error_string");
        exit;
    }
} else {
    // Not a POST request
    header('Location: login.php');
    exit;
}
?>
