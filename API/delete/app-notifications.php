<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Clear App Notifications API
   Deletes all notifications for a specific app
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

// Only allow DELETE requests
requireMethod('DELETE');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    errorResponse('Unauthorized', 401);
}

$userId = $_SESSION['user_id'];

// Get app_id from query string or JSON body
$appId = $_GET['app_id'] ?? null;

if (!$appId) {
    // Try JSON body
    $input = json_decode(file_get_contents('php://input'), true);
    $appId = $input['app_id'] ?? null;
}

if (!$appId) {
    errorResponse('App ID is required', 400);
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Verify app belongs to user
    $stmt = $db->prepare("SELECT id, name FROM apps WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $appId, 'user_id' => $userId]);
    $app = $stmt->fetch();
    
    if (!$app) {
        errorResponse('App not found', 404);
    }
    
    // Delete all notifications for this app
    $stmt = $db->prepare("DELETE FROM notifications WHERE app_id = :app_id");
    $stmt->execute(['app_id' => $appId]);
    $deletedCount = $stmt->rowCount();
    
    // Reset notification count on the app
    $stmt = $db->prepare("UPDATE apps SET notification_count = 0, updated_at = NOW() WHERE id = :id");
    $stmt->execute(['id' => $appId]);
    
    successResponse('Notifications cleared successfully', [
        'app_name' => $app['name'],
        'deleted_count' => $deletedCount
    ]);
    
} catch (PDOException $e) {
    error_log("Clear notifications error: " . $e->getMessage());
    errorResponse('Failed to clear notifications', 500);
} catch (Exception $e) {
    error_log("Clear notifications error: " . $e->getMessage());
    errorResponse('An unexpected error occurred', 500);
}
