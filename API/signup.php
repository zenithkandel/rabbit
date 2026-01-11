<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Signup API Endpoint
   Creates a new user account
   ═══════════════════════════════════════════════════════════════════════════ */

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

// Name validation
$name = isset($data['name']) ? sanitizeInput($data['name']) : '';
if (empty($name)) {
    $errors['name'] = 'Name is required';
} elseif (strlen($name) < 2) {
    $errors['name'] = 'Name must be at least 2 characters';
} elseif (strlen($name) > 255) {
    $errors['name'] = 'Name must be less than 255 characters';
}

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
} else {
    $passwordErrors = isValidPassword($password);
    if (!empty($passwordErrors)) {
        $errors['password'] = $passwordErrors[0];
    }
}

// Confirm password validation
$confirmPassword = $data['confirm_password'] ?? '';
if (empty($confirmPassword)) {
    $errors['confirm_password'] = 'Please confirm your password';
} elseif ($password !== $confirmPassword) {
    $errors['confirm_password'] = 'Passwords do not match';
}

// Return validation errors if any
if (!empty($errors)) {
    errorResponse('Please fix the errors below', 422, $errors);
}

// ─────────────────────────────────────────────────────────────────
// Process Signup
// ─────────────────────────────────────────────────────────────────

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if email already exists
    $stmt = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    
    if ($stmt->fetch()) {
        errorResponse('An account with this email already exists', 409, [
            'email' => 'This email is already registered'
        ]);
    }
    
    // Generate user data (no API key on signup - user generates it later)
    $userId = generateUUID();
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert new user without API key
    $stmt = $db->prepare("
        INSERT INTO users (id, email, name, password_hash, api_key_hash, created_at, updated_at)
        VALUES (:id, :email, :name, :password_hash, NULL, NOW(), NOW())
    ");
    
    $stmt->execute([
        'id' => $userId,
        'email' => $email,
        'name' => $name,
        'password_hash' => $passwordHash
    ]);
    
    // Return success response (no API key - user must generate from Settings)
    successResponse('Account created successfully', [
        'user' => [
            'id' => $userId,
            'name' => $name,
            'email' => $email
        ],
        'note' => 'Welcome! Generate your API key from Settings to start integrating.'
    ], 201);
    
} catch (PDOException $e) {
    error_log("Signup error: " . $e->getMessage());
    errorResponse('An error occurred while creating your account. Please try again.', 500);
} catch (Exception $e) {
    error_log("Signup error: " . $e->getMessage());
    errorResponse('An unexpected error occurred. Please try again.', 500);
}
