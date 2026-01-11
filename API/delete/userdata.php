<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Reset User Data API
   Deletes all apps and notifications but keeps the account
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
if (strtoupper($confirmation) !== 'RESET') {
    errorResponse('Please type RESET to confirm data reset', 400, [
        'confirmation' => 'Confirmation phrase is required'
    ]);
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Delete all apps for this user (notifications cascade automatically)
    $stmt = $db->prepare("DELETE FROM apps WHERE user_id = :user_id");
    $stmt->execute(['user_id' => $userId]);
    
    $deletedApps = $stmt->rowCount();
    
    successResponse('All data has been reset', [
        'deleted_apps' => $deletedApps,
        'message' => 'All your apps and notifications have been deleted.'
    ]);
    
} catch (PDOException $e) {
    error_log("Reset data error: " . $e->getMessage());
    errorResponse('Failed to reset data', 500);
} catch (Exception $e) {
    error_log("Reset data error: " . $e->getMessage());
    errorResponse('An unexpected error occurred', 500);
}
