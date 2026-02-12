<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/currencies.php';

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Check if user is admin
function isAdmin() {
    return isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'system_admin');
}

// Check if user is system admin
function isSystemAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'system_admin';
}

// Redirect if not logged in
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . SITE_URL . 'login.php');
        exit();
    }
}

// Redirect if not admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . SITE_URL . 'user/dashboard.php');
        exit();
    }
}

// Sanitize input
function sanitize($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Generate booking number
function generateBookingNumber() {
    return 'BK' . date('Ymd') . rand(1000, 9999);
}

// Calculate parking charges
function calculateParkingCharge($entry_time, $exit_time, $vehicle_type) {
    $entry = new DateTime($entry_time);
    $exit = new DateTime($exit_time);
    $interval = $entry->diff($exit);
    
    $hours = $interval->h + ($interval->days * 24);
    $minutes = $interval->i;
    
    // Round up to next hour if minutes > 0
    if ($minutes > 0) {
        $hours++;
    }
    
    // Minimum 1 hour charge
    if ($hours < 1) {
        $hours = 1;
    }
    
    $rate = ($vehicle_type === 'two_wheeler') ? TWO_WHEELER_RATE : FOUR_WHEELER_RATE;
    return $hours * $rate;
}

// Format date
function formatDate($date, $format = 'd-m-Y H:i:s') {
    return date($format, strtotime($date));
}

// Log activity
function logActivity($db, $user_id, $action, $description) {
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $stmt = $db->getConnection()->prepare("INSERT INTO activity_logs (user_id, action, description, ip_address) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $action, $description, $ip_address);
    $stmt->execute();
    $stmt->close();
}

// Flash message
function setFlashMessage($type, $message) {
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
}

function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $type = $_SESSION['flash_type'];
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_type']);
        unset($_SESSION['flash_message']);
        return ['type' => $type, 'message' => $message];
    }
    return null;
}

// Get dashboard statistics
function getDashboardStats($db) {
    $stats = [];
    
    // Total slots
    $result = $db->query("SELECT COUNT(*) as total FROM parking_slots");
    $stats['total_slots'] = $result->fetch_assoc()['total'];
    
    // Available slots
    $result = $db->query("SELECT COUNT(*) as total FROM parking_slots WHERE status = 'available'");
    $stats['available_slots'] = $result->fetch_assoc()['total'];
    
    // Occupied slots
    $result = $db->query("SELECT COUNT(*) as total FROM parking_slots WHERE status = 'occupied'");
    $stats['occupied_slots'] = $result->fetch_assoc()['total'];
    
    // Today's bookings
    $result = $db->query("SELECT COUNT(*) as total FROM parking_bookings WHERE DATE(entry_time) = CURDATE()");
    $stats['today_bookings'] = $result->fetch_assoc()['total'];
    
    // Today's revenue
    $result = $db->query("SELECT COALESCE(SUM(parking_charge), 0) as total FROM parking_bookings WHERE DATE(exit_time) = CURDATE() AND status = 'exited'");
    $stats['today_revenue'] = $result->fetch_assoc()['total'];
    
    // Active bookings
    $result = $db->query("SELECT COUNT(*) as total FROM parking_bookings WHERE status = 'parked'");
    $stats['active_bookings'] = $result->fetch_assoc()['total'];
    
    return $stats;
}

// Get system currency
function getSystemCurrency($db) {
    $result = $db->query("SELECT setting_value FROM system_settings WHERE setting_key = 'currency'");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['setting_value'];
    }
    return 'USD';
}

// Format currency with system settings
function formatCurrency($amount, $db = null) {
    if ($db) {
        $currencyCode = getSystemCurrency($db);
    } else {
        $currencyCode = 'USD';
    }
    return formatCurrencyValue($amount, $currencyCode);
}
?>
