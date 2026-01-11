<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Dashboard Stats API
   Returns user's dashboard statistics (notifications, apps, etc.)
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
    
    // Get total notifications count (across all user's apps)
    $stmt = $db->prepare("
        SELECT COUNT(n.id) as total
        FROM notifications n
        INNER JOIN apps a ON n.app_id = a.id
        WHERE a.user_id = :user_id
    ");
    $stmt->execute(['user_id' => $userId]);
    $totalNotifications = (int) $stmt->fetchColumn();
    
    // Get today's notifications count
    $stmt = $db->prepare("
        SELECT COUNT(n.id) as today
        FROM notifications n
        INNER JOIN apps a ON n.app_id = a.id
        WHERE a.user_id = :user_id
        AND DATE(n.created_at) = CURDATE()
    ");
    $stmt->execute(['user_id' => $userId]);
    $todayNotifications = (int) $stmt->fetchColumn();
    
    // Get unread notifications count
    $stmt = $db->prepare("
        SELECT COUNT(n.id) as unread
        FROM notifications n
        INNER JOIN apps a ON n.app_id = a.id
        WHERE a.user_id = :user_id
        AND n.is_read = 0
    ");
    $stmt->execute(['user_id' => $userId]);
    $unreadNotifications = (int) $stmt->fetchColumn();
    
    // Get connected apps count
    $stmt = $db->prepare("
        SELECT COUNT(id) as total
        FROM apps
        WHERE user_id = :user_id
    ");
    $stmt->execute(['user_id' => $userId]);
    $totalApps = (int) $stmt->fetchColumn();
    
    // Get active apps count
    $stmt = $db->prepare("
        SELECT COUNT(id) as active
        FROM apps
        WHERE user_id = :user_id AND is_active = 1
    ");
    $stmt->execute(['user_id' => $userId]);
    $activeApps = (int) $stmt->fetchColumn();
    
    // Get this week's notifications count
    $stmt = $db->prepare("
        SELECT COUNT(n.id) as this_week
        FROM notifications n
        INNER JOIN apps a ON n.app_id = a.id
        WHERE a.user_id = :user_id
        AND n.created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    ");
    $stmt->execute(['user_id' => $userId]);
    $weekNotifications = (int) $stmt->fetchColumn();
    
    successResponse('Dashboard stats retrieved', [
        'notifications' => [
            'total' => $totalNotifications,
            'today' => $todayNotifications,
            'unread' => $unreadNotifications,
            'this_week' => $weekNotifications
        ],
        'apps' => [
            'total' => $totalApps,
            'active' => $activeApps
        ]
    ]);
    
} catch (PDOException $e) {
    error_log("Dashboard stats error: " . $e->getMessage());
    errorResponse('Failed to retrieve stats', 500);
} catch (Exception $e) {
    error_log("Dashboard stats error: " . $e->getMessage());
    errorResponse('An unexpected error occurred', 500);
}
