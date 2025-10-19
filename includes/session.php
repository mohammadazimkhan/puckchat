<?php
// Enhanced Session Management
require_once __DIR__ . '/config.php';

// Start session with secure settings
function startSecureSession() {
    // Session security settings
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', SECURE_COOKIES ? 1 : 0);
    
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Regenerate session ID periodically for security
    if (!isset($_SESSION['created_at'])) {
        $_SESSION['created_at'] = time();
    } elseif (time() - $_SESSION['created_at'] > 1800) { // 30 minutes
        session_regenerate_id(true);
        $_SESSION['created_at'] = time();
    }
}

// Initialize session
startSecureSession();

// Generate anonymous user ID if not authenticated
if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
    $_SESSION['user_id'] = uniqid('guest_', true);
    $_SESSION['created_at'] = time();
}
?>