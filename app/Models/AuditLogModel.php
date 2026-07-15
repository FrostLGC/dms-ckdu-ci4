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
     * Ambil log aktivitas operasional (untuk Dashboard)
     * ============================================================
     * 
     * Mengecualikan Login, Logout, dan Akses Ditolak
     * 
     * @param int $limit  Jumlah log yang diambil (default 5)
     * @return array      Array berisi log aktivitas
     */
    public function getOperationalLogs($limit = 5)
    {
        return $this->db->table('audit_logs AS a')
            ->select('a.*, u.nama AS nama_user')
            ->join('users AS u', 'u.id = a.user_id', 'left')
            ->whereNotIn('a.aksi', ['Login', 'Logout', 'Akses Ditolak'])
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

    /**
     * ============================================================
     * Ambil log aktivitas dengan filter (Iterasi 14.1)
     * ============================================================
     *
     * @param array $filters  Filter: keyword, action, user_id, start_date, end_date
     * @return array
     */
    public function getFilteredLogs(array $filters = [])
    {
        $builder = $this->db->table('audit_logs AS a')
            ->select('a.*, u.nama AS nama_user')
            ->join('users AS u', 'u.id = a.user_id', 'left')
            ->orderBy('a.created_at', 'DESC');

        // Filter keyword: cari di aksi, document_name, keterangan, nama user
        if (!empty($filters['keyword'])) {
            $kw = $filters['keyword'];
            $builder->groupStart()
                ->like('a.aksi', $kw)
                ->orLike('a.document_name', $kw)
                ->orLike('a.keterangan', $kw)
                ->orLike('u.nama', $kw)
                ->groupEnd();
        }

        // Filter jenis aksi
        if (!empty($filters['action'])) {
            $builder->like('a.aksi', $filters['action'], 'none');
        }

        // Filter pengguna
        if (!empty($filters['user_id'])) {
            $builder->where('a.user_id', (int) $filters['user_id']);
        }

        // Filter rentang tanggal
        if (!empty($filters['start_date'])) {
            $builder->where('a.created_at >=', $filters['start_date'] . ' 00:00:00');
        }
        if (!empty($filters['end_date'])) {
            $builder->where('a.created_at <=', $filters['end_date'] . ' 23:59:59');
        }

        return $builder->get()->getResultArray();
    }

    /**
     * ============================================================
     * Hitung total log aktivitas dengan filter (untuk pagination)
     * ============================================================
     *
     * @param array $filters  Filter: keyword, action, user_id, start_date, end_date
     * @return int
     */
    public function countFilteredLogs(array $filters = []): int
    {
        $builder = $this->db->table('audit_logs AS a')
            ->select('a.id')
            ->join('users AS u', 'u.id = a.user_id', 'left');

        if (!empty($filters['keyword'])) {
            $kw = $filters['keyword'];
            $builder->groupStart()
                ->like('a.aksi', $kw)
                ->orLike('a.document_name', $kw)
                ->orLike('a.keterangan', $kw)
                ->orLike('u.nama', $kw)
                ->groupEnd();
        }

        if (!empty($filters['action'])) {
            $builder->like('a.aksi', $filters['action'], 'none');
        }

        if (!empty($filters['user_id'])) {
            $builder->where('a.user_id', (int) $filters['user_id']);
        }

        if (!empty($filters['start_date'])) {
            $builder->where('a.created_at >=', $filters['start_date'] . ' 00:00:00');
        }
        if (!empty($filters['end_date'])) {
            $builder->where('a.created_at <=', $filters['end_date'] . ' 23:59:59');
        }

        return (int) $builder->countAllResults();
    }

    /**
     * ============================================================
     * Ambil log aktivitas terfilter dengan LIMIT + OFFSET (pagination)
     * ============================================================
     *
     * @param array $filters  Filter: keyword, action, user_id, start_date, end_date
     * @param int   $limit    Jumlah per halaman
     * @param int   $offset   Posisi mulai data
     * @return array
     */
    public function getFilteredLogsPaginated(array $filters = [], int $limit = 10, int $offset = 0): array
    {
        $builder = $this->db->table('audit_logs AS a')
            ->select('a.*, u.nama AS nama_user')
            ->join('users AS u', 'u.id = a.user_id', 'left')
            ->orderBy('a.created_at', 'DESC');

        if (!empty($filters['keyword'])) {
            $kw = $filters['keyword'];
            $builder->groupStart()
                ->like('a.aksi', $kw)
                ->orLike('a.document_name', $kw)
                ->orLike('a.keterangan', $kw)
                ->orLike('u.nama', $kw)
                ->groupEnd();
        }

        if (!empty($filters['action'])) {
            $builder->like('a.aksi', $filters['action'], 'none');
        }

        if (!empty($filters['user_id'])) {
            $builder->where('a.user_id', (int) $filters['user_id']);
        }

        if (!empty($filters['start_date'])) {
            $builder->where('a.created_at >=', $filters['start_date'] . ' 00:00:00');
        }
        if (!empty($filters['end_date'])) {
            $builder->where('a.created_at <=', $filters['end_date'] . ' 23:59:59');
        }

        return $builder->limit($limit, $offset)->get()->getResultArray();
    }

    /**
     * ============================================================
     * Mengambil ringkasan aktivitas dokumen berdasarkan rentang periode
     * ============================================================
     * 
     * @param int $days  Jumlah hari ke belakang
     * @return array     Ringkasan jumlah untuk tiap aksi
     */
    public function getActivitySummaryByPeriod($days)
    {
        $dateThreshold = date('Y-m-d H:i:s', strtotime("-{$days} days"));

        $results = $this->db->table($this->table)
            ->select('aksi, COUNT(*) as total')
            ->whereIn('aksi', ['Upload', 'Edit', 'Revisi', 'Preview', 'Download'])
            ->where('created_at >=', $dateThreshold)
            ->groupBy('aksi')
            ->get()
            ->getResultArray();

        // Default structure
        $summary = [
            'Upload' => 0,
            'Edit' => 0,
            'Revisi' => 0,
            'Preview' => 0,
            'Download' => 0,
        ];

        foreach ($results as $row) {
            $summary[$row['aksi']] = (int) $row['total'];
        }

        return $summary;
    }
}
