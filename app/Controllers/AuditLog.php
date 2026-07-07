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

        $data = [
            'title' => 'Audit Log',
            'logs'  => $auditLogModel->getAllLogs(),
        ];

        return view('auditlog/index', $data);
    }
}
