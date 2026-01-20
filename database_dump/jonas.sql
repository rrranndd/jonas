-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 20, 2026 at 06:33 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jonas`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(3, 'admin', '$2y$12$DELJWnYGKqtfGA16c5OqZuJjjr16njDY9UwIEs9PEMpzXmvYsQuRC');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `no_invoice` varchar(30) NOT NULL,
  `kode_order` varchar(20) DEFAULT NULL,
  `id_paket` int DEFAULT NULL,
  `jml_orang` int DEFAULT '0',
  `harga_orang` int DEFAULT '0',
  `subtotal` int NOT NULL,
  `grand_total` int NOT NULL,
  `tgl_invoice` datetime NOT NULL,
  `metode` varchar(50) DEFAULT 'cash',
  `bank_tujuan` varchar(50) DEFAULT NULL,
  `dibayar` int DEFAULT '0',
  `kembalian` int DEFAULT '0',
  `status_bayar` varchar(20) NOT NULL DEFAULT 'DP'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`no_invoice`, `kode_order`, `id_paket`, `jml_orang`, `harga_orang`, `subtotal`, `grand_total`, `tgl_invoice`, `metode`, `bank_tujuan`, `dibayar`, `kembalian`, `status_bayar`) VALUES
('JKWPRBW170126HBG', 'JKW170126JPB', 2, 10, 20000, 350000, 350000, '2026-01-17 09:53:17', 'transfer', 'BRI', 350000, 0, 'Lunas'),
('JKWPRBW200126EQQ', 'JKW200126KXY', 4, 12, 20000, 590000, 590000, '2026-01-20 04:55:37', 'transfer', 'Mandiri', 590000, 0, 'Lunas');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `kode_order` varchar(20) NOT NULL,
  `tgl_order` datetime NOT NULL,
  `est_selesai` datetime DEFAULT NULL,
  `catatan` text,
  `status_order` enum('pending','proses','selesai') DEFAULT 'pending',
  `id_pelanggan` int DEFAULT NULL,
  `id_paket` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`kode_order`, `tgl_order`, `est_selesai`, `catatan`, `status_order`, `id_pelanggan`, `id_paket`, `created_at`, `updated_at`) VALUES
('JKW170126JPB', '2026-01-17 16:52:00', '2026-01-30 16:52:00', 'WLEE', 'selesai', 11, 2, NULL, NULL),
('JKW200126KXY', '2026-01-20 11:54:00', '2026-01-30 16:54:00', 'afafassf', 'selesai', 12, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `paket`
--

CREATE TABLE `paket` (
  `id_paket` int NOT NULL,
  `kode_paket` varchar(20) DEFAULT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `harga_paket` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `paket`
--

INSERT INTO `paket` (`id_paket`, `kode_paket`, `nama_paket`, `harga_paket`) VALUES
(1, 'PSG-001', 'Paket Studio Group 8rp', 150000),
(2, 'PSG-002', 'Paket Studio Couple 6rp', 150000),
(3, 'PSG-003', 'Paket Studio Baby Newborn', 200000),
(4, 'PSG-004', 'Paket Outdoor Prewedding', 350000),
(5, 'PSG-005', 'Paket Keluarga 10rp', 250000),
(6, 'PSG-006', 'Paket Graduation Studio', 180000),
(7, 'PSG-007', 'Paket Pas Foto 4x6', 75000),
(8, 'PSG-008', 'Paket Produk Komersial', 500000),
(9, 'PSG-009', 'Paket Dokumentasi Event Indoor', 400000),
(10, 'PSG-010', 'Paket Dokumentasi Event Outdoor', 650000),
(11, 'PSG-026', 'Paket Wedding', 150000);

-- --------------------------------------------------------

--
-- Table structure for table `pelanggan`
--

CREATE TABLE `pelanggan` (
  `id_pelanggan` int NOT NULL,
  `nama_pelanggan` varchar(100) NOT NULL,
  `telp_pelanggan` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pelanggan`
--

INSERT INTO `pelanggan` (`id_pelanggan`, `nama_pelanggan`, `telp_pelanggan`) VALUES
(11, 'Sandika', '09172178'),
(12, 'adad212', '23232');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`no_invoice`),
  ADD KEY `kode_order` (`kode_order`),
  ADD KEY `id_paket` (`id_paket`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`kode_order`),
  ADD KEY `id_pelanggan` (`id_pelanggan`),
  ADD KEY `fk_orders_paket` (`id_paket`);

--
-- Indexes for table `paket`
--
ALTER TABLE `paket`
  ADD PRIMARY KEY (`id_paket`);

--
-- Indexes for table `pelanggan`
--
ALTER TABLE `pelanggan`
  ADD PRIMARY KEY (`id_pelanggan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `paket`
--
ALTER TABLE `paket`
  MODIFY `id_paket` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pelanggan`
--
ALTER TABLE `pelanggan`
  MODIFY `id_pelanggan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`kode_order`) REFERENCES `orders` (`kode_order`),
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`id_paket`) REFERENCES `paket` (`id_paket`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_paket` FOREIGN KEY (`id_paket`) REFERENCES `paket` (`id_paket`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_pelanggan`) REFERENCES `pelanggan` (`id_pelanggan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
