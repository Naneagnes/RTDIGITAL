-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 01:37 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rt_info`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'Admin RT 01', 'admin@example.com', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Table structure for table `agenda`
--

CREATE TABLE `agenda` (
  `id` int(11) NOT NULL,
  `event` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agenda`
--

INSERT INTO `agenda` (`id`, `event`, `date`, `location`) VALUES
(1, 'Kerja Bakti Bersih Lingkungan', '2025-06-29', 'Lapangan RT 01'),
(2, 'Ronda Malam', '2025-07-01', 'Pos Kamling RT 01');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `created_at`) VALUES
(1, 'Rapat RT Mingguan', 'Rapat akan dilaksanakan hari Jumat pukul 19.00 di Balai Warga.', '2025-06-25 17:00:00'),
(2, 'Pemadaman Listrik', 'Akan ada pemadaman listrik sementara pada minggu pagi.', '2025-06-26 17:00:00'),
(3, 'Kerja Bakti Mingguan', 'Warga dimohon untuk segera membayar iuran keamanan bulan Juli 2025 paling lambat tanggal 5 Juli 2025 kepada bendahara RT. Terima kasih atas kerjasamanya.', '2025-06-26 13:49:53');

-- --------------------------------------------------------

--
-- Table structure for table `iuran`
--

CREATE TABLE `iuran` (
  `id` int(11) NOT NULL,
  `jenis` varchar(100) DEFAULT NULL,
  `bulan` varchar(50) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `nama_warga` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `iuran`
--

INSERT INTO `iuran` (`id`, `jenis`, `bulan`, `jumlah`, `status`, `nama_warga`) VALUES
(2, 'Iuran Keamanan', 'Juni 2025', 15000, 'Lunas', 'Siti Aminah'),
(3, 'Kas RT', 'Juni 2025', 5000, 'Lunas', 'Dewi Kartika'),
(9, 'Arisan', 'Juni 2025', 10000, 'Lunas', 'Budi Santoso'),
(17, 'Kas RT', 'Juni 2025', 5000, 'Belum', 'Susan'),
(21, 'Arisan', 'Juni 2025', 5000, 'Belum', 'Siti Aminah'),
(22, 'Arisan', 'Juni 2025', 5000, 'Belum', 'Siti Aminah');

-- --------------------------------------------------------

--
-- Table structure for table `laporan`
--

CREATE TABLE `laporan` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) DEFAULT NULL,
  `isi` text DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `status` enum('Masuk','Diproses','Selesai') DEFAULT 'Masuk'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan`
--

INSERT INTO `laporan` (`id`, `judul`, `isi`, `tanggal`, `status`) VALUES
(1, 'Lampu Jalan Mati', 'Lampu di dekat pos ronda mati sejak 2 hari lalu.', '2025-06-25', 'Selesai'),
(2, 'Got Tersumbat', 'Got depan rumah Bu Wulan mampet, mohon segera ditangani.', '2025-06-24', 'Diproses'),
(5, 'Pohon tumbang', 'Pohon dekat lapangan tumbang sejak sore tadi.', '2025-06-26', 'Masuk');

-- --------------------------------------------------------

--
-- Table structure for table `usaha`
--

CREATE TABLE `usaha` (
  `id` int(11) NOT NULL,
  `nama_warga` varchar(100) DEFAULT NULL,
  `jenis_usaha` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `kontak` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usaha`
--

INSERT INTO `usaha` (`id`, `nama_warga`, `jenis_usaha`, `deskripsi`, `kontak`) VALUES
(1, 'Pak Didi', 'Servis Elektronik', 'Menerima servis TV, kipas angin, dan setrika.', '081234567890'),
(2, 'Bu Tati', 'Katering Kue', 'Terima pesanan kue basah dan snack box.', '085678912345');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `raw_password` varchar(255) DEFAULT NULL,
  `role` enum('admin','warga') NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `raw_password`, `role`, `last_login`) VALUES
(29, 'Agnes', 'agnes@musik.com', '$2y$10$LKFVumeFe0EX4MEsa84kv.0Okql85SLpqO3KXl.9DDPjNjMyurLqm', NULL, 'warga', '2025-06-26 16:22:27'),
(30, 'wawa', 'wawa@anjay.com', '$2y$10$Eo82o.7tUaS0wMnMu3lWdeVuGUMBWaj8yHVxdRsx9jPWK0ngxpbO.', NULL, 'warga', '2025-06-26 15:18:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iuran`
--
ALTER TABLE `iuran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan`
--
ALTER TABLE `laporan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usaha`
--
ALTER TABLE `usaha`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `iuran`
--
ALTER TABLE `iuran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `laporan`
--
ALTER TABLE `laporan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `usaha`
--
ALTER TABLE `usaha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
