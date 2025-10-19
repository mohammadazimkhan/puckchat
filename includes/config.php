<?php
// Environment Configuration System
// Automatically detects local vs production environment

// Detect environment
function isLocalEnvironment() {
    $serverName = $_SERVER['SERVER_NAME'] ?? '';
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';
    
    return (
        strpos($serverName, 'localhost') !== false ||
        strpos($httpHost, 'localhost') !== false ||
        strpos($serverName, '127.0.0.1') !== false ||
        strpos($_SERVER['DOCUMENT_ROOT'] ?? '', 'xampp') !== false
    );
}

// Load appropriate configuration
if (isLocalEnvironment()) {
    require_once __DIR__ . '/config-local.php';
} else {
    require_once __DIR__ . '/config-prod.php';
}

// Common configuration for all environments
define('APP_NAME', 'PuckChat');
define('APP_VERSION', '1.0.0');
define('SESSION_TIMEOUT', 3600); // 1 hour

// Ad Network Configuration
define('ADSENSE_CLIENT_ID', 'your_adsense_client_id');
define('PROPELLER_ZONE_ID', 'your_propeller_zone_id');
?>