<?php

namespace App\Controllers;

use App\Models\AuditLogModel;

/**
 * ============================================================
 * AuditLog Controller - Menampilkan riwayat aktivitas DMS
 * ============================================================
 * 
 * Halaman ini menampilkan seluruh catatan aktivitas (audit trail)
 * yang terjadi di sistem, seperti upload dan hapus dokumen.
 * 
 * Audit log penting untuk:
 * - Melacak siapa yang melakukan apa dan kapan
 * - Keamanan dan akuntabilitas
 * - Kebutuhan audit perusahaan
 */
class AuditLog extends BaseController
{
    /**
     * Tampilkan seluruh riwayat aktivitas
     * 
     * URL: GET /auditlog
     */
    public function index()
    {
        $auditLogModel = new AuditLogModel();
        $userModel = new \App\Models\UserModel();

        // Ambil parameter filter
        $filters = [
            'keyword'    => $this->request->getGet('keyword'),
            'action'     => $this->request->getGet('action'),
            'user_id'    => $this->request->getGet('user_id'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date'   => $this->request->getGet('end_date'),
        ];

        // Ambil data users untuk dropdown filter
        $users = $userModel->orderBy('nama', 'ASC')->findAll();

        $data = [
            'title'   => 'Audit Log',
            'logs'    => $auditLogModel->getFilteredLogs($filters),
            'users'   => $users,
            'filters' => $filters,
        ];

        return view('auditlog/index', $data);
    }
}
