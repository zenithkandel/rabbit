<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Get API Key
   Returns the user's actual API key (requires re-generation if lost)
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
    
    // Check if user has an API key stored in session (only available right after generation)
    // Note: We store the plain API key temporarily in session after generation
    // This is the only time we can show it
    
    if (isset($_SESSION['api_key_plain'])) {
        $apiKey = $_SESSION['api_key_plain'];
        $masked = maskApiKey($apiKey);
        
        successResponse('API key retrieved', [
            'api_key' => $apiKey,
            'masked' => $masked,
            'is_new' => true
        ]);
    }
    
    // If no plain key in session, return masked version only
    $stmt = $db->prepare("SELECT api_key_hash FROM users WHERE id = :id LIMIT 1");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch();
    
    if (!$user || empty($user['api_key_hash'])) {
        successResponse('No API key found', [
            'api_key' => null,
            'masked' => null,
            'is_new' => false
        ]);
    }
    
    // We can't reverse the hash, so return a placeholder
    successResponse('API key exists but cannot be displayed', [
        'api_key' => null,
        'masked' => 'rb_••••••••••••••••••••',
        'is_new' => false,
        'note' => 'API key is hashed and cannot be retrieved. Regenerate if needed.'
    ]);
    
} catch (PDOException $e) {
    error_log("Get API key error: " . $e->getMessage());
    errorResponse('Failed to retrieve API key', 500);
} catch (Exception $e) {
    error_log("Get API key error: " . $e->getMessage());
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
