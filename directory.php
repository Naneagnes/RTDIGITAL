<?php
session_start();
require 'includes/config.php';
include 'includes/navbar.php';

$usaha = mysqli_query($conn, "SELECT * FROM usaha ORDER BY nama_warga ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Direktori Usaha Warga - RT Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<main class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Direktori Usaha & Keahlian Warga</h1>

    <?php if (mysqli_num_rows($usaha) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while($u = mysqli_fetch_assoc($usaha)): ?>
            <div class="bg-white rounded shadow p-4">
                <h3 class="text-lg font-bold"><?= htmlspecialchars($u['nama_warga']) ?></h3>
                <p class="text-gray-600"><?= htmlspecialchars($u['jenis_usaha']) ?></p>
                <p class="text-sm text-gray-500 mt-1"><?= nl2br(htmlspecialchars($u['deskripsi'])) ?></p>
                <p class="text-sm text-gray-400 mt-2">Kontak: <?= htmlspecialchars($u['kontak']) ?></p>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500">Belum ada warga yang mendaftarkan usaha atau keahliannya.</p>
    <?php endif; ?>
</main>
</body>
</html>
