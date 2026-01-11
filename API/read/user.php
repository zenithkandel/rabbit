<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Get User Profile API
   Returns the current user's profile information
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

// Only allow GET requests
requireMethod('GET');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    errorResponse('Unauthorized', 401);
}

$userId = $_SESSION['user_id'];

try {
    $db = Database::getInstance()->getConnection();
    
    // Get user profile with API key (masked)
    $stmt = $db->prepare("
        SELECT id, email, name, api_key_hash, created_at, updated_at
        FROM users 
        WHERE id = :id 
        LIMIT 1
    ");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch();
    
    if (!$user) {
        errorResponse('User not found', 404);
    }
    
    // Get user stats
    $statsStmt = $db->prepare("
        SELECT 
            (SELECT COUNT(*) FROM apps WHERE user_id = :user_id) as app_count,
            (SELECT COUNT(*) FROM notifications n 
             INNER JOIN apps a ON n.app_id = a.id 
             WHERE a.user_id = :user_id2) as notification_count,
            (SELECT COUNT(*) FROM notifications n 
             INNER JOIN apps a ON n.app_id = a.id 
             WHERE a.user_id = :user_id3 AND n.is_read = 0) as unread_count
    ");
    $statsStmt->execute([
        'user_id' => $userId,
        'user_id2' => $userId,
        'user_id3' => $userId
    ]);
    $stats = $statsStmt->fetch();
    
    successResponse('User profile retrieved', [
        'user' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'has_api_key' => !empty($user['api_key_hash']),
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at']
        ],
        'stats' => [
            'apps' => (int)$stats['app_count'],
            'notifications' => (int)$stats['notification_count'],
            'unread' => (int)$stats['unread_count']
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Get user error: " . $e->getMessage());
    errorResponse('Failed to retrieve user profile', 500);
} catch (Exception $e) {
    error_log("Get user error: " . $e->getMessage());
    errorResponse('An unexpected error occurred', 500);
}
