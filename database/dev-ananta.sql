-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2024 at 10:03 PM
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
-- Table structure for table `penilaian`
--

CREATE TABLE `penilaian` (
  `id_penilaian` int(11) NOT NULL,
  `id_siswa` int(11) NOT NULL,
  `id_variabel` int(11) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penilaian`
--

INSERT INTO `penilaian` (`id_penilaian`, `id_siswa`, `id_variabel`, `nilai`) VALUES
(63, 2, 1, 80),
(64, 2, 2, 85),
(65, 2, 3, 90),
(66, 2, 4, 50000000),
(67, 3, 1, 43),
(68, 3, 2, 23),
(69, 3, 3, 78),
(70, 3, 4, 500000);

-- --------------------------------------------------------

--
-- Table structure for table `rule_fuzzy`
--

CREATE TABLE `rule_fuzzy` (
  `id_rule` int(11) NOT NULL,
  `nilai_uts` varchar(250) NOT NULL,
  `nilai_uas` varchar(250) NOT NULL,
  `nilai_keaktifan` varchar(250) NOT NULL,
  `nilai_penghasilan` varchar(250) NOT NULL,
  `nilai` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rule_fuzzy`
--

INSERT INTO `rule_fuzzy` (`id_rule`, `nilai_uts`, `nilai_uas`, `nilai_keaktifan`, `nilai_penghasilan`, `nilai`) VALUES
(1, 'Tinggi', 'Tinggi', 'Tinggi', 'Rendah', 1),
(2, 'Rendah', 'Rendah', 'Rendah', 'Rendah', 0),
(3, 'Sedang', 'Sedang', 'Tinggi', 'Sedang', 1);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
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

INSERT INTO `siswa` (`id_siswa`, `nis`, `nama_siswa`, `alamat`, `tanggal_lahir`, `kelas`, `jenis_kelamin`, `no_telfon`, `email`) VALUES
(2, '181011450', 'Ahmad Rizki Hidayat', 'JL Satu Arah', '2000-01-01', '12A', 'Laki-Laki', '089777718192', 'ahmad@email.com'),
(3, '181011451', 'Arfan Jumelar Subangkit', 'Jl Dua Arah', '2000-01-02', '12A', 'Laki-Laki', '08961237817272', 'arfan@email.com');

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
  `role` varchar(250) NOT NULL,
  `avatar` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `nama`, `password`, `role`, `avatar`) VALUES
(3, 'ananta.dicapriyo', 'ananta@admin.com', 'Ananta Dicapriyo', '$2y$10$VBIj.EPBimoZyMWbtH8SiOfa7Euo6QI4nsKgXHdznTvAuuP.Inv/O', 'Admin', '670ea54b5b1b0.jpg'),
(13, 'staff1', 'staff@staff.com', 'Staff 1', '$2y$10$naciDpF2kGzM/5fCMohA3e2cAd0PT2zTigyqIF18tyXO4naDyZLtq', 'Staff', '670047c89172d.');

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
-- Indexes for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id_penilaian`),
  ADD KEY `fk_siswa` (`id_siswa`),
  ADD KEY `fk_variabel` (`id_variabel`);

--
-- Indexes for table `rule_fuzzy`
--
ALTER TABLE `rule_fuzzy`
  ADD PRIMARY KEY (`id_rule`),
  ADD UNIQUE KEY `nilai_uts` (`nilai_uts`,`nilai_uas`,`nilai_keaktifan`,`nilai_penghasilan`) USING HASH;

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

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
-- AUTO_INCREMENT for table `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id_penilaian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `rule_fuzzy`
--
ALTER TABLE `rule_fuzzy`
  MODIFY `id_rule` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `variabel`
--
ALTER TABLE `variabel`
  MODIFY `id_variabel` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `fk_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`),
  ADD CONSTRAINT `fk_variabel` FOREIGN KEY (`id_variabel`) REFERENCES `variabel` (`id_variabel`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
