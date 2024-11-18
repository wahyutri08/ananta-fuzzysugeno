-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2024 at 07:55 PM
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

--
-- Dumping data for table `hasil_fuzzy`
--

INSERT INTO `hasil_fuzzy` (`id_hasil`, `user_id`, `id_siswa`, `nis`, `nama_siswa`, `nilai_uts`, `nilai_uas`, `keaktifan`, `penghasilan`, `nilai_fuzzy`, `keterangan`, `date_report`) VALUES
(262, 13, 36, '1102', 'Arfan Jumelar Subangkit', '90', '35', '85', '2000000', '65', 'Layak', '2024-11-18'),
(263, 13, 36, '1102', 'Arfan Jumelar Subangkit', '34', '35', '85', '2000000', '55', 'Layak', '2024-11-18'),
(264, 13, 36, '1102', 'Arfan Jumelar Subangkit', '97', '70', '35', '6000000', '45', 'Tidak Layak', '2024-11-18');

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

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id_penilaian`, `user_id`, `id_siswa`, `id_variabel`, `nilai`) VALUES
(195, 13, 36, 1, 97),
(196, 13, 36, 2, 70),
(197, 13, 36, 3, 35),
(198, 13, 36, 4, 6000000);

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
(83, 'Tinggi', 'Tinggi', 'Tinggi', 'Rendah', 100, 'Layak'),
(84, 'Tinggi', 'Tinggi', 'Tinggi', 'Sedang', 90, 'Layak'),
(85, 'Tinggi', 'Tinggi', 'Tinggi', 'Tinggi', 80, 'Layak'),
(86, 'Tinggi', 'Tinggi', 'Sedang', 'Rendah', 85, 'Layak'),
(87, 'Tinggi', 'Tinggi', 'Sedang', 'Sedang', 75, 'Layak'),
(88, 'Tinggi', 'Tinggi', 'Sedang', 'Tinggi', 65, 'Tidak Layak'),
(89, 'Tinggi', 'Tinggi', 'Rendah', 'Rendah', 70, 'Layak'),
(90, 'Tinggi', 'Tinggi', 'Rendah', 'Sedang', 60, 'Tidak Layak'),
(91, 'Tinggi', 'Tinggi', 'Rendah', 'Tinggi', 50, 'Tidak Layak'),
(92, 'Tinggi', 'Sedang', 'Tinggi', 'Rendah', 95, 'Layak'),
(93, 'Tinggi', 'Sedang', 'Tinggi', 'Sedang', 85, 'Layak'),
(94, 'Tinggi', 'Sedang', 'Tinggi', 'Tinggi', 75, 'Layak'),
(95, 'Tinggi', 'Sedang', 'Sedang', 'Rendah', 80, 'Layak'),
(96, 'Tinggi', 'Sedang', 'Sedang', 'Sedang', 70, 'Layak'),
(97, 'Tinggi', 'Sedang', 'Sedang', 'Tinggi', 60, 'Tidak Layak'),
(98, 'Tinggi', 'Sedang', 'Rendah', 'Rendah', 65, 'Tidak Layak'),
(99, 'Tinggi', 'Sedang', 'Rendah', 'Sedang', 55, 'Tidak Layak'),
(100, 'Tinggi', 'Sedang', 'Rendah', 'Tinggi', 45, 'Tidak Layak'),
(101, 'Tinggi', 'Rendah', 'Tinggi', 'Rendah', 75, 'Layak'),
(102, 'Tinggi', 'Rendah', 'Tinggi', 'Sedang', 65, 'Tidak Layak'),
(103, 'Tinggi', 'Rendah', 'Tinggi', 'Tinggi', 55, 'Tidak Layak'),
(104, 'Tinggi', 'Rendah', 'Sedang', 'Rendah', 60, 'Tidak Layak'),
(105, 'Tinggi', 'Rendah', 'Sedang', 'Sedang', 50, 'Tidak Layak'),
(106, 'Tinggi', 'Rendah', 'Sedang', 'Tinggi', 40, 'Tidak Layak'),
(107, 'Tinggi', 'Rendah', 'Rendah', 'Rendah', 45, 'Tidak Layak'),
(108, 'Tinggi', 'Rendah', 'Rendah', 'Sedang', 35, 'Tidak Layak'),
(109, 'Tinggi', 'Rendah', 'Rendah', 'Tinggi', 25, 'Tidak Layak'),
(110, 'Sedang', 'Tinggi', 'Tinggi', 'Rendah', 90, 'Layak'),
(111, 'Sedang', 'Tinggi', 'Tinggi', 'Sedang', 80, 'Layak'),
(112, 'Sedang', 'Tinggi', 'Tinggi', 'Tinggi', 70, 'Layak'),
(113, 'Sedang', 'Tinggi', 'Sedang', 'Rendah', 75, 'Layak'),
(114, 'Sedang', 'Tinggi', 'Sedang', 'Sedang', 65, 'Tidak Layak'),
(115, 'Sedang', 'Tinggi', 'Sedang', 'Tinggi', 55, 'Tidak Layak'),
(116, 'Sedang', 'Tinggi', 'Rendah', 'Rendah', 60, 'Tidak Layak'),
(117, 'Sedang', 'Tinggi', 'Rendah', 'Sedang', 50, 'Tidak Layak'),
(118, 'Sedang', 'Tinggi', 'Rendah', 'Tinggi', 40, 'Tidak Layak'),
(119, 'Sedang', 'Sedang', 'Tinggi', 'Rendah', 80, 'Layak'),
(120, 'Sedang', 'Sedang', 'Tinggi', 'Sedang', 70, 'Layak'),
(121, 'Sedang', 'Sedang', 'Tinggi', 'Tinggi', 60, 'Tidak Layak'),
(122, 'Sedang', 'Sedang', 'Sedang', 'Rendah', 70, 'Layak'),
(123, 'Sedang', 'Sedang', 'Sedang', 'Sedang', 60, 'Tidak Layak'),
(124, 'Sedang', 'Sedang', 'Sedang', 'Tinggi', 50, 'Tidak Layak'),
(125, 'Sedang', 'Sedang', 'Rendah', 'Rendah', 55, 'Tidak Layak'),
(126, 'Sedang', 'Sedang', 'Rendah', 'Sedang', 45, 'Tidak Layak'),
(127, 'Sedang', 'Sedang', 'Rendah', 'Tinggi', 35, 'Tidak Layak'),
(128, 'Sedang', 'Rendah', 'Tinggi', 'Rendah', 70, 'Layak'),
(129, 'Sedang', 'Rendah', 'Tinggi', 'Sedang', 60, 'Tidak Layak'),
(130, 'Sedang', 'Rendah', 'Tinggi', 'Tinggi', 50, 'Tidak Layak'),
(131, 'Sedang', 'Rendah', 'Sedang', 'Rendah', 55, 'Tidak Layak'),
(132, 'Sedang', 'Rendah', 'Sedang', 'Sedang', 45, 'Tidak Layak'),
(133, 'Sedang', 'Rendah', 'Sedang', 'Tinggi', 35, 'Tidak Layak'),
(134, 'Sedang', 'Rendah', 'Rendah', 'Rendah', 40, 'Tidak Layak'),
(135, 'Sedang', 'Rendah', 'Rendah', 'Sedang', 30, 'Tidak Layak'),
(136, 'Sedang', 'Rendah', 'Rendah', 'Tinggi', 20, 'Tidak Layak'),
(137, 'Rendah', 'Tinggi', 'Tinggi', 'Rendah', 75, 'Layak'),
(138, 'Rendah', 'Tinggi', 'Tinggi', 'Sedang', 65, 'Tidak Layak'),
(139, 'Rendah', 'Tinggi', 'Tinggi', 'Tinggi', 55, 'Tidak Layak'),
(140, 'Rendah', 'Tinggi', 'Sedang', 'Rendah', 60, 'Tidak Layak'),
(141, 'Rendah', 'Tinggi', 'Sedang', 'Sedang', 50, 'Tidak Layak'),
(142, 'Rendah', 'Tinggi', 'Sedang', 'Tinggi', 40, 'Tidak Layak'),
(143, 'Rendah', 'Tinggi', 'Rendah', 'Rendah', 45, 'Tidak Layak'),
(144, 'Rendah', 'Tinggi', 'Rendah', 'Sedang', 35, 'Tidak Layak'),
(145, 'Rendah', 'Tinggi', 'Rendah', 'Tinggi', 25, 'Tidak Layak'),
(146, 'Rendah', 'Sedang', 'Tinggi', 'Rendah', 70, 'Layak'),
(147, 'Rendah', 'Sedang', 'Tinggi', 'Sedang', 60, 'Tidak Layak'),
(148, 'Rendah', 'Sedang', 'Tinggi', 'Tinggi', 50, 'Tidak Layak'),
(149, 'Rendah', 'Sedang', 'Sedang', 'Rendah', 55, 'Tidak Layak'),
(150, 'Rendah', 'Sedang', 'Sedang', 'Sedang', 45, 'Tidak Layak'),
(151, 'Rendah', 'Sedang', 'Sedang', 'Tinggi', 35, 'Tidak Layak'),
(152, 'Rendah', 'Sedang', 'Rendah', 'Rendah', 40, 'Tidak Layak'),
(153, 'Rendah', 'Sedang', 'Rendah', 'Sedang', 30, 'Tidak Layak'),
(154, 'Rendah', 'Sedang', 'Rendah', 'Tinggi', 20, 'Tidak Layak'),
(155, 'Rendah', 'Rendah', 'Tinggi', 'Rendah', 65, 'Tidak Layak'),
(156, 'Rendah', 'Rendah', 'Tinggi', 'Sedang', 55, 'Tidak Layak'),
(157, 'Rendah', 'Rendah', 'Tinggi', 'Tinggi', 45, 'Tidak Layak'),
(158, 'Rendah', 'Rendah', 'Sedang', 'Rendah', 50, 'Tidak Layak'),
(159, 'Rendah', 'Rendah', 'Sedang', 'Sedang', 40, 'Tidak Layak'),
(160, 'Rendah', 'Rendah', 'Sedang', 'Tinggi', 30, 'Tidak Layak'),
(161, 'Rendah', 'Rendah', 'Rendah', 'Rendah', 25, 'Tidak Layak'),
(162, 'Rendah', 'Rendah', 'Rendah', 'Sedang', 15, 'Tidak Layak'),
(163, 'Rendah', 'Rendah', 'Rendah', 'Tinggi', 10, 'Tidak Layak');

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

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `user_id`, `nis`, `nama_siswa`, `alamat`, `tanggal_lahir`, `kelas`, `jenis_kelamin`, `no_telfon`, `email`) VALUES
(36, 13, '1102', 'Arfan Jumelar Subangkit', 'Jl Pamulang', '2004-02-18', '12A', 'Laki-Laki', '098098098098', 'arfan@email.com');

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
(3, 'ananta.dicapriyo', 'ananta@admin.com', 'Ananta Dicapriyo', '$2y$10$VBIj.EPBimoZyMWbtH8SiOfa7Euo6QI4nsKgXHdznTvAuuP.Inv/O', 'Admin', 'Aktif', '673a1f5062963.jpg'),
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
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;

--
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=199;

--
-- AUTO_INCREMENT for table `rule_fuzzy`
--
ALTER TABLE `rule_fuzzy`
  MODIFY `id_rule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=164;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

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
