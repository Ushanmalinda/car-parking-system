<?php
// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'car_parking_system');

// Site Configuration
define('SITE_NAME', 'Car Parking Management System');
define('SITE_URL', 'http://localhost/car-parking-system/');

// Session Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds

// Parking Rates (per hour)
define('TWO_WHEELER_RATE', 10);
define('FOUR_WHEELER_RATE', 20);

// Timezone
date_default_timezone_set('Asia/Kolkata');
?>
