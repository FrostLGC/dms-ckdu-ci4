<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ============================================================
 * AuditLogModel - Model untuk mencatat & mengambil log aktivitas
 * ============================================================
 * 
 * Tabel audit_logs mencatat setiap aksi penting di DMS, seperti:
 * - Upload dokumen baru
 * - Hapus dokumen
 * - (nantinya bisa ditambah: edit, arsip, dll.)
 * 
 * Setiap log berisi:
 * - Siapa (user_id + nama dari JOIN)
 * - Apa (aksi: "Upload", "Hapus")
 * - Dokumen apa (document_name)
 * - Kapan (created_at)
 */
class AuditLogModel extends Model
{
    protected $table         = 'audit_logs';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'user_id',
        'aksi',
        'document_name',
        'keterangan',
    ];
    // Hanya pakai created_at (log tidak perlu di-update)
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // Kosongkan karena tabel ini tidak punya updated_at

    /**
     * ============================================================
     * Ambil log aktivitas terbaru (dengan nama user)
     * ============================================================
     * 
     * Melakukan JOIN ke tabel users untuk mendapatkan nama orang
     * yang melakukan aksi, bukan hanya ID-nya.
     * 
     * Perbandingan PHP Native:
     *   $query = "SELECT a.*, u.nama AS nama_user 
     *             FROM audit_logs a 
     *             JOIN users u ON a.user_id = u.id 
     *             ORDER BY a.created_at DESC 
     *             LIMIT $limit";
     * 
     * @param int $limit  Jumlah log yang diambil (default 10)
     * @return array      Array berisi log aktivitas
     */
    public function getLatestLogs($limit = 10)
    {
        return $this->db->table('audit_logs AS a')
            ->select('a.*, u.nama AS nama_user')
            ->join('users AS u', 'u.id = a.user_id', 'left')
            ->orderBy('a.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * ============================================================
     * Ambil SEMUA log aktivitas (untuk halaman Audit Log penuh)
     * ============================================================
     * 
     * Sama seperti getLatestLogs tapi tanpa limit.
     * Digunakan di halaman /auditlog.
     * 
     * @return array  Semua log aktivitas
     */
    public function getAllLogs()
    {
        return $this->db->table('audit_logs AS a')
            ->select('a.*, u.nama AS nama_user')
            ->join('users AS u', 'u.id = a.user_id', 'left')
            ->orderBy('a.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * ============================================================
     * Simpan log aktivitas baru
     * ============================================================
     * 
     * Dipanggil dari Controller setiap kali ada aksi penting.
     * Contoh pemanggilan:
     *   $auditLogModel->insertLog([
     *       'user_id'       => session()->get('user_id'),
     *       'aksi'          => 'Upload',
     *       'document_name' => 'Kontrak Proyek A',
     *       'keterangan'    => 'Upload dokumen baru',
     *   ]);
     * 
     * @param array $data  Data log aktivitas
     * @return int|false   ID log yang baru dibuat
     */
    public function insertLog($data)
    {
        $this->insert($data);
        return $this->getInsertID();
    }
}
