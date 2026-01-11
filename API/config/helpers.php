<?php
/* ═══════════════════════════════════════════════════════════════════════════
   RABBIT — API Helper Functions
   ═══════════════════════════════════════════════════════════════════════════ */

// Prevent direct access
if (!defined('RABBIT_API')) {
    http_response_code(403);
    exit('Direct access not permitted');
}

/**
 * Send JSON response and exit
 */
function jsonResponse(array $data, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * Send success response
 */
function successResponse(string $message, array $data = [], int $statusCode = 200): void {
    jsonResponse([
        'success' => true,
        'message' => $message,
        'data' => $data
    ], $statusCode);
}

/**
 * Send error response
 */
function errorResponse(string $message, int $statusCode = 400, array $errors = []): void {
    $response = [
        'success' => false,
        'message' => $message
    ];
    
    if (!empty($errors)) {
        $response['errors'] = $errors;
    }
    
    jsonResponse($response, $statusCode);
}

/**
 * Generate UUID v4
 */
function generateUUID(): string {
    $data = random_bytes(16);
    
    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

/**
 * Validate email format
 */
function isValidEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength
 */
function isValidPassword(string $password): array {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }
    
    return $errors;
}

/**
 * Sanitize input string
 */
function sanitizeInput(string $input): string {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate secure API key
 */
function generateApiKey(): string {
    return 'rb_' . bin2hex(random_bytes(24));
}

/**
 * Hash API key for storage
 */
function hashApiKey(string $apiKey): string {
    return hash('sha256', $apiKey);
}

/**
 * Get JSON request body
 */
function getJsonBody(): array {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        return [];
    }
    
    return $data ?? [];
}

/**
 * Check if request method matches
 */
function requireMethod(string $method): void {
    if ($_SERVER['REQUEST_METHOD'] !== strtoupper($method)) {
        errorResponse('Method not allowed', 405);
    }
}

/**
 * Set CORS headers
 */
function setCorsHeaders(): void {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-API-Key');
    
    // Handle preflight
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}
