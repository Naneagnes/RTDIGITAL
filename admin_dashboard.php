<?php
require 'includes/auth.php';
require 'includes/config.php';

// Aktifkan error untuk debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ambil data admin untuk last_login
$admin_id = $_SESSION['user_id'];
$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT last_login FROM users WHERE id = $admin_id"));

// Ambil statistik
$stats = [
    'warga' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role='warga'"))['total'],
    'pengumuman' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM announcements"))['total'],
    'agenda' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM agenda"))['total'],
    'usaha' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM usaha"))['total'],
    'iuran' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) as total FROM iuran WHERE status='Lunas'"))['total'],
    'laporan' => mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM laporan WHERE status='Masuk'"))['total']
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
<?php include 'includes/navbar_admin.php'; ?>

<div class="container mx-auto p-4 mt-4">
    <h1 class="text-2xl font-bold mb-1">
        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard Admin
    </h1>
    <p class="text-sm text-gray-600">
        Halo, <?= htmlspecialchars($_SESSION['nama']) ?>!
    </p>
    <p class="text-xs text-gray-400 mb-6">
        Terakhir login: <?= date('d M Y H:i', strtotime($admin['last_login'])) ?>
    </p>

    <!-- Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white p-4 rounded shadow text-center">
            <i class="fas fa-users text-blue-500 text-2xl mb-2"></i>
            <div class="font-bold text-lg"><?= $stats['warga'] ?></div>
            <div class="text-gray-500 text-sm">Warga</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <i class="fas fa-bullhorn text-green-500 text-2xl mb-2"></i>
            <div class="font-bold text-lg"><?= $stats['pengumuman'] ?></div>
            <div class="text-gray-500 text-sm">Pengumuman</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <i class="fas fa-calendar text-yellow-500 text-2xl mb-2"></i>
            <div class="font-bold text-lg"><?= $stats['agenda'] ?></div>
            <div class="text-gray-500 text-sm">Agenda</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <i class="fas fa-briefcase text-indigo-500 text-2xl mb-2"></i>
            <div class="font-bold text-lg"><?= $stats['usaha'] ?></div>
            <div class="text-gray-500 text-sm">Usaha</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <i class="fas fa-money-bill-wave text-pink-500 text-2xl mb-2"></i>
            <div class="font-bold text-lg"><?= $stats['iuran'] ?></div>
            <div class="text-gray-500 text-sm">Iuran</div>
        </div>
        <div class="bg-white p-4 rounded shadow text-center">
            <i class="fas fa-flag text-red-500 text-2xl mb-2"></i>
            <div class="font-bold text-lg"><?= $stats['laporan'] ?></div>
            <div class="text-gray-500 text-sm">Laporan</div>
        </div>
    </div>

    <!-- Navigasi Cepat -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="admin/manage_announcements.php" class="bg-white p-4 rounded shadow hover:bg-gray-50 flex items-center">
            <i class="fas fa-bullhorn text-2xl text-green-500 mr-4"></i>
            <div>
                <h3 class="font-bold">Kelola Pengumuman</h3>
                <p class="text-sm text-gray-500">Tambah & lihat pengumuman</p>
            </div>
        </a>
        <a href="admin/manage_events.php" class="bg-white p-4 rounded shadow hover:bg-gray-50 flex items-center">

            <i class="fas fa-calendar text-2xl text-yellow-500 mr-4"></i>
            <div>
                <h3 class="font-bold">Kelola Agenda</h3>
                <p class="text-sm text-gray-500">Jadwal kegiatan warga</p>
            </div>
        </a>
        <a href="admin/manage_dues.php" class="bg-white p-4 rounded shadow hover:bg-gray-50 flex items-center">

            <i class="fas fa-money-bill text-2xl text-pink-500 mr-4"></i>
            <div>
                <h3 class="font-bold">Kelola Iuran</h3>
                <p class="text-sm text-gray-500">Pembayaran & status</p>
            </div>
        </a>
        <a href="admin/manage_usaha.php" class="bg-white p-4 rounded shadow hover:bg-gray-50 flex items-center">
            <i class="fas fa-briefcase text-2xl text-indigo-500 mr-4"></i>
            <div>
                <h3 class="font-bold">Kelola Usaha</h3>
                <p class="text-sm text-gray-500">Data usaha warga</p>
            </div>
        </a>
        <a href="admin/manage_reports.php" class="bg-white p-4 rounded shadow hover:bg-gray-50 flex items-center">
        <i class="fas fa-file-alt text-2xl text-red-500 mr-4"></i>
        <div>
            <h3 class="font-bold">Kelola Laporan</h3>
            <p class="text-sm text-gray-500">Pengaduan & laporan warga</p>
        </div>
    </a>
    </div>
</div>
</body>
</html>
