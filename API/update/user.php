<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Update User Profile API
   Updates the current user's profile information
   ═══════════════════════════════════════════════════════════════════════════ */

// Start session
session_start();

// Define API constant for included files
define('RABBIT_API', true);

// Include dependencies
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/helpers.php';

// Set CORS headers
setCorsHeaders();

// Only allow POST/PUT requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'PUT') {
    errorResponse('Method not allowed', 405);
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    errorResponse('Unauthorized', 401);
}

$userId = $_SESSION['user_id'];

// Get request data
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
$updates = [];

// Name validation (optional)
if (isset($data['name'])) {
    $name = sanitizeInput($data['name']);
    if (empty($name)) {
        $errors['name'] = 'Name cannot be empty';
    } elseif (strlen($name) < 2) {
        $errors['name'] = 'Name must be at least 2 characters';
    } elseif (strlen($name) > 255) {
        $errors['name'] = 'Name must be less than 255 characters';
    } else {
        $updates['name'] = $name;
    }
}

// Email validation (optional)
if (isset($data['email'])) {
    $email = strtolower(trim($data['email']));
    if (empty($email)) {
        $errors['email'] = 'Email cannot be empty';
    } elseif (!isValidEmail($email)) {
        $errors['email'] = 'Please enter a valid email address';
    } else {
        $updates['email'] = $email;
    }
}

// Return validation errors if any
if (!empty($errors)) {
    errorResponse('Please fix the errors below', 422, $errors);
}

// Check if there's anything to update
if (empty($updates)) {
    errorResponse('No fields to update', 400);
}

// ─────────────────────────────────────────────────────────────────
// Process Update
// ─────────────────────────────────────────────────────────────────

try {
    $db = Database::getInstance()->getConnection();
    
    // If email is being changed, check for duplicates
    if (isset($updates['email'])) {
        $stmt = $db->prepare("SELECT id FROM users WHERE email = :email AND id != :id LIMIT 1");
        $stmt->execute(['email' => $updates['email'], 'id' => $userId]);
        
        if ($stmt->fetch()) {
            errorResponse('Email already in use', 409, [
                'email' => 'This email is already registered to another account'
            ]);
        }
    }
    
    // Build update query
    $setParts = [];
    $params = ['id' => $userId];
    
    foreach ($updates as $field => $value) {
        $setParts[] = "`$field` = :$field";
        $params[$field] = $value;
    }
    
    $sql = "UPDATE users SET " . implode(', ', $setParts) . ", updated_at = NOW() WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    
    // Update session if name or email changed
    if (isset($updates['name'])) {
        $_SESSION['user_name'] = $updates['name'];
    }
    if (isset($updates['email'])) {
        $_SESSION['user_email'] = $updates['email'];
    }
    
    // Return updated user data
    successResponse('Profile updated successfully', [
        'user' => [
            'id' => $userId,
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email']
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Update user error: " . $e->getMessage());
    errorResponse('Failed to update profile', 500);
} catch (Exception $e) {
    error_log("Update user error: " . $e->getMessage());
    errorResponse('An unexpected error occurred', 500);
}
