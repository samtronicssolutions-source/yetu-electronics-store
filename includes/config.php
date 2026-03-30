<?php
session_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'yetu_db');

// Site configuration
define('SITE_NAME', 'Yetu');
define('SITE_URL', 'http://localhost/yetu/');
define('ADMIN_EMAIL', 'admin@yetu.com');

// M-Pesa Configuration
define('MPESA_CONSUMER_KEY', '75ZYqCx3xbXvAt3ymvRZ4EsewrJDhnUCEUwkAWyAJwGWE78E');
define('MPESA_CONSUMER_SECRET', 'Z2nJOAZb1XqBrdjaWejH0rhxl9ff1DLSy6Sj7GWlUpXMlOTFDaKvV2zIad3vjBRl');
define('MPESA_SHORTCODE', '174379');
define('MPESA_PASSKEY', 'your_passkey_here'); // Get from Safaricom
define('MPESA_CALLBACK_URL', SITE_URL . 'mpesa-callback.php');

// Environment (sandbox or production)
define('MPESA_ENV', 'sandbox'); // Change to 'production' for live

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Africa/Nairobi');
?>
