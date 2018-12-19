-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Okt 2018 pada 05.49
-- Versi server: 10.1.31-MariaDB
-- Versi PHP: 5.6.35

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `core`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `groups`
--

CREATE TABLE `groups` (
  `id` bigint(20) NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(16, 'Demo', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu_new`
--

CREATE TABLE `menu_new` (
  `id` int(11) NOT NULL,
  `module` varchar(32) DEFAULT NULL,
  `menu` varchar(32) NOT NULL,
  `link` varchar(32) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `weight` int(11) NOT NULL DEFAULT '0',
  `icon` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `menu_new`
--

INSERT INTO `menu_new` (`id`, `module`, `menu`, `link`, `parent_id`, `weight`, `icon`) VALUES
(83, 'core', 'Menu', 'core/menu', 0, 0, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pas_activity`
--

CREATE TABLE `pas_activity` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `abbreviation` varchar(4) NOT NULL,
  `description` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pas_area`
--

CREATE TABLE `pas_area` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pas_card`
--

CREATE TABLE `pas_card` (
  `id` int(11) NOT NULL,
  `sub_type_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `valid_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL,
  `dominant_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `batch` int(1) NOT NULL,
  `assessment_date` date NOT NULL,
  `has_photo` tinyint(1) NOT NULL DEFAULT '0',
  `invoice_number` varchar(32) NOT NULL,
  `created_on` datetime NOT NULL,
  `approve_request` varchar(48) NOT NULL,
  `approve_test` varchar(48) NOT NULL,
  `approve_egm` varchar(48) NOT NULL,
  `approve_finance` varchar(48) NOT NULL,
  `approve_request_on` datetime NOT NULL,
  `approve_test_on` datetime NOT NULL,
  `approve_egm_on` datetime NOT NULL,
  `approve_finance_on` datetime NOT NULL,
  `approve_on` datetime DEFAULT NULL,
  `date_start` date NOT NULL,
  `date_end` date NOT NULL,
  `status` varchar(32) NOT NULL DEFAULT 'Request'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pas_card_area`
--

CREATE TABLE `pas_card_area` (
  `card_id` int(11) NOT NULL,
  `area_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pas_company`
--

CREATE TABLE `pas_company` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `abbreviation` varchar(32) NOT NULL,
  `address` varchar(128) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pas_dominant`
--

CREATE TABLE `pas_dominant` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `text_color` int(11) NOT NULL,
  `r` int(11) NOT NULL,
  `g` int(11) NOT NULL,
  `b` int(11) NOT NULL,
  `description` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pas_person`
--

CREATE TABLE `pas_person` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `email` varchar(64) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `address` varchar(128) NOT NULL,
  `postal_code` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pas_sub_type`
--

CREATE TABLE `pas_sub_type` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pas_type`
--

CREATE TABLE `pas_type` (
  `id` int(11) NOT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pas_valid`
--

CREATE TABLE `pas_valid` (
  `id` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `permissions`
--

CREATE TABLE `permissions` (
  `name` varchar(64) NOT NULL,
  `group_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data untuk tabel `permissions`
--

INSERT INTO `permissions` (`name`, `group_id`, `menu_id`, `is_admin`) VALUES
('Core.Menu', 16, 83, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `struktur`
--

CREATE TABLE `struktur` (
  `id` int(11) NOT NULL,
  `tgl_insert` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `direktorat` varchar(250) COLLATE latin1_general_ci NOT NULL,
  `unit_kerja` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `nama_struktur` varchar(100) CHARACTER SET latin1 NOT NULL,
  `id_direktur` int(11) NOT NULL,
  `id_divisi` int(11) NOT NULL,
  `kode_direktorat` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `kode_struktur` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `kepanjangan` varchar(100) COLLATE latin1_general_ci NOT NULL,
  `sorting` int(11) DEFAULT NULL,
  `aktif` enum('aktif','unaktif') COLLATE latin1_general_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_login` varchar(100) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `password` varchar(128) DEFAULT '2c216b1ba5e33a27eb6d3df7de7f8c36',
  `user_name` varchar(200) NOT NULL,
  `user_function` varchar(100) NOT NULL,
  `kode_struktur` varchar(10) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `posisi` varchar(64) DEFAULT NULL,
  `nik` varchar(24) DEFAULT NULL,
  `user_avatar` varchar(30) NOT NULL,
  `akses` varchar(100) DEFAULT NULL,
  `user_atasan` int(11) DEFAULT NULL,
  `sppd_atasan` int(11) NOT NULL,
  `direksi_atasan` int(11) NOT NULL,
  `status_atasan` varchar(50) NOT NULL,
  `status` varchar(10) DEFAULT 'aktif',
  `email` varchar(100) DEFAULT NULL,
  `rule_id` int(11) DEFAULT NULL,
  `id_struktur` int(11) DEFAULT NULL,
  `id_unitkerja` int(11) NOT NULL,
  `id_unitkerja_divisi` varchar(500) DEFAULT NULL,
  `id_office` int(3) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `user_login`, `user_password`, `password`, `user_name`, `user_function`, `kode_struktur`, `jabatan`, `posisi`, `nik`, `user_avatar`, `akses`, `user_atasan`, `sppd_atasan`, `direksi_atasan`, `status_atasan`, `status`, `email`, `rule_id`, `id_struktur`, `id_unitkerja`, `id_unitkerja_divisi`, `id_office`) VALUES
(165, 'user-demo@bijb.co.id', '', '2c216b1ba5e33a27eb6d3df7de7f8c36', 'User Demo', 'Demo', '', 'Non Staf', 'Demo', '1', '', NULL, NULL, 0, 0, '', 'aktif', NULL, NULL, NULL, 0, NULL, 1),
(166, 'dede@bijb.co.id', '', 'd41d8cd98f00b204e9800998ecf8427e', 'Dede Husen', '', '', '', NULL, NULL, '', NULL, NULL, 0, 0, '', 'aktif', NULL, NULL, NULL, 0, NULL, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_group`
--

CREATE TABLE `user_group` (
  `user_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user_group`
--

INSERT INTO `user_group` (`user_id`, `group_id`) VALUES
(166, 16),
(165, 16);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `menu_new`
--
ALTER TABLE `menu_new`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pas_activity`
--
ALTER TABLE `pas_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pas_area`
--
ALTER TABLE `pas_area`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pas_card`
--
ALTER TABLE `pas_card`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pas_card_pas_sub_type1_idx` (`sub_type_id`),
  ADD KEY `fk_pas_card_pas_person1_idx` (`person_id`),
  ADD KEY `fk_pas_card_pas_company1_idx` (`company_id`),
  ADD KEY `fk_pas_card_pas_valid1_idx` (`valid_id`);

--
-- Indeks untuk tabel `pas_card_area`
--
ALTER TABLE `pas_card_area`
  ADD KEY `fk_pas_card_area_pas_area1_idx` (`area_id`),
  ADD KEY `fk_pas_card_area_pas_card1_idx` (`card_id`);

--
-- Indeks untuk tabel `pas_company`
--
ALTER TABLE `pas_company`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pas_dominant`
--
ALTER TABLE `pas_dominant`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pas_person`
--
ALTER TABLE `pas_person`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pas_sub_type`
--
ALTER TABLE `pas_sub_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pas_sub_type_pas_type_idx` (`type_id`);

--
-- Indeks untuk tabel `pas_type`
--
ALTER TABLE `pas_type`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pas_valid`
--
ALTER TABLE `pas_valid`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `struktur`
--
ALTER TABLE `struktur`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD KEY `fk_rule` (`rule_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `groups`
--
ALTER TABLE `groups`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `menu_new`
--
ALTER TABLE `menu_new`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT untuk tabel `pas_activity`
--
ALTER TABLE `pas_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pas_area`
--
ALTER TABLE `pas_area`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `pas_card`
--
ALTER TABLE `pas_card`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pas_company`
--
ALTER TABLE `pas_company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pas_dominant`
--
ALTER TABLE `pas_dominant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pas_person`
--
ALTER TABLE `pas_person`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `pas_sub_type`
--
ALTER TABLE `pas_sub_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `pas_type`
--
ALTER TABLE `pas_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pas_valid`
--
ALTER TABLE `pas_valid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `struktur`
--
ALTER TABLE `struktur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=167;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `pas_card`
--
ALTER TABLE `pas_card`
  ADD CONSTRAINT `fk_pas_card_pas_company1` FOREIGN KEY (`company_id`) REFERENCES `pas_company` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pas_card_pas_person1` FOREIGN KEY (`person_id`) REFERENCES `pas_person` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pas_card_pas_sub_type1` FOREIGN KEY (`sub_type_id`) REFERENCES `pas_sub_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pas_card_pas_valid1` FOREIGN KEY (`valid_id`) REFERENCES `pas_valid` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ketidakleluasaan untuk tabel `pas_card_area`
--
ALTER TABLE `pas_card_area`
  ADD CONSTRAINT `fk_pas_card_area_pas_area1` FOREIGN KEY (`area_id`) REFERENCES `pas_area` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pas_card_area_pas_card1` FOREIGN KEY (`card_id`) REFERENCES `pas_card` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ketidakleluasaan untuk tabel `pas_sub_type`
--
ALTER TABLE `pas_sub_type`
  ADD CONSTRAINT `fk_pas_sub_type_pas_type` FOREIGN KEY (`type_id`) REFERENCES `pas_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
