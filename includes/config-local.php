<?php
// Local Development Configuration (XAMPP)

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'puckchat_local');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Environment Settings
define('ENVIRONMENT', 'local');
define('DEBUG_MODE', true);
define('SHOW_ERRORS', true);
define('LOG_ERRORS', true);

// Security Settings
define('SECURE_COOKIES', false); // HTTP for local development
define('CSRF_PROTECTION', true);

// Application URLs
define('BASE_URL', 'http://localhost/puckchat/public/');
define('API_URL', 'http://localhost/puckchat/public/api/');

// Error Reporting
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 1);
}
?>