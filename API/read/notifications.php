<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Get Notifications API
   Returns user's notifications with pagination
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
$limit = isset($_GET['limit']) ? min((int)$_GET['limit'], 50) : 10;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$appId = isset($_GET['app_id']) ? sanitizeInput($_GET['app_id']) : null;
$unreadOnly = isset($_GET['unread']) && $_GET['unread'] === '1';

try {
    $db = Database::getInstance()->getConnection();
    
    // Build query
    $whereConditions = ['a.user_id = :user_id'];
    $params = ['user_id' => $userId];
    
    if ($appId) {
        $whereConditions[] = 'n.app_id = :app_id';
        $params['app_id'] = $appId;
    }
    
    if ($unreadOnly) {
        $whereConditions[] = 'n.is_read = 0';
    }
    
    $whereClause = implode(' AND ', $whereConditions);
    
    // Get notifications with app info
    $stmt = $db->prepare("
        SELECT 
            n.id,
            n.title,
            n.message,
            n.tag,
            n.target_link,
            n.is_read,
            n.read_at,
            n.created_at,
            a.id as app_id,
            a.name as app_name,
            a.slug as app_slug,
            a.color as app_color
        FROM notifications n
        INNER JOIN apps a ON n.app_id = a.id
        WHERE {$whereClause}
        ORDER BY n.created_at DESC
        LIMIT :limit OFFSET :offset
    ");
    
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    foreach ($params as $key => $value) {
        $stmt->bindValue(":{$key}", $value);
    }
    
    $stmt->execute();
    $notifications = $stmt->fetchAll();
    
    // Format timestamps and add relative time
    foreach ($notifications as &$notif) {
        $notif['created_at_relative'] = getRelativeTime($notif['created_at']);
        $notif['is_read'] = (bool) $notif['is_read'];
    }
    
    // Get total count for pagination
    $stmt = $db->prepare("
        SELECT COUNT(n.id) as total
        FROM notifications n
        INNER JOIN apps a ON n.app_id = a.id
        WHERE {$whereClause}
    ");
    foreach ($params as $key => $value) {
        $stmt->bindValue(":{$key}", $value);
    }
    $stmt->execute();
    $total = (int) $stmt->fetchColumn();
    
    successResponse('Notifications retrieved', [
        'notifications' => $notifications,
        'pagination' => [
            'total' => $total,
            'limit' => $limit,
            'offset' => $offset,
            'has_more' => ($offset + $limit) < $total
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Get notifications error: " . $e->getMessage());
    errorResponse('Failed to retrieve notifications', 500);
} catch (Exception $e) {
    error_log("Get notifications error: " . $e->getMessage());
    errorResponse('An unexpected error occurred', 500);
}

/**
 * Get relative time string (e.g., "2 min ago", "1 hour ago")
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
