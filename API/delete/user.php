<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Delete User Account API
   Permanently deletes user account and all associated data
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

// Only allow DELETE or POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    errorResponse('Method not allowed', 405);
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    errorResponse('Unauthorized', 401);
}

$userId = $_SESSION['user_id'];

// Get request data for confirmation
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (strpos($contentType, 'application/json') !== false) {
    $data = getJsonBody();
} else {
    $data = $_POST;
}

// Require confirmation phrase
$confirmation = $data['confirmation'] ?? '';
if (strtoupper($confirmation) !== 'DELETE') {
    errorResponse('Please type DELETE to confirm account deletion', 400, [
        'confirmation' => 'Confirmation phrase is required'
    ]);
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Delete user (cascades to apps and notifications due to FK constraints)
    $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    
    if ($stmt->rowCount() === 0) {
        errorResponse('User not found', 404);
    }
    
    // Destroy session
    session_unset();
    session_destroy();
    
    successResponse('Account deleted successfully', [
        'redirect' => '/rabbit/index.php'
    ]);
    
} catch (PDOException $e) {
    error_log("Delete user error: " . $e->getMessage());
    errorResponse('Failed to delete account', 500);
} catch (Exception $e) {
    error_log("Delete user error: " . $e->getMessage());
    errorResponse('An unexpected error occurred', 500);
}
