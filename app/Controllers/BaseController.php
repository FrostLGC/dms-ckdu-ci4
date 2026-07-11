<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Muat helper URL dan Form secara global agar tersedia di semua Controller dan View.
        // Helper 'url' menyediakan: base_url(), site_url(), uri_string(), dll.
        // Helper 'form' menyediakan: form_open(), form_close(), csrf_field(), dll.
        $this->helpers = ['form', 'url'];

        // Caution: Do not edit this line.
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        // $this->session = service('session');
    }

    /**
     * ============================================================
     * logActivity() - Helper Audit Log Terpusat (Iterasi 14)
     * ============================================================
     * Mencatat aktivitas user ke tabel audit_logs.
     * Dipanggil dari semua controller yang butuh audit trail.
     *
     * @param string $aksi          Jenis aksi (Login, Logout, Upload, dll.)
     * @param string $documentName  Nama dokumen/entitas yang terlibat (opsional)
     * @param string $keterangan    Deskripsi detail aktivitas (opsional)
     */
    protected function logActivity(string $aksi, string $documentName = '', string $keterangan = ''): void
    {
        // Hanya catat jika user sedang login
        if (!session()->get('is_logged_in')) {
            return;
        }

        try {
            $auditLogModel = new \App\Models\AuditLogModel();
            $auditLogModel->insertLog([
                'user_id'       => session()->get('user_id'),
                'aksi'          => $aksi,
                'document_name' => $documentName,
                'keterangan'    => $keterangan ?: $aksi,
            ]);
        } catch (\Throwable $e) {
            // Jangan biarkan kegagalan log mengganggu proses utama
            log_message('error', 'Gagal mencatat audit log: ' . $e->getMessage());
        }
    }
}
