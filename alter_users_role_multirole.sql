-- ============================================================
-- SQL: Normalisasi Role Pengguna - DMS PT. CKDU
-- ============================================================
-- Jalankan script ini di phpMyAdmin atau MySQL CLI
-- Database: dms_ckdu_ci
-- ============================================================

USE dms_ckdu_ci;

-- Langkah 1: Konversi role lama ke role baru
UPDATE users SET role = 'hrd' WHERE role = 'user';
UPDATE users SET role = 'hrd' WHERE role IS NULL OR role = '';

-- Langkah 2: Ubah kolom menjadi ENUM terbatas
ALTER TABLE users 
MODIFY role ENUM('admin','hrd','pimpinan') NOT NULL DEFAULT 'hrd';
