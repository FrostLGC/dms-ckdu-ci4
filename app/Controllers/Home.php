<?php

namespace App\Controllers;

// Import Model
use App\Models\DocumentModel;
use App\Models\CategoryModel;
use App\Models\AuditLogModel;

/**
 * ============================================================
 * Home Controller - Menangani halaman utama (Login & Dashboard)
 * ============================================================
 * 
 * Controller ini mengatur:
 * 1. Halaman login (landing page) -> index()
 * 2. Dashboard setelah login -> dashboard()
 */
class Home extends BaseController
{
    /**
     * Halaman utama (Landing Page = Login)
     * 
     * URL: / atau /home
     * Menampilkan form login elegan.
     */
    public function index()
    {
        // Muat helper URL agar base_url() tersedia di View
        helper('url');

        // Tampilkan halaman login
        // View ini TIDAK meng-extend layout/main.php karena login punya desain sendiri
        return view('auth/login');
    }

    /**
     * Dashboard - Halaman utama setelah login
     * 
     * URL: /dashboard
     * Menampilkan ringkasan data dokumen.
     */
    public function dashboard()
    {
        helper(['url', 'form']);

        // Ambil data statistik untuk ditampilkan di dashboard
        $documentModel = new DocumentModel();
        $categoryModel = new CategoryModel();
        $auditLogModel = new AuditLogModel();

        // Hitung total dokumen
        $totalDokumen = $documentModel->countAll();

        // Hitung dokumen aktif
        // resetQuery=true agar builder di-reset setelah count
        $totalAktif = $documentModel->where('status', 'aktif')->countAllResults();

        // Hitung dokumen arsip (query baru yang independen)
        $totalArsip = $documentModel->where('status', 'arsip')->countAllResults();

        // Hitung total kategori
        $totalKategori = $categoryModel->countAll();

        // Ambil 5 dokumen terbaru untuk ditampilkan di tabel dashboard
        $recentDocs = $documentModel->getDocuments();
        $recentDocs = array_slice($recentDocs, 0, 5);

        // Ambil 5 log aktivitas terbaru untuk widget "Aktivitas Terbaru" (exclude login/logout)
        $recentLogs = $auditLogModel->getOperationalLogs(5);

        $data = [
            'title'         => 'Dashboard',
            'totalDokumen'  => $totalDokumen,
            'totalAktif'    => $totalAktif,
            'totalArsip'    => $totalArsip,
            'totalKategori' => $totalKategori,
            'recentDocs'    => $recentDocs,
            'recentLogs'    => $recentLogs,    // Data log untuk widget aktivitas
        ];

        return view('dashboard/index', $data);
    }
}
