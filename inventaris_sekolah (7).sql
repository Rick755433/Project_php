-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Apr 2025 pada 15.15
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
-- Database: `inventaris_sekolah`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'elektronik', 'jfjghjfhjgh'),
(2, 'mebel', 'drfhg'),
(3, '<script>   alert(\"Hello, World!\"); </script>', 'ddd');

-- --------------------------------------------------------

--
-- Struktur dari tabel `disposals`
--

CREATE TABLE `disposals` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `disposal_date` date NOT NULL,
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `qrcode_image` varchar(255) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `item_code` varchar(50) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `item_condition` varchar(255) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `barcode` varchar(255) DEFAULT NULL,
  `status` enum('bisadipinjam','tidakbisadipinjam') NOT NULL DEFAULT 'bisadipinjam',
  `total` int(11) NOT NULL,
  `repair_status` enum('tidak_diperbaiki','diperbaiki') NOT NULL DEFAULT 'tidak_diperbaiki'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `items`
--

INSERT INTO `items` (`id`, `qrcode_image`, `name`, `item_code`, `category_id`, `purchase_date`, `price`, `image`, `item_condition`, `room_id`, `quantity`, `barcode`, `status`, `total`, `repair_status`) VALUES
(78, NULL, 'kulkas', 'KUL-01-250413-X0N9L', 1, '2025-03-30', 1000.00, 'images.jpeg', 'rusak ringan', 3, 1, 'barcodes/qr_KUL-01-250413-X0N9L_78.png', 'bisadipinjam', 1, 'tidak_diperbaiki'),
(79, NULL, 'kulkas', 'KUL-01-250413-T6KAC', 1, '2025-03-30', 1000.00, 'images.jpeg', 'rusak ringan', 3, 2, 'barcodes/qr_KUL-01-250413-T6KAC_79.png', 'bisadipinjam', 1, 'tidak_diperbaiki'),
(80, NULL, 'kulkas', 'KUL-01-250413-6FHQ1', 1, '2025-04-12', 34555.00, 'images.jpeg', 'rusak ringan', 2, 1, 'barcodes/qr_KUL-01-250413-6FHQ1_80.png', 'bisadipinjam', 1, 'tidak_diperbaiki'),
(81, NULL, 'kulkas', 'KUL-01-250413-7HC3T', 1, '2025-04-12', 34555.00, 'images.jpeg', 'rusak ringan', 2, 1, 'barcodes/qr_KUL-01-250413-7HC3T_81.png', 'bisadipinjam', 1, 'tidak_diperbaiki'),
(82, NULL, 'kulkas', 'KUL-01-250413-6E8AC', 1, '2025-04-12', 34555.00, 'images.jpeg', 'rusak ringan', 2, 1, 'barcodes/qr_KUL-01-250413-6E8AC_82.png', 'bisadipinjam', 1, 'tidak_diperbaiki');

-- --------------------------------------------------------

--
-- Struktur dari tabel `maintenance`
--

CREATE TABLE `maintenance` (
  `id` int(11) NOT NULL,
  `item_id` int(11) DEFAULT NULL,
  `maintenance_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('dalam_perbaikan','selesai') DEFAULT 'dalam_perbaikan',
  `cost` decimal(10,2) DEFAULT NULL,
  `technician` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date DEFAULT NULL,
  `jumlah` int(11) NOT NULL,
  `status` enum('diajukan','menunggu konfirmasi','dipinjam','dikembalikan','ditolak') NOT NULL DEFAULT 'diajukan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `peminjaman`
--

INSERT INTO `peminjaman` (`id`, `user_id`, `item_id`, `tanggal_pinjam`, `tanggal_kembali`, `jumlah`, `status`) VALUES
(61, 13, 79, '2025-04-16', '2025-04-16', 1, 'dikembalikan'),
(62, 13, 78, '2025-04-16', '2025-04-16', 1, 'dikembalikan'),
(63, 13, 80, '2025-04-16', NULL, 1, 'ditolak'),
(64, 13, 78, '2025-04-16', '2025-04-16', 1, 'ditolak'),
(65, 12, 78, '2025-04-16', '2025-04-17', 1, 'ditolak'),
(66, 13, 79, '2025-04-18', '2025-04-18', 1, 'ditolak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `location`) VALUES
(2, 'asm1', 'lantai1'),
(3, 'asm2', 'lantai1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT 'profile/default.jpg',
  `role` varchar(30) NOT NULL DEFAULT 'user',
  `theme_mode` enum('light','dark') NOT NULL DEFAULT 'light'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `reset_token`, `token_expiry`, `profile_picture`, `role`, `theme_mode`) VALUES
(1, 'admin', 'admin@example.com', '$2y$10$7t8YT1Hvu/gLMbvXrRQpB.xz6qPL6vq5PP.chilz2FpXBGK6KvBfC', NULL, NULL, 'profile/default.jpg', 'user', 'light'),
(3, 'Ucok_Baba', 'kempligimang@gmail.com', '$2y$10$oGcMTGUbUeMcIw.dlRJrBuZEgJA7J4Mtpq5BlKlBRt3FeV.BOpW8W', 'c8e0d289c27c4cf432f4759cd1db1772d8384b0d6bc316a9d3c5b5141485ccca39ebff3e23094949bc9d58730f254b9aa2fd', '2025-03-10 14:59:52', 'profile/default.jpg', 'user', 'light'),
(6, 'bagas', 'bagas88@gmail.com', '$2y$10$OXu0p/2jUnqb8sWOC2cizuUmQDe0Q/Gqqrff7NQKfjaiySEqVMfUu', NULL, NULL, 'profile/default.jpg', 'user', 'light'),
(7, 'agus', 'aguskotak@gmail.com', '$2y$10$OtTwbNsiL21584fgL.fy7.TV.oxpUbl6YOkv48u6l9FQafmDGqV3i', NULL, NULL, 'profile/default.jpg', 'user', 'light'),
(8, 'agus444', 'juga@gmail.com', '$2y$10$ExpSQ3UOKrnGDF1bZqZlo.M6HPrzOkyAvVWLJS7S9kdR38UgOm5F.', NULL, NULL, 'profile/default.jpg', 'user', 'light'),
(9, 'juta', 'jota555@gmail.com', '$2y$10$IqDegWejt2wj0VQuM6w9r.4HTAIRbmP3.hVOCd6ZKvPHggqO98ERq', NULL, NULL, 'profile/default.jpg', 'user', 'light'),
(10, 'mna', 'dava.abhirama21@smk.belajar.id', '$2y$10$IyMqbunUsFraIocCGnToe.35/Vmo93OxgJYvPPWsNW/YHRbfKP/0S', NULL, NULL, 'profile/default.jpg', 'user', 'light'),
(11, 'da', 'da.abhirama21@smk.belajar.id', '$2y$10$MKxe2fwt7l.5a7sQvtar0e80V8NK5VX75Q4mPoiZfBZ64Yc2wcgwG', NULL, NULL, 'profile/default.jpg', 'user', 'light'),
(12, 'das', 'dasiramap@gmail.com', '$2y$10$TH0y53CyHt.O/SXLwmjeG.Zk7gd2RzLLl6Nxqf3aThdIgLQm6B6ue', NULL, NULL, 'profile/default.jpg', 'admin', 'light'),
(13, 'dar', 'dava.dar@smk.belajar.id', '$2y$10$9.CTHAn1OLzmRo4bEBtE8.Y8Kmrabyl//MJgp5YWmnQUlbuYhd1XC', NULL, NULL, 'profile/default.jpg', 'user', 'light'),
(14, 'daz', 'daz.abhirama21@smk.belajar.id', '$2y$10$fq.CNF2WWlUx8l/gTs.v2uRE7IQILd/vSM2u911wXQzN2hr9VzOLK', NULL, NULL, 'profile/default.jpg', 'superadmin', 'light'),
(15, 'dark', 'cava.abhirama21@smk.belajar.id', '$2y$10$ayQ08OQQ0WvK2NT3r8TqVuKo98e6lAs.q1JeMJV8n.iwwwsF99TK6', NULL, NULL, 'profile/default.jpg', 'superadmin', 'light'),
(16, 'rar', 'rasr@gmail.com', '$2y$10$Qkwg8Fyja8NidBQ.FxrE0O4zwDob7vnl0Co5D0QGExKM9.EukJaZu', NULL, NULL, 'profile/default.jpg', 'admin', 'light');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `disposals`
--
ALTER TABLE `disposals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indeks untuk tabel `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `item_code` (`item_code`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeks untuk tabel `maintenance`
--
ALTER TABLE `maintenance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indeks untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indeks untuk tabel `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `disposals`
--
ALTER TABLE `disposals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT untuk tabel `maintenance`
--
ALTER TABLE `maintenance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT untuk tabel `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `disposals`
--
ALTER TABLE `disposals`
  ADD CONSTRAINT `disposals_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `disposals_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `maintenance`
--
ALTER TABLE `maintenance`
  ADD CONSTRAINT `maintenance_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Ketidakleluasaan untuk tabel `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD CONSTRAINT `peminjaman_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjaman_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
