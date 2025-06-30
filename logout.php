<?php
session_start(); // Mulai session

// Hapus semua session
session_unset();
session_destroy();

// Redirect ke halaman login (atau index.php sesuai kebutuhanmu)
header('Location: login.php');
exit;
?>
