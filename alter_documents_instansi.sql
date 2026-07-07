-- ============================================================
-- SQL: Tambah Relasi Instansi ke Tabel Dokumen - DMS PT. CKDU
-- ============================================================
-- Jalankan script ini di phpMyAdmin atau MySQL CLI
-- Database: dms_ckdu_ci
-- ============================================================

USE dms_ckdu_ci;

ALTER TABLE documents
ADD COLUMN instansi_id INT NULL AFTER category_id;
