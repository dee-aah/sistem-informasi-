-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Jun 2025 pada 13.58
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
-- Database: `bmkg_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggota`
--

CREATE TABLE `anggota` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `no_hp` text DEFAULT NULL,
  `tgl_bergabung` date DEFAULT NULL,
  `status` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `anggota`
--

INSERT INTO `anggota` (`id`, `nama`, `tgl_lahir`, `no_hp`, `tgl_bergabung`, `status`) VALUES
(2, 'Dede Ahmad M', '2004-05-06', '082215511904', '2025-06-12', 'Aktif'),
(3, 'Fahri Anugrah', '2004-07-03', '082345673969', '2025-06-12', 'Aktif'),
(5, 'Riki', '2008-07-12', '082257849495', '2025-06-12', 'Aktif'),
(6, 'Dani', '2007-06-11', '083578689977', '2025-06-27', 'Aktif'),
(7, 'Muhsin', '1995-01-17', '082346467851', '2025-06-12', 'Aktif'),
(9, 'Matin', '1989-06-12', '082235769088', '2025-06-12', 'Aktif');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `tempat` text DEFAULT NULL,
  `jawab` text DEFAULT NULL,
  `status` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kegiatan`
--

INSERT INTO `kegiatan` (`id`, `nama`, `tanggal`, `tempat`, `jawab`, `status`) VALUES
(2, 'Rapat Paripurna', '2025-07-02 08:00:00', 'Gor Sekolah', 'Hj Dede', 'Tersedia'),
(3, 'Muharaman', '2025-06-27 08:30:00', 'Masjid As-Syukur', 'Hj Dede', 'Tersedia'),
(5, 'Demo', '2025-06-26 09:32:00', 'Gedung DPR-RI', 'Hj Dede', 'Tersedia');

-- --------------------------------------------------------

--
-- Struktur dari tabel `keuangan`
--

CREATE TABLE `keuangan` (
  `id` int(11) NOT NULL,
  `waktu` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `pemasukan` int(20) DEFAULT NULL,
  `pengeluaran` int(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `keuangan`
--

INSERT INTO `keuangan` (`id`, `waktu`, `keterangan`, `pemasukan`, `pengeluaran`) VALUES
(6, '2025-06-12', 'Bela Sungkawa', 0, 250000),
(7, '2025-06-21', 'Iuran Dede', 100000, 0),
(8, '2025-06-20', 'Iuran Ahmad', 100000, 0),
(9, '2025-06-20', 'Iuran Asep', 100000, 0),
(10, '2025-06-20', 'Iuran Rizal', 100000, 0);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `keuangan`
--
ALTER TABLE `keuangan`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `keuangan`
--
ALTER TABLE `keuangan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
