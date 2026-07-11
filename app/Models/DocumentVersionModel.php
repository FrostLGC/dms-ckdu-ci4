<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * DocumentVersionModel - Model untuk mengelola riwayat versi dokumen
 * 
 * Setiap kali dokumen diperbarui/upload ulang, versi baru akan disimpan di sini.
 * Tujuannya: agar kita bisa melihat riwayat perubahan dokumen.
 */
class DocumentVersionModel extends Model
{
    protected $table         = 'document_versions'; // Nama tabel
    protected $primaryKey    = 'id';                // Primary key
    protected $allowedFields = [                    // Kolom yang boleh diisi
        'document_id',
        'nomor_versi',
        'nama_file',
        'nama_file_asli',
        'ukuran_file',
        'catatan',
        'uploaded_by',
    ];

    // Hanya pakai created_at (tanpa updated_at) karena versi tidak diubah, hanya ditambah
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';  // Kosongkan karena tabel ini tidak punya kolom updated_at

    /**
     * Ambil semua versi dari 1 dokumen, urut dari versi terbaru.
     * 
     * @param int $documentId  ID dokumen
     * @return array           Daftar versi dokumen
     */
    public function getVersionsByDocumentId($documentId)
    {
        return $this->select('document_versions.*, users.nama as nama_uploader')
                    ->join('users', 'users.id = document_versions.uploaded_by', 'left')
                    ->where('document_id', $documentId)
                    ->orderBy('nomor_versi', 'DESC')
                    ->findAll();
    }

    /**
     * Ambil detail satu versi spesifik berdasarkan ID
     * Termasuk nama uploader.
     * 
     * @param int $versionId ID dari tabel document_versions
     * @return array|null    Data versi atau null
     */
    public function getVersionById($versionId)
    {
        return $this->select('document_versions.*, users.nama as nama_uploader')
                    ->join('users', 'users.id = document_versions.uploaded_by', 'left')
                    ->where('document_versions.id', $versionId)
                    ->first();
    }

    /**
     * Ambil nomor versi terakhir dari sebuah dokumen.
     * Berguna saat mau upload versi baru (versi_terakhir + 1).
     * 
     * @param int $documentId  ID dokumen
     * @return int             Nomor versi terakhir (0 jika belum ada)
     */
    public function getLatestVersionNumber($documentId)
    {
        $result = $this->selectMax('nomor_versi', 'max_versi')
                       ->where('document_id', $documentId)
                       ->first();

        // Jika belum ada versi, kembalikan 0
        return $result['max_versi'] ?? 0;
    }
}
