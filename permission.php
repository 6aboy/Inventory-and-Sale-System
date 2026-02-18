<?php
// permission.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ' . '/Inventory-and-Sale-System/views/auth/login.php');
    exit();
}
?>