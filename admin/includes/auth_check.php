<?php
session_start();

// Check if user is not logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get current admin user info
$admin_id = $_SESSION['admin_id'];
$admin_username = $_SESSION['admin_username'];
?> 