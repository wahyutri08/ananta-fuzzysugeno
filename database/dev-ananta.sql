-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 06:55 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dev-ananta`
--

-- --------------------------------------------------------

--
-- Table structure for table `hasil_fuzzy`
--

CREATE TABLE `hasil_fuzzy` (
  `id_hasil` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `id_siswa` int(11) DEFAULT NULL,
  `nis` varchar(250) NOT NULL,
  `nama_siswa` varchar(250) NOT NULL,
  `nilai_uts` varchar(250) NOT NULL,
  `nilai_uas` varchar(250) NOT NULL,
  `keaktifan` varchar(250) NOT NULL,
  `penghasilan` varchar(250) NOT NULL,
  `nilai_fuzzy` varchar(250) NOT NULL,
  `keterangan` varchar(250) NOT NULL,
  `date_report` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_variabel` int(11) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rule_fuzzy`
--

CREATE TABLE `rule_fuzzy` (
  `id_rule` int(11) NOT NULL,
  `nilai_uts` enum('Rendah','Sedang','Tinggi') NOT NULL,
  `nilai_uas` enum('Rendah','Sedang','Tinggi') NOT NULL,
  `nilai_keaktifan` enum('Rendah','Sedang','Tinggi') NOT NULL,
  `nilai_penghasilan` enum('Rendah','Sedang','Tinggi') NOT NULL,
  `nilai` float NOT NULL,
  `keterangan` enum('Layak','Tidak Layak') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rule_fuzzy`
--

INSERT INTO `rule_fuzzy` (`id_rule`, `nilai_uts`, `nilai_uas`, `nilai_keaktifan`, `nilai_penghasilan`, `nilai`, `keterangan`) VALUES
(7, 'Tinggi', 'Tinggi', 'Tinggi', 'Rendah', 100, 'Layak'),
(8, 'Rendah', 'Rendah', 'Rendah', 'Tinggi', 50, 'Tidak Layak'),
(9, 'Sedang', 'Sedang', 'Tinggi', 'Sedang', 75, 'Layak'),
(10, 'Sedang', 'Tinggi', 'Sedang', 'Sedang', 85, 'Layak'),
(11, 'Tinggi', 'Sedang', 'Rendah', 'Rendah', 70, 'Layak'),
(12, 'Rendah', 'Sedang', 'Rendah', 'Tinggi', 50, 'Tidak Layak'),
(13, 'Tinggi', 'Rendah', 'Tinggi', 'Sedang', 80, 'Layak'),
(14, 'Sedang', 'Rendah', 'Sedang', 'Rendah', 65, 'Layak'),
(15, 'Rendah', 'Rendah', 'Tinggi', 'Tinggi', 50, 'Tidak Layak'),
(16, 'Sedang', 'Tinggi', 'Rendah', 'Rendah', 70, 'Layak');

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `nis` varchar(250) NOT NULL,
  `nama_siswa` varchar(250) NOT NULL,
  `alamat` varchar(250) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `kelas` varchar(250) NOT NULL,
  `jenis_kelamin` enum('Laki-Laki','Perempuan') NOT NULL,
  `no_telfon` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `nama` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `role` enum('Admin','Staff') NOT NULL,
  `status` enum('Aktif','Tidak Aktif') NOT NULL,
  `avatar` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `nama`, `password`, `role`, `status`, `avatar`) VALUES
(3, 'ananta.dicapriyo', 'ananta@admin.com', 'Ananta Dicapriyo', '$2y$10$VBIj.EPBimoZyMWbtH8SiOfa7Euo6QI4nsKgXHdznTvAuuP.Inv/O', 'Admin', 'Aktif', '6712aef472d5f.jpg'),
(13, 'ananta1', 'staff@staff.com', 'Ananta', '$2y$10$AFoZktU.nJI.hpt//Vz86elnaAUdZYQln2Mvw8eZc7M9P0OaY4Bse', 'Staff', 'Aktif', '6710e8ea86177.jpg'),
(20, 'ananta2', 'ananta@staff.com', 'Ananta 2', '$2y$10$TQGYb.tGnDuS1Fj4f44Rgej/xMUl0XdL9KSdCkw.SlnGsd5d9kHHa', 'Staff', 'Tidak Aktif', '6730df8578e84.');

-- --------------------------------------------------------

--
-- Table structure for table `variabel`
--

CREATE TABLE `variabel` (
  `id_variabel` int(11) NOT NULL,
  `nama_variabel` varchar(250) NOT NULL,
  `kat_rendah` varchar(250) NOT NULL,
  `kat_sedang` varchar(250) NOT NULL,
  `kat_tinggi` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `variabel`
--

INSERT INTO `variabel` (`id_variabel`, `nama_variabel`, `kat_rendah`, `kat_sedang`, `kat_tinggi`) VALUES
(1, 'Nilai Ujian Tengah Semester (UTS)', '0-60', '60-80', '80-100'),
(2, 'Nilai Ujian Akhir Semester (UAS)', '0 - 60', '60 - 80', '80 - 100'),
(3, 'Nilai Keaktifan Sekolah (OSIS, Ekskul)', '0 - 50', '50 - 70', '70 - 100'),
(4, 'Penghasilan Orang Tua', '< Rp2.000.000', 'Rp2.000.000 - Rp5.000.000', '> Rp5.000.000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `hasil_fuzzy`
--
ALTER TABLE `hasil_fuzzy`
  ADD PRIMARY KEY (`id_hasil`),
  ADD KEY `fk_hasil_user` (`user_id`),
  ADD KEY `fk_hasil_siswa` (`id_siswa`);

--
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD KEY `fk_siswa` (`id_siswa`),
  ADD KEY `fk_variabel` (`id_variabel`),
  ADD KEY `fk_user_nilai` (`user_id`);

--
-- Indexes for table `rule_fuzzy`
--
ALTER TABLE `rule_fuzzy`
  ADD PRIMARY KEY (`id_rule`),
  ADD UNIQUE KEY `nilai_uts` (`nilai_uts`,`nilai_uas`,`nilai_keaktifan`,`nilai_penghasilan`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variabel`
--
ALTER TABLE `variabel`
  ADD PRIMARY KEY (`id_variabel`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `hasil_fuzzy`
--
ALTER TABLE `hasil_fuzzy`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=260;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `rule_fuzzy`
--
ALTER TABLE `rule_fuzzy`
  MODIFY `id_rule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `variabel`
--
ALTER TABLE `variabel`
  MODIFY `id_variabel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `hasil_fuzzy`
--
ALTER TABLE `hasil_fuzzy`
  ADD CONSTRAINT `fk_hasil_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_hasil_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `fk_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`),
  ADD CONSTRAINT `fk_user_nilai` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_variabel` FOREIGN KEY (`id_variabel`) REFERENCES `variabel` (`id_variabel`);

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
