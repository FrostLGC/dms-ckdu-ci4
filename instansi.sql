-- ============================================================
-- SQL: Tabel Instansi (Mitra Kerja) - DMS PT. CKDU
-- ============================================================
-- Jalankan script ini di phpMyAdmin atau MySQL CLI
-- Database: dms_ckdu_ci
-- ============================================================

USE dms_ckdu_ci;

CREATE TABLE IF NOT EXISTS instansi (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    nama_instansi   VARCHAR(255) NOT NULL,
    alamat          TEXT NULL,
    no_telp         VARCHAR(30) NULL,
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
