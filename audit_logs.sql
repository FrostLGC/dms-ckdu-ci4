-- ============================================================
-- TABEL AUDIT_LOGS - Mencatat setiap aktivitas penting di DMS
-- ============================================================
-- Tabel ini merekam siapa melakukan apa dan kapan.
-- Setiap upload atau hapus dokumen akan tercatat di sini.

USE dms_ckdu_ci;

CREATE TABLE IF NOT EXISTS audit_logs (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id       INT UNSIGNED   NOT NULL COMMENT 'ID user yang melakukan aksi',
    aksi          VARCHAR(50)    NOT NULL COMMENT 'Jenis aksi: Upload, Hapus, Edit, dll.',
    document_name VARCHAR(255)   NOT NULL COMMENT 'Nama/judul dokumen yang terkait',
    keterangan    TEXT           NULL     COMMENT 'Detail tambahan (opsional)',
    created_at    DATETIME       DEFAULT CURRENT_TIMESTAMP,

    -- Foreign key ke tabel users
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
