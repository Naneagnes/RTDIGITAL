<?php
if (!isset($_SESSION)) session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}
?>

<nav class="bg-blue-700 text-white shadow">
    <div class="container mx-auto px-4 py-3 flex justify-between items-center">

        <div class="flex items-center space-x-3">
            <span class="text-sm">👤 <?= htmlspecialchars($_SESSION['nama']) ?></span>
            <a href="logout.php" onclick="return confirm('Yakin ingin logout?');" class="text-red-600 hover:underline">Logout</a>
        </div>
    </div>
</nav>
