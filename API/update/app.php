<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Update App API
   Updates an existing app's details
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

// Only allow PUT/PATCH requests
requireMethod('PUT,PATCH');

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    errorResponse('Unauthorized', 401);
}

$userId = $_SESSION['user_id'];

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    errorResponse('Invalid JSON input', 400);
}

// Validate app_id
$appId = $input['app_id'] ?? null;
if (!$appId) {
    errorResponse('App ID is required', 400);
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Verify app belongs to user
    $stmt = $db->prepare("SELECT id, slug FROM apps WHERE id = :id AND user_id = :user_id");
    $stmt->execute(['id' => $appId, 'user_id' => $userId]);
    $app = $stmt->fetch();
    
    if (!$app) {
        errorResponse('App not found', 404);
    }
    
    // Build update query dynamically
    $updateFields = [];
    $params = ['id' => $appId];
    
    // Name (required if provided)
    if (isset($input['name'])) {
        $name = sanitizeInput($input['name']);
        if (empty($name)) {
            errorResponse('App name cannot be empty', 400);
        }
        if (strlen($name) > 255) {
            errorResponse('App name is too long (max 255 characters)', 400);
        }
        $updateFields[] = 'name = :name';
        $params['name'] = $name;
    }
    
    // Description
    if (array_key_exists('description', $input)) {
        $description = $input['description'] ? sanitizeInput($input['description']) : null;
        $updateFields[] = 'description = :description';
        $params['description'] = $description;
    }
    
    // Color
    if (isset($input['color'])) {
        $color = sanitizeInput($input['color']);
        // Validate hex color
        if (!preg_match('/^#[a-fA-F0-9]{6}$/', $color)) {
            errorResponse('Invalid color format (must be hex like #FF5500)', 400);
        }
        $updateFields[] = 'color = :color';
        $params['color'] = $color;
    }
    
    // Active status
    if (isset($input['is_active'])) {
        $updateFields[] = 'is_active = :is_active';
        $params['is_active'] = $input['is_active'] ? 1 : 0;
    }
    
    if (empty($updateFields)) {
        errorResponse('No fields to update', 400);
    }
    
    // Execute update
    $updateQuery = "UPDATE apps SET " . implode(', ', $updateFields) . ", updated_at = NOW() WHERE id = :id";
    $stmt = $db->prepare($updateQuery);
    $stmt->execute($params);
    
    // Fetch updated app
    $stmt = $db->prepare("
        SELECT 
            id,
            name,
            slug,
            description,
            color,
            is_active,
            notification_count,
            last_notification_at,
            created_at,
            updated_at
        FROM apps
        WHERE id = :id
    ");
    $stmt->execute(['id' => $appId]);
    $updatedApp = $stmt->fetch();
    
    $updatedApp['is_active'] = (bool) $updatedApp['is_active'];
    $updatedApp['notification_count'] = (int) $updatedApp['notification_count'];
    
    successResponse('App updated successfully', [
        'app' => $updatedApp
    ]);
    
} catch (PDOException $e) {
    error_log("Update app error: " . $e->getMessage());
    errorResponse('Failed to update app', 500);
} catch (Exception $e) {
    error_log("Update app error: " . $e->getMessage());
    errorResponse('An unexpected error occurred', 500);
}
