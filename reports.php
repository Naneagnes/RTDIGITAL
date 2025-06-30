<?php
session_start();
require 'includes/config.php';
include 'includes/navbar.php';

// Ambil semua laporan dari tabel laporan
$laporan = mysqli_query($conn, "SELECT * FROM laporan ORDER BY tanggal DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Warga - RT Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<main class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Laporan Warga</h1>

    <?php if (mysqli_num_rows($laporan) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while($r = mysqli_fetch_assoc($laporan)): ?>
            <div class="bg-white rounded shadow p-4">
                <h3 class="text-lg font-semibold mb-1"><?= htmlspecialchars($r['judul']) ?></h3>
                <p class="text-sm text-gray-500 mb-2"><?= date('d M Y', strtotime($r['tanggal'])) ?> | Status: 
                    <span class="<?= $r['status'] === 'Selesai' ? 'text-green-600' : 'text-yellow-500' ?>">
                        <?= htmlspecialchars($r['status']) ?>
                    </span>
                </p>
                <p class="text-gray-700 whitespace-pre-line"><?= nl2br(htmlspecialchars($r['isi'])) ?></p>
            </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500">Belum ada laporan yang tercatat.</p>
    <?php endif; ?>
</main>
</body>
</html>
