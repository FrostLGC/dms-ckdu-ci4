<?php

namespace App\Controllers;

use App\Models\AuditLogModel;

/**
 * ============================================================
 * AuditLog Controller - Menampilkan riwayat aktivitas DMS
 * ============================================================
 * 
 * Iterasi 17: Ditambahkan:
 * - Filter default hari ini saat tidak ada parameter
 * - Filter cepat: Hari Ini, 7 Hari, 30 Hari, Semua Riwayat
 * - Pagination 10 data per halaman (dengan nomor urut berlanjut)
 * - Parameter filter tetap terbawa saat pindah halaman
 */
class AuditLog extends BaseController
{
    const PER_PAGE = 10;

    /**
     * Tampilkan riwayat aktivitas dengan filter dan pagination
     * URL: GET /auditlog
     */
    public function index()
    {
        $auditLogModel = new AuditLogModel();
        $userModel     = new \App\Models\UserModel();

        $today = date('Y-m-d');

        // ============================================================
        // Tangkap parameter filter dari URL
        // ============================================================
        $keyword   = $this->request->getGet('keyword')   ?? '';
        $action    = $this->request->getGet('action')    ?? '';
        $userId    = $this->request->getGet('user_id')   ?? '';
        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');
        $quick     = $this->request->getGet('quick');     // tombol cepat
        $page      = max(1, (int) ($this->request->getGet('page') ?? 1));

        // ============================================================
        // Tangani filter cepat dari tombol periode
        // ============================================================
        if ($quick !== null) {
            switch ($quick) {
                case 'today':
                    $startDate = $today;
                    $endDate   = $today;
                    break;
                case '7days':
                    $startDate = date('Y-m-d', strtotime('-6 days'));
                    $endDate   = $today;
                    break;
                case '30days':
                    $startDate = date('Y-m-d', strtotime('-29 days'));
                    $endDate   = $today;
                    break;
                case 'all':
                    $startDate = '';
                    $endDate   = '';
                    break;
            }
        }

        // ============================================================
        // Default: Hari Ini (jika tidak ada parameter apapun)
        // ============================================================
        $hasAnyParam = $this->request->getGet('keyword') !== null
            || $this->request->getGet('action') !== null
            || $this->request->getGet('user_id') !== null
            || $this->request->getGet('start_date') !== null
            || $this->request->getGet('end_date') !== null
            || $quick !== null;

        if (!$hasAnyParam) {
            $startDate = $today;
            $endDate   = $today;
        }

        // ============================================================
        // Validasi tanggal: start tidak boleh lebih besar dari end
        // ============================================================
        $dateError = null;
        if (!empty($startDate) && !empty($endDate) && $startDate > $endDate) {
            $dateError = 'Tanggal awal tidak boleh lebih besar dari tanggal akhir.';
            // Swap agar tetap bisa menampilkan data
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        $filters = [
            'keyword'    => $keyword,
            'action'     => $action,
            'user_id'    => $userId,
            'start_date' => $startDate ?? '',
            'end_date'   => $endDate   ?? '',
        ];

        // ============================================================
        // Hitung total dan ambil data dengan pagination
        // ============================================================
        $totalLogs = $auditLogModel->countFilteredLogs($filters);
        $totalPages = $totalLogs > 0 ? (int) ceil($totalLogs / self::PER_PAGE) : 1;
        $page = min($page, $totalPages);
        $offset = ($page - 1) * self::PER_PAGE;

        $logs = $auditLogModel->getFilteredLogsPaginated($filters, self::PER_PAGE, $offset);

        // ============================================================
        // Susun link pagination (dengan semua filter terbawa)
        // ============================================================
        $queryParams = array_filter([
            'quick'      => $quick,
            'keyword'    => $keyword,
            'action'     => $action,
            'user_id'    => $userId,
            'start_date' => $filters['start_date'],
            'end_date'   => $filters['end_date'],
        ], fn($v) => $v !== '' && $v !== null);

        $users = $userModel->orderBy('nama', 'ASC')->findAll();

        $data = [
            'title'        => 'Audit Log',
            'logs'         => $logs,
            'users'        => $users,
            'filters'      => $filters,
            'totalLogs'    => $totalLogs,
            'totalPages'   => $totalPages,
            'currentPage'  => $page,
            'perPage'      => self::PER_PAGE,
            'offset'       => $offset,
            'queryParams'  => $queryParams,
            'dateError'    => $dateError,
            'quickActive'  => $quick,
        ];

        return view('auditlog/index', $data);
    }
}
