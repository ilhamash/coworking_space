-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 24, 2026 at 02:48 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_rent`
--

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_ruangan`
--

CREATE TABLE `pengaturan_ruangan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_ruangan` varchar(50) NOT NULL,
  `kode_prefix` varchar(10) NOT NULL,
  `jumlah_unit` int(11) NOT NULL,
  `kapasitas_per_unit` int(11) NOT NULL,
  `harga_per_jam` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan_ruangan`
--

INSERT INTO `pengaturan_ruangan` (`id`, `user_id`, `nama_ruangan`, `kode_prefix`, `jumlah_unit`, `kapasitas_per_unit`, `harga_per_jam`) VALUES
(1, 1, 'Hot Desk', 'h', 10, 2, 15000),
(2, 1, 'Private Office', 'p', 5, 4, 20000),
(3, 1, 'Meeting Room', 'm', 3, 10, 30000);

-- --------------------------------------------------------

--
-- Table structure for table `penyewaan`
--

CREATE TABLE `penyewaan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_penyewa` varchar(100) NOT NULL,
  `jenis_ruangan` varchar(50) NOT NULL,
  `slot_id` varchar(20) NOT NULL,
  `tanggal_sewa` date NOT NULL,
  `durasi_jam` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `status_pembayaran` enum('Belum Lunas','Lunas') DEFAULT 'Belum Lunas',
  `status_sewa` enum('Aktif','Selesai') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penyewaan`
--

INSERT INTO `penyewaan` (`id`, `user_id`, `nama_penyewa`, `jenis_ruangan`, `slot_id`, `tanggal_sewa`, `durasi_jam`, `total_harga`, `status_pembayaran`, `status_sewa`) VALUES
(3, 1, 'Ilham', 'Hot Desk', 'h10', '2026-04-25', 5, 50000, 'Lunas', 'Selesai'),
(4, 1, 'Asep', 'Hot Desk', 'h8', '2026-04-24', 5, 50000, 'Lunas', 'Aktif'),
(5, 1, 'Ujang', 'Hot Desk', 'h2', '2026-04-24', 2, 30000, 'Lunas', 'Aktif'),
(6, 1, 'Haq', 'Meeting Room', 'm1', '2026-04-25', 3, 90000, 'Lunas', 'Aktif'),
(7, 1, 'Fais', 'Meeting Room', 'm3', '2026-04-24', 4, 120000, 'Lunas', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$XHC.tEpmOD0gEBk.c.TTDuyAuQpyW6LF1pa7lw53LWyCi.v2V1SAG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengaturan_ruangan`
--
ALTER TABLE `pengaturan_ruangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `penyewaan`
--
ALTER TABLE `penyewaan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengaturan_ruangan`
--
ALTER TABLE `pengaturan_ruangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `penyewaan`
--
ALTER TABLE `penyewaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pengaturan_ruangan`
--
ALTER TABLE `pengaturan_ruangan`
  ADD CONSTRAINT `pengaturan_ruangan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penyewaan`
--
ALTER TABLE `penyewaan`
  ADD CONSTRAINT `penyewaan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
