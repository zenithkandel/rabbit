<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Signin API Endpoint
   Authenticates user and creates session
   ═══════════════════════════════════════════════════════════════════════════ */

// Start session at the very beginning
session_start();

// Define API constant for included files
define('RABBIT_API', true);

// Include dependencies
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/helpers.php';

// Set CORS headers
setCorsHeaders();

// Only allow POST requests
requireMethod('POST');

// Get request data (support both JSON and form data)
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';

if (strpos($contentType, 'application/json') !== false) {
    $data = getJsonBody();
} else {
    $data = $_POST;
}

// ─────────────────────────────────────────────────────────────────
// Validate Input
// ─────────────────────────────────────────────────────────────────

$errors = [];

// Email validation
$email = isset($data['email']) ? strtolower(trim($data['email'])) : '';
if (empty($email)) {
    $errors['email'] = 'Email is required';
} elseif (!isValidEmail($email)) {
    $errors['email'] = 'Please enter a valid email address';
}

// Password validation
$password = $data['password'] ?? '';
if (empty($password)) {
    $errors['password'] = 'Password is required';
}

// Return validation errors if any
if (!empty($errors)) {
    errorResponse('Please fix the errors below', 422, $errors);
}

// ─────────────────────────────────────────────────────────────────
// Process Signin
// ─────────────────────────────────────────────────────────────────

try {
    $db = Database::getInstance()->getConnection();
    
    // Find user by email
    $stmt = $db->prepare("
        SELECT id, email, name, password_hash, api_key_hash 
        FROM users 
        WHERE email = :email 
        LIMIT 1
    ");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
    
    // Check if user exists
    if (!$user) {
        errorResponse('Invalid email or password', 401, [
            'email' => 'No account found with this email'
        ]);
    }
    
    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        errorResponse('Invalid email or password', 401, [
            'password' => 'Incorrect password'
        ]);
    }
    
    // Regenerate session ID for security
    session_regenerate_id(true);
    
    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['logged_in'] = true;
    $_SESSION['login_time'] = time();
    
    // Return success response
    successResponse('Signed in successfully', [
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ],
        'redirect' => '/rabbit/dashboard/'
    ]);
    
} catch (PDOException $e) {
    error_log("Signin error: " . $e->getMessage());
    errorResponse('An error occurred while signing in. Please try again.', 500);
} catch (Exception $e) {
    error_log("Signin error: " . $e->getMessage());
    errorResponse('An unexpected error occurred. Please try again.', 500);
}
