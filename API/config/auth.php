<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Session Authentication Guard
   Include this file at the top of any page that requires authentication
   ═══════════════════════════════════════════════════════════════════════════ */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database for API key check
require_once __DIR__ . '/database.php';

/**
 * Check if user is logged in
 */
function isLoggedIn(): bool {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_id']);
}

/**
 * Check if user has an API key generated
 */
function hasApiKey(): bool {
    if (!isLoggedIn()) {
        return false;
    }
    
    try {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT api_key_hash FROM users WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user && !empty($user['api_key_hash']);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Get current user data from session
 */
function getCurrentUser(): ?array {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'email' => $_SESSION['user_email'] ?? null,
        'name' => $_SESSION['user_name'] ?? null,
        'login_time' => $_SESSION['login_time'] ?? null,
        'has_api_key' => hasApiKey()
    ];
}

/**
 * Require authentication - redirects if not logged in
 */
function requireAuth(): void {
    if (!isLoggedIn()) {
        // Clear any partial session data
        session_unset();
        session_destroy();
        
        // Redirect to landing page
        header('Location: /rabbit/index.php');
        exit;
    }
}

// Auto-require auth when this file is included
requireAuth();

// Make user data available
$currentUser = getCurrentUser();
