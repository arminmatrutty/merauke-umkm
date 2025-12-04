-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2025 at 11:07 AM
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
-- Database: `db_crowdfunding_umkm`
--

-- --------------------------------------------------------

--
-- Table structure for table `donasi`
--

CREATE TABLE `donasi` (
  `id_donasi` int(11) NOT NULL,
  `id_investor` int(11) NOT NULL,
  `id_proposal` int(11) NOT NULL,
  `jumlah_donasi` decimal(15,2) NOT NULL,
  `tanggal_donasi` datetime DEFAULT current_timestamp(),
  `metode_pembayaran` enum('transfer','cash','lainnya') DEFAULT 'transfer',
  `status` enum('pending','berhasil','gagal') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `investor_profile`
--

CREATE TABLE `investor_profile` (
  `id_investor` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `perusahaan` varchar(150) DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investor_profile`
--

INSERT INTO `investor_profile` (`id_investor`, `id_user`, `perusahaan`, `jabatan`, `alamat`, `no_hp`, `foto_profil`, `tanggal_update`) VALUES
(1, 3, 'PT Merauke Investindo', 'Direktur Utama', 'Jl. Raya Mandala No.10, Merauke', '081345678901', NULL, '2025-11-11 19:17:39');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_investasi`
--

CREATE TABLE `jadwal_investasi` (
  `id_jadwal` int(11) NOT NULL,
  `id_investor` int(11) NOT NULL,
  `id_proposal` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `waktu` time NOT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('menunggu konfirmasi admin','disetujui','ditolak') DEFAULT 'menunggu konfirmasi admin',
  `tanggal_ditetapkan` datetime DEFAULT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal_investasi`
--

INSERT INTO `jadwal_investasi` (`id_jadwal`, `id_investor`, `id_proposal`, `tanggal`, `waktu`, `catatan`, `status`, `tanggal_ditetapkan`, `tanggal_dibuat`) VALUES
(1, 1, 1, '2025-11-15', '11:30:00', 'Via Zoom Online', '', '2025-11-12 02:38:51', '2025-11-11 17:23:32');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_pertemuan`
--

CREATE TABLE `jadwal_pertemuan` (
  `id_jadwal` int(11) NOT NULL,
  `id_proposal` int(11) NOT NULL,
  `id_investor` int(11) NOT NULL,
  `tanggal_usulan` datetime NOT NULL,
  `tanggal_ditetapkan` datetime DEFAULT NULL,
  `status` enum('diajukan','ditetapkan','selesai') DEFAULT 'diajukan',
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `log_aktivitas`
--

CREATE TABLE `log_aktivitas` (
  `id_log` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `aktivitas` text DEFAULT NULL,
  `tanggal_log` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `tanggal_log`) VALUES
(1, 1, 'Menyetujui proposal id=1', '2025-11-12 02:01:43'),
(2, 1, 'Menetapkan jadwal investasi id=1', '2025-11-12 02:36:14'),
(3, 1, 'Menetapkan jadwal investasi id=1', '2025-11-12 02:36:16'),
(4, 1, 'Menetapkan jadwal investasi id=1', '2025-11-12 02:38:51'),
(5, 1, 'Menyetujui proposal id=2', '2025-11-12 12:11:02');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id_notif` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `judul` varchar(100) NOT NULL,
  `pesan` text DEFAULT NULL,
  `isi` text NOT NULL,
  `tanggal` datetime DEFAULT current_timestamp(),
  `status` enum('belum_dibaca','dibaca') DEFAULT 'belum_dibaca'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id_notif`, `id_user`, `judul`, `pesan`, `isi`, `tanggal`, `status`) VALUES
(1, 2, 'Jadwal Investasi Ditetapkan', 'Proposal \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah dijadwalkan pertemuan oleh admin.', '', '2025-11-12 02:38:51', 'dibaca'),
(2, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:04:48', 'dibaca'),
(3, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:05:18', 'dibaca'),
(4, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:05:40', 'dibaca'),
(5, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:05:50', 'dibaca'),
(6, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:06:01', 'dibaca'),
(7, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:06:18', 'dibaca'),
(8, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:06:45', 'dibaca'),
(9, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:20:54', 'dibaca'),
(10, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:21:00', 'dibaca'),
(11, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:28:00', 'dibaca'),
(12, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:30:28', 'dibaca'),
(13, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:30:29', 'dibaca'),
(14, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:30:30', 'dibaca'),
(15, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:30:30', 'dibaca'),
(16, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:30:30', 'dibaca'),
(17, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:30:30', 'dibaca'),
(18, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:30:31', 'dibaca'),
(19, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:30:31', 'dibaca'),
(20, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:30:31', 'dibaca'),
(21, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:30:31', 'dibaca'),
(22, 2, 'Proposal Diterima oleh Investor', 'Selamat! Proposal Anda berjudul \'KEDAI KOPI & ROTI SAGU NUSANTARA\' telah diterima oleh investor dan akan segera dijadwalkan untuk pertemuan.', '', '2025-11-12 03:30:32', 'dibaca');

-- --------------------------------------------------------

--
-- Table structure for table `proposal`
--

CREATE TABLE `proposal` (
  `id_proposal` int(11) NOT NULL,
  `id_umkm` int(11) NOT NULL,
  `judul_proposal` varchar(200) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `modal_dibutuhkan` decimal(15,2) DEFAULT NULL,
  `tujuan_penggunaan` text DEFAULT NULL,
  `estimasi_keuntungan` text DEFAULT NULL,
  `keunikan_usaha` text DEFAULT NULL,
  `strategi_pemasaran` text DEFAULT NULL,
  `tanggal_pengajuan` datetime DEFAULT current_timestamp(),
  `status` enum('menunggu','revisi','disetujui','ditolak') DEFAULT 'menunggu',
  `tanggal_respon` datetime DEFAULT NULL,
  `foto_usaha` varchar(255) DEFAULT NULL,
  `catatan_revisi` text DEFAULT NULL,
  `views` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proposal`
--

INSERT INTO `proposal` (`id_proposal`, `id_umkm`, `judul_proposal`, `deskripsi`, `modal_dibutuhkan`, `tujuan_penggunaan`, `estimasi_keuntungan`, `keunikan_usaha`, `strategi_pemasaran`, `tanggal_pengajuan`, `status`, `tanggal_respon`, `foto_usaha`, `catatan_revisi`, `views`) VALUES
(1, 1, 'KEDAI KOPI & ROTI SAGU NUSANTARA', 'Kedai Kopi & Roti Sagu Nusantara merupakan usaha kuliner yang menyajikan kopi lokal Merauke dan aneka olahan roti berbahan dasar sagu — bahan pangan khas Papua yang kaya gizi dan ramah lingkungan.\r\nUsaha ini mengusung konsep modern-culture café yang menyatukan nuansa tradisional Papua dengan kenyamanan kafe masa kini. Target pasar meliputi masyarakat lokal, pelajar, pegawai, dan wisatawan yang datang ke Merauke.\r\n\r\nProduk unggulan meliputi:\r\nKopi arabika Merauke (hot & iced)\r\nRoti sagu coklat, keju, dan kelapa\r\nEs kopi sagu\r\nPaket sarapan lokal (kopi + roti sagu)\r\nSnack ringan berbahan sagu dan kelapa', 32000000.00, 'Dana bantuan akan digunakan untuk:\r\nMenyewa dan menata lokasi usaha.\r\nMembeli perlengkapan dapur dan mesin kopi.\r\nMembeli bahan baku dan kemasan ramah lingkungan.\r\nMelakukan promosi awal melalui media sosial dan banner lokal.\r\nPelatihan singkat untuk karyawan lokal agar mampu mengelola usaha mandiri.', '4–5 bulan Rp 18.000.000', 'Mengangkat sagu sebagai bahan lokal khas Papua ke ranah kuliner modern.\r\nMengutamakan produk lokal Merauke (kopi dan bahan pangan).\r\nKonsep kafe ramah lingkungan: kemasan kertas & sedotan bambu.\r\nMenciptakan lapangan kerja bagi pemuda lokal.', 'Media Sosial: Promosi melalui Instagram, Facebook, dan TikTok dengan konten menarik (foto, video pembuatan kopi & roti).\r\nPromo Pembukaan: Diskon 20% di bulan pertama.\r\nKerja Sama Komunitas: Event kecil bersama komunitas pemuda, pelajar, dan wisatawan lokal.\r\nBranding Lokal: Desain interior bertema Papua untuk memperkuat identitas budaya.\r\nProgram Loyalti: Kartu pelanggan “5x beli kopi, gratis 1”.', '2025-11-12 02:00:15', 'disetujui', '2025-11-12 02:01:43', '1762880415_ChatGPT Image Nov 12, 2025, 02_00_02 AM.png', NULL, 2),
(2, 3, 'Catering Sehat Nusantara – Usaha Kuliner Sehat dan Praktis di Kabupaten Merauke', 'Catering Sehat Nusantara merupakan usaha kuliner yang menyediakan makanan sehat dan praktis untuk masyarakat di Kabupaten Merauke. Fokus usaha ini adalah menyediakan menu harian yang bergizi, terjangkau, dan menggunakan bahan lokal segar. Target pasar utama adalah pekerja kantoran, mahasiswa, dan keluarga yang menginginkan makanan sehat tanpa repot memasak.\r\n\r\nJenis layanan:\r\nPaket makan siang sehat harian\r\nPaket catering acara kecil\r\nLayanan pesan antar ke rumah atau kantor', 20000000.00, 'Dana dari proposal ini akan digunakan untuk:\r\nPembelian peralatan dapur yang memadai.\r\nPembelian bahan baku awal dan kemasan.\r\nPromosi usaha melalui media sosial dan leaflet.\r\nMenyediakan layanan pengiriman agar dapat menjangkau lebih banyak pelanggan.', '4–5 bulan Rp 10.000.000', 'Menu sehat dengan bahan lokal Merauke.\r\nPaket praktis siap santap untuk pekerja dan keluarga.\r\nLayanan antar hingga rumah/kantor.\r\nFokus pada cita rasa Nusantara yang sehat dan lezat.', 'Media Sosial: Instagram, Facebook, dan WhatsApp Business untuk promosi harian dan testimoni pelanggan.\r\n\r\nKerja Sama dengan Perusahaan & Sekolah: Menawarkan paket langganan untuk karyawan dan kantin sekolah.\r\n\r\nPromosi Offline: Leaflet, spanduk, dan demo masak di pasar lokal.\r\n\r\nDiskon & Loyalty Program: Diskon untuk pelanggan baru dan program poin bagi pelanggan tetap.', '2025-11-12 12:09:40', 'disetujui', '2025-11-12 12:11:02', '1762916980_ChatGPT Image Nov 12, 2025, 12_02_54 PM.png', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `umkm_profile`
--

CREATE TABLE `umkm_profile` (
  `id_umkm` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `nama_usaha` varchar(150) NOT NULL,
  `bidang_usaha` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `foto_usaha` varchar(255) DEFAULT NULL,
  `tanggal_update` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `umkm_profile`
--

INSERT INTO `umkm_profile` (`id_umkm`, `id_user`, `nama_usaha`, `bidang_usaha`, `deskripsi`, `alamat`, `no_hp`, `foto_usaha`, `tanggal_update`) VALUES
(1, 2, 'Usaha Noken Papua', 'Kerajinan Tangan', 'Produksi tas noken tradisional khas Papua', 'Jl. Mandala Merauke', '081234567890', NULL, '2025-11-11 19:17:39'),
(3, 10, 'Catering Sehat Nusantara – Usaha Kuliner Sehat dan Praktis di Kabupaten Merauke', 'Kuliner Makanan', 'Catering Sehat Nusantara merupakan usaha kuliner yang menyediakan makanan sehat dan praktis untuk masyarakat di Kabupaten Merauke. Fokus usaha ini adalah menyediakan menu harian yang bergizi, terjangkau, dan menggunakan bahan lokal segar. Target pasar utama adalah pekerja kantoran, mahasiswa, dan keluarga yang menginginkan makanan sehat tanpa repot memasak.\r\n\r\nJenis layanan:\r\nPaket makan siang sehat harian\r\nPaket catering acara kecil\r\nLayanan pesan antar ke rumah atau kantor', 'Jl.Pendidikan', '082199032740', 'umkm_6913f9e6068ef.png', '2025-11-12 04:07:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','umkm','investor') NOT NULL,
  `status` enum('aktif','nonaktif') DEFAULT 'aktif',
  `tanggal_daftar` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `nama_lengkap`, `email`, `username`, `password`, `role`, `status`, `tanggal_daftar`) VALUES
(1, 'Admin Dinas', 'admin@umkmmerauke.go.id', 'admin', '0192023a7bbd73250516f069df18b500', 'admin', 'aktif', '2025-11-11 19:17:39'),
(2, 'Pelaku UMKM 1', 'umkm1@gmail.com', 'umkm1', '57eba66a7fd977dfda83c680f7d3722d', 'umkm', 'aktif', '2025-11-11 19:17:39'),
(3, 'Investor 1', 'investor1@gmail.com', 'investor1', '6380f1dd02e9a9f477b82c29696fd1d2', 'investor', 'aktif', '2025-11-11 19:17:39'),
(4, 'investor2', '', 'investor2', '6380f1dd02e9a9f477b82c29696fd1d2', 'investor', 'aktif', '2025-11-12 10:02:45'),
(9, 'investor3', 'armin2016srngg@gmail.com', 'investor3', '6380f1dd02e9a9f477b82c29696fd1d2', 'umkm', 'aktif', '2025-11-12 10:37:43'),
(10, 'umkm2', 'umkm2@gmail.com', 'umkm2', '57eba66a7fd977dfda83c680f7d3722d', 'umkm', 'aktif', '2025-11-12 11:39:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donasi`
--
ALTER TABLE `donasi`
  ADD PRIMARY KEY (`id_donasi`),
  ADD KEY `id_investor` (`id_investor`),
  ADD KEY `id_proposal` (`id_proposal`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `investor_profile`
--
ALTER TABLE `investor_profile`
  ADD PRIMARY KEY (`id_investor`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `jadwal_investasi`
--
ALTER TABLE `jadwal_investasi`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_investor` (`id_investor`),
  ADD KEY `id_proposal` (`id_proposal`);

--
-- Indexes for table `jadwal_pertemuan`
--
ALTER TABLE `jadwal_pertemuan`
  ADD PRIMARY KEY (`id_jadwal`),
  ADD KEY `id_proposal` (`id_proposal`),
  ADD KEY `id_investor` (`id_investor`);

--
-- Indexes for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id_notif`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `proposal`
--
ALTER TABLE `proposal`
  ADD PRIMARY KEY (`id_proposal`),
  ADD KEY `id_umkm` (`id_umkm`);

--
-- Indexes for table `umkm_profile`
--
ALTER TABLE `umkm_profile`
  ADD PRIMARY KEY (`id_umkm`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donasi`
--
ALTER TABLE `donasi`
  MODIFY `id_donasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `investor_profile`
--
ALTER TABLE `investor_profile`
  MODIFY `id_investor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal_investasi`
--
ALTER TABLE `jadwal_investasi`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal_pertemuan`
--
ALTER TABLE `jadwal_pertemuan`
  MODIFY `id_jadwal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id_notif` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `proposal`
--
ALTER TABLE `proposal`
  MODIFY `id_proposal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `umkm_profile`
--
ALTER TABLE `umkm_profile`
  MODIFY `id_umkm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `donasi`
--
ALTER TABLE `donasi`
  ADD CONSTRAINT `donasi_ibfk_1` FOREIGN KEY (`id_investor`) REFERENCES `investor_profile` (`id_investor`) ON DELETE CASCADE,
  ADD CONSTRAINT `donasi_ibfk_2` FOREIGN KEY (`id_proposal`) REFERENCES `proposal` (`id_proposal`) ON DELETE CASCADE;

--
-- Constraints for table `investor_profile`
--
ALTER TABLE `investor_profile`
  ADD CONSTRAINT `investor_profile_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_investasi`
--
ALTER TABLE `jadwal_investasi`
  ADD CONSTRAINT `jadwal_investasi_ibfk_1` FOREIGN KEY (`id_investor`) REFERENCES `investor_profile` (`id_investor`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_investasi_ibfk_2` FOREIGN KEY (`id_proposal`) REFERENCES `proposal` (`id_proposal`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_pertemuan`
--
ALTER TABLE `jadwal_pertemuan`
  ADD CONSTRAINT `jadwal_pertemuan_ibfk_1` FOREIGN KEY (`id_proposal`) REFERENCES `proposal` (`id_proposal`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_pertemuan_ibfk_2` FOREIGN KEY (`id_investor`) REFERENCES `investor_profile` (`id_investor`) ON DELETE CASCADE;

--
-- Constraints for table `log_aktivitas`
--
ALTER TABLE `log_aktivitas`
  ADD CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `proposal`
--
ALTER TABLE `proposal`
  ADD CONSTRAINT `proposal_ibfk_1` FOREIGN KEY (`id_umkm`) REFERENCES `umkm_profile` (`id_umkm`);

--
-- Constraints for table `umkm_profile`
--
ALTER TABLE `umkm_profile`
  ADD CONSTRAINT `umkm_profile_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
