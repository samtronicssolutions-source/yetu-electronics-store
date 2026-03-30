<?php
session_start();

// Get database URL from environment (Render provides this)
$database_url = getenv('DATABASE_URL');

if ($database_url) {
    // Parse Render's PostgreSQL URL
    $db_parts = parse_url($database_url);
    
    define('DB_HOST', $db_parts['host']);
    define('DB_USER', $db_parts['user']);
    define('DB_PASS', $db_parts['pass']);
    define('DB_NAME', ltrim($db_parts['path'], '/'));
    define('DB_PORT', $db_parts['port']);
} else {
    // Local development fallback
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'yetu_db');
    define('DB_PORT', '3306');
}

// Site configuration
define('SITE_NAME', 'Yetu');
define('SITE_URL', getenv('SITE_URL') ?: 'http://localhost:8000/');
define('ADMIN_EMAIL', 'admin@yetu.com');

// M-Pesa Configuration (from environment variables)
define('MPESA_CONSUMER_KEY', getenv('MPESA_CONSUMER_KEY') ?: '');
define('MPESA_CONSUMER_SECRET', getenv('MPESA_CONSUMER_SECRET') ?: '');
define('MPESA_SHORTCODE', getenv('MPESA_SHORTCODE') ?: '174379');
define('MPESA_PASSKEY', getenv('MPESA_PASSKEY') ?: '');
define('MPESA_CALLBACK_URL', SITE_URL . 'mpesa-callback.php');
define('MPESA_ENV', getenv('MPESA_ENV') ?: 'sandbox');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', getenv('APP_ENV') === 'development' ? 1 : 0);

// Timezone
date_default_timezone_set('Africa/Nairobi');
?>
