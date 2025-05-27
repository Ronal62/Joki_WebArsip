-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 27, 2025 at 01:45 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siketannn`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `username`, `password`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'adminku', '$2y$10$fkgyxmvArKB..mZqaRcQYO4lj3/rmsnvJ8ALbKnKOK1aTv0KuZfvW', NULL, '2025-05-20 00:08:49', NULL),
(2, 'Admin test', 'adminaku', '$2y$10$62zARdvP5AIkY8OgacQ7DeogrrktFwL9yD.Ke1px4irehGw7KpZz2', 'uploads/admin/1748194614_IMG_20250424_171713_084.jpg', '2025-05-25 22:35:55', '2025-05-27 19:07:39'),
(4, 'admin', 'admingue', '$2y$10$Y605.Qr2KNwyKoW2hudrw.QdtOwyf0YVDo/UYl7Q.J7q.QKB1/puO', 'uploads/admin/admin_68333dd9a623c5.04145259.jpg', '2025-05-25 22:57:13', NULL),
(5, 'wildan', 'wildan', '$2y$10$QhDivnGmNdncLoQv.YKgaeq/b5THD6DZ.8kNQ00CObnGy565.6rsu', NULL, '2025-05-27 19:27:59', '2025-05-27 20:41:49');

-- --------------------------------------------------------

--
-- Table structure for table `arsip`
--

CREATE TABLE `arsip` (
  `id` int NOT NULL,
  `kode_arsip` varchar(50) NOT NULL,
  `nama_arsip` varchar(255) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text,
  `nama_file` varchar(255) NOT NULL,
  `path_file` varchar(255) NOT NULL,
  `waktu_upload` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `arsip`
--

INSERT INTO `arsip` (`id`, `kode_arsip`, `nama_arsip`, `kategori`, `tanggal`, `keterangan`, `nama_file`, `path_file`, `waktu_upload`) VALUES
(1, 'SK1', 'Test', 'surat masuk', '2025-05-18', 'test', 'test.pdf', 'uploads/surat_masuk/682a0743d98b0_test.pdf', '2025-05-18 23:13:55'),
(4, 'SK1', 'Test', 'surat_rahasia', '2025-05-18', 'teest', 'test.pdf', 'uploads/surat_rahasia/682a0db14b30a_test.pdf', '2025-05-18 23:41:21'),
(5, 'SK2', 'Test', 'surat_pengantar', '2025-05-19', 'test jpg', 'pngtree-vector-logout-icon-png-image_4184683.jpg', 'uploads/surat_pengantar/682aefd37c77b_pngtreevectorlogouticonpngimage_4184683.jpg', '2025-05-19 15:46:11'),
(7, 'SK2', 'Test jpg', 'surat_masuk', '2025-05-19', 'jpg', 'IMG-20250512-WA0084[1].jpg', 'uploads/surat_masuk/682af080c31eb_IMG20250512WA00841.jpg', '2025-05-19 15:49:04'),
(8, 'SK1', 'Test jpg', 'surat_pengantar', '2025-05-19', 'jpg', 'WhatsApp Image 2025-05-14 at 20.57.42_e4b7a23e.jpg', 'uploads/surat_pengantar/682af10f42920_WhatsAppImage20250514at20.57.42_e4b7a23e.jpg', '2025-05-19 15:51:27'),
(9, 'SK2', 'Test pdf', 'surat_kependudukan', '2025-05-19', 'pdf', 'test.pdf', 'uploads/surat_kependudukan/682af331ef776_test.pdf', '2025-05-19 16:00:33'),
(10, 'SK3', 'Test jpg', 'surat_pendukung', '2025-05-19', 'testt pdf', 'test (1).pdf', 'uploads/surat_pendukung/682af56cf11ea_test1.pdf', '2025-05-19 16:10:05'),
(11, 'SK2', 'Test jpg', 'surat_rahasia', '2025-05-19', 'jpg', 'IMG-20250512-WA0084[1].jpg', 'uploads/surat_rahasia/682af617930e6_IMG20250512WA00841.jpg', '2025-05-19 16:12:55'),
(12, 'SK3', 'Test jpg gede', 'surat_rahasia', '2025-05-19', 'ukuran 10mb up', 'WhatsApp Image 2025-05-19 at 16.15.36_a56a0a0e.jpg', 'uploads/surat_rahasia/682af74e02943_WhatsAppImage20250519at16.15.36_a56a0a0e.jpg', '2025-05-19 16:18:06'),
(13, 'SK3', 'test', 'surat_masuk', '2025-05-19', 'test file pdf', 'test.pdf', 'uploads/surat_masuk/682b3908b844c_test.pdf', '2025-05-19 20:58:32'),
(19, 'SK1', 'Surat Keluar', 'surat_keluar', '2025-05-25', 'PDF', 'PPT SIM.pdf', 'uploads/surat_keluar/6832146ed3773_PPTSIM.pdf', '2025-05-25 01:48:14'),
(20, 'SKTEST', 'Test', 'surat_masuk', '2025-05-25', 'anu', 'test (6).pdf', 'uploads/surat_masuk/683337e42817c_test6.pdf', '2025-05-25 22:31:48');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `token` varchar(64) NOT NULL,
  `expiry` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staf`
--

CREATE TABLE `staf` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `staf`
--

INSERT INTO `staf` (`id`, `nama`, `username`, `password`, `foto`, `created_at`, `updated_at`) VALUES
(1, 'faniza syahida', 'fanizasyaaa', '$2y$10$H1EUxogDZJt5FthX2Qh3d.uSO9EoCLnqGD8mF3.4E50kVZJWArulS', NULL, '2025-05-18 19:13:14', '2025-05-18 19:44:15'),
(3, 'Admin Siketan', 'adminku', '$2y$10$TBPj8epG9vzu7jr4vpC97uTF5Bn74lN.damEdc.sImzRrite3CCs6', NULL, '2025-05-19 23:32:30', '2025-05-21 22:34:28'),
(4, 'erika staf', 'erika', '$2y$10$wdzG/SLoGOKjYlpZLZwdFeRD4FEb8RUhi9Rabhnezeh6.uY7bBvyu', 'uploads/1748194856_Absensi Awal Pra-PKKMB.png', '2025-05-23 21:10:01', '2025-05-26 00:41:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `arsip`
--
ALTER TABLE `arsip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `staf`
--
ALTER TABLE `staf`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `arsip`
--
ALTER TABLE `arsip`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staf`
--
ALTER TABLE `staf`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
