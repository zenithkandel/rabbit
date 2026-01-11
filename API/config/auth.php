<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Session Authentication Guard
   Include this file at the top of any page that requires authentication
   ═══════════════════════════════════════════════════════════════════════════ */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn(): bool {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_id']);
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
        'login_time' => $_SESSION['login_time'] ?? null
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
