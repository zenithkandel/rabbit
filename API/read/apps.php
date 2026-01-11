<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Get Apps API
   Returns user's connected apps
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

// Get query parameters
$limit = isset($_GET['limit']) ? min((int)$_GET['limit'], 50) : 20;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$activeOnly = isset($_GET['active']) && $_GET['active'] === '1';

try {
    $db = Database::getInstance()->getConnection();
    
    // Build query
    $whereConditions = ['user_id = :user_id'];
    $params = ['user_id' => $userId];
    
    if ($activeOnly) {
        $whereConditions[] = 'is_active = 1';
    }
    
    $whereClause = implode(' AND ', $whereConditions);
    
    // Get apps
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
        WHERE {$whereClause}
        ORDER BY last_notification_at DESC, created_at DESC
        LIMIT :limit OFFSET :offset
    ");
    
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => $value) {
        $stmt->bindValue(":{$key}", $value);
    }
    
    $stmt->execute();
    $apps = $stmt->fetchAll();
    
    // Format data
    foreach ($apps as &$app) {
        $app['is_active'] = (bool) $app['is_active'];
        $app['notification_count'] = (int) $app['notification_count'];
        if ($app['last_notification_at']) {
            $app['last_notification_relative'] = getRelativeTime($app['last_notification_at']);
        }
    }
    
    // Get total count
    $stmt = $db->prepare("
        SELECT COUNT(id) as total
        FROM apps
        WHERE {$whereClause}
    ");
    foreach ($params as $key => $value) {
        $stmt->bindValue(":{$key}", $value);
    }
    $stmt->execute();
    $total = (int) $stmt->fetchColumn();
    
    successResponse('Apps retrieved', [
        'apps' => $apps,
        'pagination' => [
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset,
            'has_more' => ($offset + $limit) < $total
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Get apps error: " . $e->getMessage());
    errorResponse('Failed to retrieve apps', 500);
} catch (Exception $e) {
    error_log("Get apps error: " . $e->getMessage());
    errorResponse('An unexpected error occurred', 500);
}

/**
 * Get relative time string
 */
function getRelativeTime(string $datetime): string {
    $now = new DateTime();
    $then = new DateTime($datetime);
    $diff = $now->diff($then);
    
    if ($diff->y > 0) {
        return $diff->y === 1 ? '1 year ago' : $diff->y . ' years ago';
    }
    if ($diff->m > 0) {
        return $diff->m === 1 ? '1 month ago' : $diff->m . ' months ago';
    }
    if ($diff->d > 0) {
        if ($diff->d === 1) return 'Yesterday';
        if ($diff->d < 7) return $diff->d . ' days ago';
        $weeks = floor($diff->d / 7);
        return $weeks === 1 ? '1 week ago' : $weeks . ' weeks ago';
    }
    if ($diff->h > 0) {
        return $diff->h === 1 ? '1 hour ago' : $diff->h . ' hours ago';
    }
    if ($diff->i > 0) {
        return $diff->i === 1 ? '1 min ago' : $diff->i . ' min ago';
    }
    return 'Just now';
}
