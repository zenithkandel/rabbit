<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Create App API
   Creates a new app for the authenticated user
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

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    errorResponse('Invalid JSON input', 400);
}

// Validate required fields
$name = isset($input['name']) ? sanitizeInput($input['name']) : null;
$slug = isset($input['slug']) ? sanitizeInput($input['slug']) : null;

if (!$name) {
    errorResponse('App name is required', 400);
}

if (strlen($name) > 255) {
    errorResponse('App name is too long (max 255 characters)', 400);
}

if (!$slug) {
    errorResponse('App identifier (slug) is required', 400);
}

// Validate slug format (lowercase, alphanumeric with hyphens)
$slug = strtolower(trim($slug));
if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
    errorResponse('Invalid identifier format. Use lowercase letters, numbers, and hyphens only.', 400);
}

if (strlen($slug) > 100) {
    errorResponse('App identifier is too long (max 100 characters)', 400);
}

// Optional fields
$description = isset($input['description']) ? sanitizeInput($input['description']) : null;
$color = isset($input['color']) ? sanitizeInput($input['color']) : '#5C6B55';

// Validate color format
if (!preg_match('/^#[a-fA-F0-9]{6}$/', $color)) {
    errorResponse('Invalid color format (must be hex like #FF5500)', 400);
}

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if slug already exists for this user
    $stmt = $db->prepare("SELECT id FROM apps WHERE user_id = :user_id AND slug = :slug");
    $stmt->execute(['user_id' => $userId, 'slug' => $slug]);
    
    if ($stmt->fetch()) {
        errorResponse('An app with this identifier already exists', 409);
    }
    
    // Generate UUID for new app
    $appId = generateUUID();
    
    // Insert new app
    $stmt = $db->prepare("
        INSERT INTO apps (id, user_id, name, slug, description, color, is_active, notification_count, created_at, updated_at)
        VALUES (:id, :user_id, :name, :slug, :description, :color, 1, 0, NOW(), NOW())
    ");
    
    $stmt->execute([
        'id' => $appId,
        'user_id' => $userId,
        'name' => $name,
        'slug' => $slug,
        'description' => $description,
        'color' => $color
    ]);
    
    // Fetch the created app
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
    $newApp = $stmt->fetch();
    
    $newApp['is_active'] = (bool) $newApp['is_active'];
    $newApp['notification_count'] = (int) $newApp['notification_count'];
    
    successResponse('App created successfully', [
        'app' => $newApp
    ], 201);
    
} catch (PDOException $e) {
    error_log("Create app error: " . $e->getMessage());
    errorResponse('Failed to create app', 500);
} catch (Exception $e) {
    error_log("Create app error: " . $e->getMessage());
    errorResponse('An unexpected error occurred', 500);
}

/**
 * Generate UUID v4
 */
function generateUUID(): string {
    $data = random_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
