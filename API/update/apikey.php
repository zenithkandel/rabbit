<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Regenerate API Key
   Generates a new API key for the user (invalidates old key)
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

// Only allow POST requests
requireMethod('POST');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    errorResponse('Unauthorized', 401);
}

$userId = $_SESSION['user_id'];

try {
    $db = Database::getInstance()->getConnection();
    
    // Generate new API key
    $newApiKey = generateApiKey();
    $apiKeyHash = hashApiKey($newApiKey);
    
    // Update user's API key
    $stmt = $db->prepare("
        UPDATE users 
        SET api_key_hash = :api_key_hash, updated_at = NOW() 
        WHERE id = :id
    ");
    $stmt->execute([
        'api_key_hash' => $apiKeyHash,
        'id' => $userId
    ]);
    
    // Store plain API key in session temporarily (for display)
    $_SESSION['api_key_plain'] = $newApiKey;
    
    // Return the new API key (only shown once!)
    successResponse('API key regenerated successfully', [
        'api_key' => $newApiKey,
        'masked' => maskApiKey($newApiKey),
        'note' => 'Save your API key securely. It will only be shown once.'
    ]);
    
} catch (PDOException $e) {
    error_log("Regenerate API key error: " . $e->getMessage());
    errorResponse('Failed to regenerate API key', 500);
} catch (Exception $e) {
    error_log("Regenerate API key error: " . $e->getMessage());
    errorResponse('An unexpected error occurred', 500);
}

/**
 * Mask API key for display
 */
function maskApiKey(string $key): string {
    if (strlen($key) <= 10) {
        return $key;
    }
    $prefix = substr($key, 0, 6);
    $suffix = substr($key, -4);
    return $prefix . '••••••••••••' . $suffix;
}
