<?php
require_once 'config.php';
require_once 'db_connect.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: signup.php");
    exit;
}

$errors = [];

// Sanitize and validate inputs
$username = sanitizeInput($_POST['username'] ?? '');
$email = sanitizeInput($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$terms = isset($_POST['terms']);

// Username validation
if (empty($username)) {
    $errors['username'] = 'Username is required';
} elseif (strlen($username) < 3) {
    $errors['username'] = 'Username must be at least 3 characters long';
} elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
    $errors['username'] = 'Username can only contain letters, numbers, hyphens, and underscores';
}

// Email validation
if (empty($email)) {
    $errors['email'] = 'Email is required';
} elseif (!validateEmail($email)) {
    $errors['email'] = 'Please enter a valid email address';
}

// Password validation
if (empty($password)) {
    $errors['password'] = 'Password is required';
} elseif (strlen($password) < 8) {
    $errors['password'] = 'Password must be at least 8 characters long';
}

// Confirm password validation
if ($password !== $confirm_password) {
    $errors['confirm_password'] = 'Passwords do not match';
}

// Terms validation
if (!$terms) {
    $errors['terms'] = 'You must agree to the Terms of Service';
}

// If no errors, check duplicate username/email
if (empty($errors)) {
    try {
        $existingUser = Database::getInstance()->fetchOne(
            "SELECT id, email, username FROM users WHERE email = ? OR username = ?",
            [$email, $username]
        );

        if ($existingUser) {
            if ($existingUser['email'] === $email) {
                $errors['email'] = 'An account with this email already exists';
            }
            if ($existingUser['username'] === $username) {
                $errors['username'] = 'This username is already taken';
            }
        }

    } catch (Exception $e) {
        error_log("Signup duplicate check error: " . $e->getMessage());
        $errors['general'] = 'Database error occurred. Please try again.';
    }
}

// If errors exist, redirect back
if (!empty($errors)) {
    $_SESSION['signup_errors'] = $errors;
    $_SESSION['signup_data'] = $_POST;
    header("Location: signup.php");
    exit;
}

// Insert user into database
try {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $userId = Database::getInstance()->insert('users', [
        'username' => $username,
        'email' => $email,
        'password' => $hashedPassword
    ]);

    if (!$userId) {
        throw new Exception("Insert failed: userId not returned.");
    }

    // Log user in
    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;

    // Redirect to dashboard
    header("Location: dashboard.php");
    exit;

} catch (Exception $e) {
    error_log("Registration error: " . $e->getMessage());

    $_SESSION['signup_errors'] = [
        'general' => 'Registration failed. Please try again.'
    ];
    $_SESSION['signup_data'] = $_POST;

    header("Location: signup.php");
    exit;
}
?>
