<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Check API Key Status
   Returns whether the user has an API key configured (not the key itself)
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
    
    // Check if user has an API key
    $stmt = $db->prepare("SELECT api_key_hash FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch();
    
    $hasApiKey = $user && !empty($user['api_key_hash']);
    
    successResponse('API key status retrieved', [
        'has_api_key' => $hasApiKey,
        'note' => $hasApiKey 
            ? 'API key is configured. It can only be viewed when generated.' 
            : 'No API key. Generate one from Settings.'
    ]);
    
} catch (PDOException $e) {
    error_log("Get API key status error: " . $e->getMessage());
    errorResponse('Failed to check API key status', 500);
} catch (Exception $e) {
    error_log("Get API key status error: " . $e->getMessage());
    errorResponse('An unexpected error occurred', 500);
}
