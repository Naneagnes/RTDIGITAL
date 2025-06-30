<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Jika belum login, redirect ke halaman login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

// Dapatkan data admin
require 'config.php';
$admin_id = $_SESSION['user_id'];
$admin_query = mysqli_query($conn, "SELECT * FROM users WHERE id = $admin_id");
$admin_data = mysqli_fetch_assoc($admin_query);
?>
