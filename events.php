<?php
session_start();
include 'includes/config.php';
include 'includes/navbar.php';

$agenda = mysqli_query($conn, "SELECT * FROM agenda ORDER BY date DESC");
$iuran = mysqli_query($conn, "SELECT * FROM iuran ORDER BY bulan DESC");
$usaha = mysqli_query($conn, "SELECT * FROM usaha ORDER BY nama_warga ASC");
$laporan = mysqli_query($conn, "SELECT * FROM laporan ORDER BY tanggal DESC");

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Agenda - RT Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<main class="container mx-auto p-4 mt-4">
    <h1 class="text-2xl font-bold mb-6">Agenda Kegiatan Warga</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <?php while($row = mysqli_fetch_assoc($agenda)): ?>
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold"><?= htmlspecialchars($row['event']) ?></h3>
            <p class="text-gray-600"><?= htmlspecialchars($row['location']) ?></p>
            <p class="text-sm text-gray-400"><?= date('d M Y', strtotime($row['date'])) ?></p>
        </div>
        <?php endwhile; ?>
    </div>
</main>
</body>
</html>
