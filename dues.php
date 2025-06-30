<?php
session_start();
require 'includes/config.php';
include 'includes/navbar.php';

// Ambil semua iuran, urutkan bulan terbaru
$iuran = mysqli_query(
    $conn,
    "SELECT * FROM iuran ORDER BY bulan DESC, nama_warga ASC"
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Informasi Iuran – RT Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<main class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Informasi Iuran Warga</h1>

    <?php if (mysqli_num_rows($iuran) > 0): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded shadow">
                <thead class="bg-blue-700 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Nama warga</th>
                        <th class="py-3 px-4 text-left">Jenis</th>
                        <th class="py-3 px-4 text-left">Bulan</th>
                        <th class="py-3 px-4 text-left">Jumlah</th>
                        <th class="py-3 px-4 text-left">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($iuran)): ?>
                    <tr class="border-t">
                        <td class="py-3 px-4"><?= htmlspecialchars($row['nama_warga']) ?></td>
                        <td class="py-3 px-4"><?= htmlspecialchars($row['jenis']) ?></td>
                        <td class="py-3 px-4"><?= htmlspecialchars($row['bulan']) ?></td>
                        <td class="py-3 px-4">
                            Rp <?= number_format($row['jumlah'], 0, ',', '.') ?>
                        </td>
                        <td class="py-3 px-4">
                            <span class="<?= $row['status'] === 'Lunas' ? 'text-green-600' : 'text-red-500' ?>">
                                <?= htmlspecialchars($row['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-gray-500">
            Belum ada data iuran yang tercatat.
        </p>
    <?php endif; ?>
</main>
</body>
</html>
