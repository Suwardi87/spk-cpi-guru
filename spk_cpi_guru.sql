-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 28 Jul 2025 pada 04.42
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spk_cpi_guru`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `guru`
--

CREATE TABLE `guru` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `guru`
--

INSERT INTO `guru` (`id`, `nama`, `jabatan`) VALUES
(1, 'Gusmelda Fitri, S.Ag', 'Kepala Sekolah'),
(2, 'Febria Noza, S.Pd I', 'Guru'),
(3, 'Susi Elmi, S.Pd I', 'Guru'),
(4, 'Riska Fhadillah, S.Pd', 'Guru'),
(5, 'Ernawilis Emardi, S.Pd', 'Guru'),
(6, 'Evi Rahayu, S.Pd', 'Guru'),
(7, 'Reni Ekaputri, S.Pd', 'Guru'),
(8, 'Rina Febriana, S.Pd', 'Guru'),
(9, 'Reni Puspita Ningsih, S.Pd', 'Guru'),
(10, 'Nurfatmawati, S.Pd', 'Guru'),
(11, 'Ridani, S.Pd', 'Guru'),
(12, 'Delvita Hendriaty, S.Pd', 'Guru'),
(13, 'Maiwasti, S.Pd', 'Guru'),
(14, 'Santi Gustina, S.E', 'Guru'),
(15, 'Eniza Sefiawati, A.Md', 'Guru'),
(16, 'Drs. Febrizal Zuardi', 'Guru'),
(17, 'Rismayanti, S.Fil.I', 'Guru'),
(18, 'Syerli Fitriani, S.Pd', 'Guru');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int(11) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `bobot` decimal(5,2) NOT NULL,
  `tren` enum('positif','negatif','netral') DEFAULT 'netral'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kriteria`
--

INSERT INTO `kriteria` (`id`, `nama_kriteria`, `bobot`, `tren`) VALUES
(1, 'Absensi', 0.10, 'positif'),
(2, 'Kedisiplinan', 0.25, 'positif'),
(3, 'Supervisi Kelas', 0.20, 'positif'),
(4, 'Dokumen Pembelajaran', 0.15, 'positif'),
(5, 'Observasi Kelas', 0.30, 'positif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengguna`
--

CREATE TABLE `pengguna` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kepala_sekolah','guru') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengguna`
--

INSERT INTO `pengguna` (`id`, `username`, `password`, `role`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin'),
(2, 'kepala_sekolah', 'eaed95826e34a1e91b051f3b56f5ff3d', 'kepala_sekolah'),
(3, 'guru', '9310f83135f238b04af729fec041cca8', 'guru');

-- --------------------------------------------------------

--
-- Struktur dari tabel `penilaian`
--

CREATE TABLE `penilaian` (
  `id` int(11) NOT NULL,
  `id_guru` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nilai` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `penilaian`
--

INSERT INTO `penilaian` (`id`, `id_guru`, `id_kriteria`, `nilai`) VALUES
(1, 1, 1, 100),
(2, 1, 2, 90),
(3, 1, 3, 95),
(4, 1, 4, 90),
(5, 1, 5, 95),
(6, 2, 1, 98),
(7, 2, 2, 85),
(8, 2, 3, 93),
(9, 2, 4, 88),
(10, 2, 5, 92),
(11, 3, 1, 95),
(12, 3, 2, 90),
(13, 3, 3, 95),
(14, 3, 4, 90),
(15, 3, 5, 95),
(16, 4, 1, 95),
(17, 4, 2, 85),
(18, 4, 3, 95),
(19, 4, 4, 90),
(20, 4, 5, 95),
(21, 5, 1, 90),
(22, 5, 2, 80),
(23, 5, 3, 90),
(24, 5, 4, 85),
(25, 5, 5, 90),
(26, 6, 1, 95),
(27, 6, 2, 85),
(28, 6, 3, 95),
(29, 6, 4, 90),
(30, 6, 5, 95),
(31, 7, 1, 97),
(32, 7, 2, 85),
(33, 7, 3, 95),
(34, 7, 4, 90),
(35, 7, 5, 95),
(36, 8, 1, 93),
(37, 8, 2, 85),
(38, 8, 3, 93),
(39, 8, 4, 88),
(40, 8, 5, 92),
(41, 9, 1, 98),
(42, 9, 2, 85),
(43, 9, 3, 95),
(44, 9, 4, 90),
(45, 9, 5, 95),
(46, 10, 1, 94),
(47, 10, 2, 85),
(48, 10, 3, 95),
(49, 10, 4, 90),
(50, 10, 5, 95),
(51, 11, 1, 96),
(52, 11, 2, 85),
(53, 11, 3, 95),
(54, 11, 4, 90),
(55, 11, 5, 95),
(56, 12, 1, 92),
(57, 12, 2, 85),
(58, 12, 3, 95),
(59, 12, 4, 90),
(60, 12, 5, 95),
(61, 13, 1, 93),
(62, 13, 2, 80),
(63, 13, 3, 95),
(64, 13, 4, 90),
(65, 13, 5, 95),
(66, 14, 1, 90),
(67, 14, 2, 85),
(68, 14, 3, 90),
(69, 14, 4, 85),
(70, 14, 5, 90),
(71, 15, 1, 92),
(72, 15, 2, 85),
(73, 15, 3, 95),
(74, 15, 4, 90),
(75, 15, 5, 95),
(76, 16, 1, 90),
(77, 16, 2, 85),
(78, 16, 3, 95),
(79, 16, 4, 90),
(80, 16, 5, 95),
(81, 17, 1, 95),
(82, 17, 2, 85),
(83, 17, 3, 95),
(84, 17, 4, 90),
(85, 17, 5, 95),
(86, 18, 1, 91),
(87, 18, 2, 85),
(88, 18, 3, 95),
(89, 18, 4, 90),
(90, 18, 5, 95);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_guru` (`id_guru`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `guru`
--
ALTER TABLE `guru`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pengguna`
--
ALTER TABLE `pengguna`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `penilaian`
--
ALTER TABLE `penilaian`
  ADD CONSTRAINT `penilaian_ibfk_1` FOREIGN KEY (`id_guru`) REFERENCES `guru` (`id`),
  ADD CONSTRAINT `penilaian_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `kriteria` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
