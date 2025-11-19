<?php
require_once 'config/config.php';
require_once 'includes/functions.php';

if (isLoggedIn()) {
    $db = new Database();
    logActivity($db, $_SESSION['user_id'], 'Logout', 'User logged out');
    $db->closeConnection();
}

session_destroy();
header('Location: ' . SITE_URL . 'index.php');
exit();
?>
