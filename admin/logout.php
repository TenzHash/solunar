<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['admin_id'])) {
    // Log the logout activity
    $stmt = $conn->prepare("INSERT INTO activity_logs (admin_id, action, entity_type, details) VALUES (?, 'logout', 'admin', 'Admin logged out')");
    $stmt->bind_param("i", $_SESSION['admin_id']);
    $stmt->execute();
    
    // Update last login time
    $stmt = $conn->prepare("UPDATE admin_accounts SET last_login = NOW() WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['admin_id']);
    $stmt->execute();
}

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: login.php');
exit;
?> 