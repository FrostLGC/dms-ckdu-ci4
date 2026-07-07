-- ============================================================
-- SQL: Tambah Kolom Nomor Dokumen ke Tabel Documents
-- ============================================================
-- Jalankan script ini di phpMyAdmin atau MySQL CLI
-- Database: dms_ckdu_ci
-- ============================================================

USE dms_ckdu_ci;

ALTER TABLE documents ADD COLUMN nomor_dokumen VARCHAR(100) NULL AFTER instansi_id;
