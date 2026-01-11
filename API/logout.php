<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — Logout API Endpoint
   Destroys user session and logs out
   ═══════════════════════════════════════════════════════════════════════════ */

// Start session
session_start();

// Define API constant for included files
define('RABBIT_API', true);

// Include dependencies
require_once __DIR__ . '/config/helpers.php';

// Set CORS headers
setCorsHeaders();

// Only allow POST requests
requireMethod('POST');

// Destroy session
session_unset();
session_destroy();

// Clear session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

successResponse('Logged out successfully', [
    'redirect' => '/rabbit/index.php'
]);
