<?php
// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RT Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Navbar Desktop -->
    <nav class="bg-blue-700 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-3">
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-2">
                    <i class="fas fa-home text-2xl"></i>
                    <a href="index.php" class="text-xl font-bold">RT Digital</a>
                </div>

                <!-- Menu untuk Desktop -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="index.php" class="hover:text-blue-200 transition <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'font-bold border-b-2 border-white' : '' ?>">
                        <i class="fas fa-home mr-1"></i> Beranda
                    </a>
                    
                    <a href="announcements.php" class="hover:text-blue-200 transition <?= basename($_SERVER['PHP_SELF']) == 'announcements.php' ? 'font-bold border-b-2 border-white' : '' ?>">
                        <i class="fas fa-bullhorn mr-1"></i> Pengumuman
                    </a>
                    
                    <a href="events.php" class="hover:text-blue-200 transition <?= basename($_SERVER['PHP_SELF']) == 'events.php' ? 'font-bold border-b-2 border-white' : '' ?>">
                        <i class="fas fa-calendar-alt mr-1"></i> Agenda
                    </a>
                    
                    <a href="dues.php" class="hover:text-blue-200 transition <?= basename($_SERVER['PHP_SELF']) == 'dues.php' ? 'font-bold border-b-2 border-white' : '' ?>">
                        <i class="fas fa-money-bill-wave mr-1"></i> Iuran
                    </a>
                    
                    <a href="directory.php" class="hover:text-blue-200 transition <?= basename($_SERVER['PHP_SELF']) == 'directory.php' ? 'font-bold border-b-2 border-white' : '' ?>">
                        <i class="fas fa-store mr-1"></i> Direktori Usaha
                    </a>

                    <!-- Menu Admin -->
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                    <div class="relative group">
                        <button class="hover:text-blue-200 flex items-center">
                            <i class="fas fa-tools mr-1"></i> Admin 
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute hidden group-hover:block bg-white text-gray-800 rounded-md shadow-lg py-2 w-48 right-0 z-10">
                            <a href="admin/dashboard.php" class="block px-4 py-2 hover:bg-blue-50">
                                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                            </a>
                            <a href="admin/manage_announcements.php" class="block px-4 py-2 hover:bg-blue-50">
                                <i class="fas fa-bullhorn mr-2"></i> Kelola Pengumuman
                            </a>
                            <a href="admin/manage_events.php" class="block px-4 py-2 hover:bg-blue-50">
                                <i class="fas fa-calendar mr-2"></i> Kelola Agenda
                            </a>
                            <a href="admin/manage_dues.php" class="block px-4 py-2 hover:bg-blue-50">
                                <i class="fas fa-money-bill mr-2"></i> Kelola Iuran
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- User Dropdown -->
                    <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="relative group">
                        <button class="flex items-center space-x-1">
                            <img src="https://ui-avatars.com/api/?name=<?= isset($_SESSION['nama']) ? urlencode($_SESSION['nama']) : 'User' ?>&background=random" 
                                 class="w-8 h-8 rounded-full border-2 border-white">
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="absolute hidden group-hover:block bg-white text-gray-800 rounded-md shadow-lg py-2 w-48 right-0 z-10">
                            <div class="px-4 py-2 border-b">
                                <p class="font-semibold"><?= $_SESSION['nama'] ?? 'User' ?></p>
                                <p class="text-xs text-gray-500"><?= $_SESSION['role'] == 'admin' ? 'Admin' : 'Warga' ?></p>
                            </div>
                            <a href="profile.php" class="block px-4 py-2 hover:bg-blue-50">
                                <i class="fas fa-user mr-2"></i> Profil
                            </a>
                            <a href="reports.php" class="block px-4 py-2 hover:bg-blue-50">
                                <i class="fas fa-exclamation-circle mr-2"></i> Laporan Saya
                            </a>
                            <a href="logout.php" class="block px-4 py-2 hover:bg-blue-50 text-red-500">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </a>
                        </div>
                    </div>
                    <?php else: ?>
                    <a href="login.php" class="bg-white text-blue-700 px-4 py-1 rounded-lg hover:bg-blue-50 transition">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Hamburger Menu untuk Mobile -->
                <button id="mobile-menu-button" class="md:hidden focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>

        <!-- Menu Mobile -->
        <div id="mobile-menu" class="hidden md:hidden bg-blue-800 px-4 py-2">
            <a href="index.php" class="block py-2 px-2 hover:bg-blue-600 rounded <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'bg-blue-600' : '' ?>">
                <i class="fas fa-home mr-2"></i> Beranda
            </a>
            
            <a href="announcements.php" class="block py-2 px-2 hover:bg-blue-600 rounded <?= basename($_SERVER['PHP_SELF']) == 'announcements.php' ? 'bg-blue-600' : '' ?>">
                <i class="fas fa-bullhorn mr-2"></i> Pengumuman
            </a>
            
            <a href="events.php" class="block py-2 px-2 hover:bg-blue-600 rounded <?= basename($_SERVER['PHP_SELF']) == 'events.php' ? 'bg-blue-600' : '' ?>">
                <i class="fas fa-calendar-alt mr-2"></i> Agenda
            </a>
            
            <a href="dues.php" class="block py-2 px-2 hover:bg-blue-600 rounded <?= basename($_SERVER['PHP_SELF']) == 'dues.php' ? 'bg-blue-600' : '' ?>">
                <i class="fas fa-money-bill-wave mr-2"></i> Iuran
            </a>
            
            <a href="directory.php" class="block py-2 px-2 hover:bg-blue-600 rounded <?= basename($_SERVER['PHP_SELF']) == 'directory.php' ? 'bg-blue-600' : '' ?>">
                <i class="fas fa-store mr-2"></i> Direktori Usaha
            </a>

            <a href="reports.php" class="block py-2 px-2 hover:bg-blue-600 rounded <?= basename($_SERVER['PHP_SELF']) == 'directory.php' ? 'bg-blue-600' : '' ?>">
                <i class="fas fa-store mr-2"></i> Laporan
            </a>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <div class="ml-2 mt-2 border-t border-blue-600 pt-2">
                <p class="px-2 py-1 text-blue-300 font-bold">
                    <i class="fas fa-tools mr-2"></i> Menu Admin
                </p>
                <a href="admin/dashboard.php" class="block py-2 px-4 hover:bg-blue-600 rounded">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="admin/manage_announcements.php" class="block py-2 px-4 hover:bg-blue-600 rounded">
                    <i class="fas fa-bullhorn mr-2"></i> Pengumuman
                </a>
                <a href="admin/manage_events.php" class="block py-2 px-4 hover:bg-blue-600 rounded">
                    <i class="fas fa-calendar mr-2"></i> Agenda
                </a>
                <a href="admin/manage_dues.php" class="block py-2 px-4 hover:bg-blue-600 rounded">
                    <i class="fas fa-money-bill mr-2"></i> Iuran
                </a>
            </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="ml-2 mt-2 border-t border-blue-600 pt-2">
                <p class="px-2 py-1 text-xs text-blue-300">Login sebagai: <strong><?= $_SESSION['nama'] ?? 'User' ?></strong></p>
                <a href="profile.php" class="block py-2 px-4 hover:bg-blue-600 rounded">
                    <i class="fas fa-user mr-2"></i> Profil
                </a>
                <a href="reports.php" class="block py-2 px-4 hover:bg-blue-600 rounded">
                    <i class="fas fa-exclamation-circle mr-2"></i> Laporan Saya
                </a>
                <a href="logout.php" class="block py-2 px-4 hover:bg-blue-600 rounded text-red-300">
                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                </a>
            </div>
            <?php else: ?>
            <a href="login.php" class="block py-2 px-2 mt-2 text-center bg-white text-blue-700 rounded-lg hover:bg-blue-100 transition">
                <i class="fas fa-sign-in-alt mr-2"></i> Login
            </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Script untuk mobile menu -->
    <script>
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
