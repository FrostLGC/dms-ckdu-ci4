-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Jul 2026 pada 18.21
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 8.2.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dms_ckdu_ci`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'ID user yang melakukan aksi',
  `aksi` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Jenis aksi: Upload, Hapus, Edit, dll.',
  `document_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nama/judul dokumen yang terkait',
  `keterangan` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Detail tambahan (opsional)',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `aksi`, `document_name`, `keterangan`, `created_at`) VALUES
(1, 1, 'Upload', 'test2', 'Mengupload dokumen baru: \"test2\" (PDF)', '2026-06-10 13:53:03'),
(2, 1, 'Hapus', 'test2', 'Menghapus dokumen: \"test2\"', '2026-06-10 13:54:00'),
(3, 1, 'Edit', 'test', 'Merevisi dokumen: \"test\"', '2026-06-15 18:14:25'),
(4, 1, 'Upload', 'test', 'Mengupload dokumen baru: \"test\" (PDF)', '2026-06-28 16:26:06'),
(5, 1, 'Upload', 'SKK FERRY APRILLA', 'Mengupload dokumen baru: \"SKK FERRY APRILLA\" (PDF)', '2026-07-02 07:38:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Surat Masuk', 'Surat-surat yang diterima dari pihak eksternal', '2026-06-08 22:05:07', '2026-06-08 22:05:07'),
(2, 'Surat Keluar', 'Surat-surat yang dikirim ke pihak eksternal', '2026-06-08 22:05:07', '2026-06-08 22:05:07'),
(3, 'Kontrak', 'Dokumen perjanjian kerja sama dan kontrak proyek', '2026-06-08 22:05:07', '2026-06-08 22:05:07'),
(4, 'Laporan', 'Laporan kegiatan, keuangan, dan progress proyek', '2026-06-08 22:05:07', '2026-06-08 22:05:07'),
(5, 'SK & Kebijakan', 'Surat Keputusan dan kebijakan internal perusahaan', '2026-06-08 22:05:07', '2026-06-08 22:05:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `documents`
--

CREATE TABLE `documents` (
  `id` int(10) UNSIGNED NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `nama_file` varchar(255) NOT NULL,
  `nama_file_asli` varchar(255) NOT NULL,
  `ukuran_file` bigint(20) UNSIGNED DEFAULT 0,
  `tipe_file` varchar(20) NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `instansi_id` int(11) DEFAULT NULL,
  `uploaded_by` int(10) UNSIGNED NOT NULL,
  `status` enum('aktif','arsip') NOT NULL DEFAULT 'aktif',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `documents`
--

INSERT INTO `documents` (`id`, `judul`, `deskripsi`, `nama_file`, `nama_file_asli`, `ukuran_file`, `tipe_file`, `category_id`, `instansi_id`, `uploaded_by`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Kontrak Proyek Pembangunan Gedung A', 'Kontrak kerja sama pembangunan gedung A antara PT. CKDU dengan PT. Mitra Bangun Sejahtera', 'kontrak_gedung_a_1717862400.pdf', 'Kontrak Proyek Gedung A.pdf', 2048000, 'pdf', 3, NULL, 1, 'aktif', '2026-06-08 22:05:07', '2026-06-08 22:05:07'),
(3, 'test', 'test', '1781017108_e8f17e906f2806f3b13c.pdf', '9221-Article Text-10703-1-10-20230615.pdf', 433181, 'pdf', 4, NULL, 1, 'arsip', '2026-06-09 14:58:28', '2026-06-15 18:14:25'),
(5, 'test', '', '1782663965_672e6b45129fe5842771.pdf', '0495f9b3b62812ef347dd7dbc411cfb4f435.pdf', 1065205, 'pdf', 3, NULL, 1, 'aktif', '2026-06-28 16:26:05', '2026-06-28 16:26:05'),
(6, 'SKK FERRY APRILLA', 'SKK PT. SUPRAABAKTI MANDIRI', '1782977887_20427c6d949ae8b7c892.pdf', '16..pdf', 435382, 'pdf', 3, NULL, 1, 'aktif', '2026-07-02 07:38:07', '2026-07-02 07:38:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `document_versions`
--

CREATE TABLE `document_versions` (
  `id` int(10) UNSIGNED NOT NULL,
  `document_id` int(10) UNSIGNED NOT NULL,
  `nomor_versi` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `nama_file` varchar(255) NOT NULL,
  `nama_file_asli` varchar(255) NOT NULL,
  `ukuran_file` bigint(20) UNSIGNED DEFAULT 0,
  `catatan` text DEFAULT NULL,
  `uploaded_by` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `document_versions`
--

INSERT INTO `document_versions` (`id`, `document_id`, `nomor_versi`, `nama_file`, `nama_file_asli`, `ukuran_file`, `catatan`, `uploaded_by`, `created_at`) VALUES
(1, 1, 1, 'kontrak_gedung_a_1717862400.pdf', 'Kontrak Proyek Gedung A.pdf', 2048000, 'Versi awal dokumen kontrak', 1, '2026-06-08 22:05:07'),
(3, 3, 1, '1781017108_e8f17e906f2806f3b13c.pdf', '9221-Article Text-10703-1-10-20230615.pdf', 433181, 'Upload pertama (versi awal)', 1, '2026-06-09 14:58:28'),
(5, 5, 1, '1782663965_672e6b45129fe5842771.pdf', '0495f9b3b62812ef347dd7dbc411cfb4f435.pdf', 1065205, 'Upload pertama (versi awal)', 1, '2026-06-28 16:26:06'),
(6, 6, 1, '1782977887_20427c6d949ae8b7c892.pdf', '16..pdf', 435382, 'Upload pertama (versi awal)', 1, '2026-07-02 07:38:07');

-- --------------------------------------------------------

--
-- Struktur dari tabel `instansi`
--

CREATE TABLE `instansi` (
  `id` int(11) NOT NULL,
  `nama_instansi` varchar(255) NOT NULL,
  `alamat` text DEFAULT NULL,
  `no_telp` varchar(30) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `instansi`
--

INSERT INTO `instansi` (`id`, `nama_instansi`, `alamat`, `no_telp`, `created_at`, `updated_at`) VALUES
(1, 'PT. Mayora Group Tbk', 'test', '0812345678', '2026-06-25 09:35:59', '2026-06-25 09:35:59'),
(2, 'PT. ITI Utama', 'test2', '012222222', '2026-06-25 09:37:23', '2026-06-25 09:37:23');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nama`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@ckdu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-06-08 22:05:07', '2026-06-08 22:05:07'),
(2, 'Staf Dokumen', 'staf@ckdu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '2026-06-08 22:05:07', '2026-06-08 22:05:07'),
(3, 'Alva Herbart', 'alva@gmail.com', '$2y$10$217J1sGj/61fqf.CqZXuNegzQe59WWfS4c1iosW7BhHMv7sgQS.pG', 'admin', '2026-06-15 15:58:46', '2026-06-15 15:58:46');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_documents_category` (`category_id`),
  ADD KEY `idx_documents_status` (`status`),
  ADD KEY `fk_documents_user` (`uploaded_by`);

--
-- Indeks untuk tabel `document_versions`
--
ALTER TABLE `document_versions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_versions_document` (`document_id`),
  ADD KEY `fk_versions_user` (`uploaded_by`);

--
-- Indeks untuk tabel `instansi`
--
ALTER TABLE `instansi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_users_email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `document_versions`
--
ALTER TABLE `document_versions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `instansi`
--
ALTER TABLE `instansi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `fk_documents_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_documents_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `document_versions`
--
ALTER TABLE `document_versions`
  ADD CONSTRAINT `fk_versions_document` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_versions_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
