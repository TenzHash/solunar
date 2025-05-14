<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // If not logged in and not on login page, redirect to login
    $current_page = basename($_SERVER['PHP_SELF']);
    if ($current_page !== 'login.php') {
        header('Location: login.php');
        exit;
    }
} else {
    // If logged in and on login page, redirect to dashboard
    $current_page = basename($_SERVER['PHP_SELF']);
    if ($current_page === 'login.php') {
        header('Location: index.php');
        exit;
    }
}
?> 