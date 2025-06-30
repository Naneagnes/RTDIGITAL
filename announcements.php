<?php
session_start();
require 'includes/config.php';
include 'includes/navbar.php';

$announcements = mysqli_query($conn, "SELECT * FROM announcements ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengumuman Warga - RT Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<main class="container mx-auto p-4 mt-4">
    <h1 class="text-2xl font-bold mb-6">Pengumuman Terbaru</h1>

    <?php if (mysqli_num_rows($announcements) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while($ann = mysqli_fetch_assoc($announcements)): ?>
            <div class="bg-white p-4 rounded shadow">
                <h3 class="font-bold text-lg"><?= htmlspecialchars($ann['title']) ?></h3>
                <p class="text-gray-600 mt-2"><?= nl2br(htmlspecialchars($ann['content'])) ?></p>
                <p class="text-sm text-gray-400 mt-1"><?= date('d M Y H:i', strtotime($ann['created_at'])) ?></p>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500">Belum ada pengumuman yang tersedia.</p>
    <?php endif; ?>
</main>
</body>
</html>
