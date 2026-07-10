<?php

namespace App\Controllers;

use App\Models\DocumentModel;
use App\Models\CategoryModel;

/**
 * ============================================================
 * Report Controller - Fitur Cetak Laporan Arsip Dokumen
 * ============================================================
 */
class Report extends BaseController
{
    public function index()
    {
        $categoryModel = new CategoryModel();
        $instansiModel = new \App\Models\InstansiModel();
        $userModel = new \App\Models\UserModel();

        $data = [
            'title'      => 'Cetak Laporan',
            'categories' => $categoryModel->orderBy('nama_kategori', 'ASC')->findAll(),
            'instansis'  => $instansiModel->orderBy('nama_instansi', 'ASC')->findAll(),
            'uploaders'  => $userModel->orderBy('nama', 'ASC')->findAll(),
        ];

        return view('report/index', $data);
    }

    public function print()
    {
        $documentModel = new DocumentModel();

        // Ambil parameter filter dari request GET
        $filters = [
            'keyword'     => $this->request->getGet('keyword'),
            'category_id' => $this->request->getGet('category_id'),
            'instansi_id' => $this->request->getGet('instansi_id'),
            'status'      => $this->request->getGet('status'),
            'uploaded_by' => $this->request->getGet('uploaded_by'),
            'start_date'  => $this->request->getGet('start_date'),
            'end_date'    => $this->request->getGet('end_date'),
        ];

        // Dapatkan data dokumen yang difilter
        $documents = $documentModel->getDocuments($filters);

        // Ambil label untuk keyword
        $keywordLabel = !empty($filters['keyword']) ? $filters['keyword'] : '-';

        // Ambil nama kategori
        $kategoriNama = 'Semua Kategori';
        if (!empty($filters['category_id'])) {
            $categoryModel = new CategoryModel();
            $kategori = $categoryModel->find($filters['category_id']);
            if ($kategori) {
                $kategoriNama = $kategori['nama_kategori'];
            }
        }

        // Ambil nama instansi
        $instansiNama = 'Semua Instansi';
        if (!empty($filters['instansi_id'])) {
            $instansiModel = new \App\Models\InstansiModel();
            $instansi = $instansiModel->find($filters['instansi_id']);
            if ($instansi) {
                $instansiNama = $instansi['nama_instansi'];
            }
        }

        // Ambil nama uploader
        $uploaderNama = 'Semua Uploader';
        if (!empty($filters['uploaded_by'])) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($filters['uploaded_by']);
            if ($user) {
                $uploaderNama = $user['nama'];
            }
        }

        // Ambil nama status
        $statusNama = 'Semua Status';
        if (!empty($filters['status'])) {
            $statusNama = ucfirst($filters['status']);
        }

        $nomorLaporan = $this->request->getGet('nomor_laporan');

        $data = [
            'title'        => 'Laporan Arsip Dokumen',
            'documents'    => $documents,
            'filters'      => $filters,
            'keywordLabel' => $keywordLabel,
            'kategoriNama' => $kategoriNama,
            'instansiNama' => $instansiNama,
            'uploaderNama' => $uploaderNama,
            'statusNama'   => $statusNama,
            'nomorLaporan' => $nomorLaporan,
        ];

        return view('report/print', $data);
    }

    /**
     * ============================================================
     * downloadPackage() - Iterasi 13.2
     * Mengunduh semua file dokumen sesuai filter laporan dalam satu ZIP
     * ============================================================
     */
    public function downloadPackage()
    {
        // Pastikan user sudah login
        $role = session()->get('user_role');
        if (!session()->get('is_logged_in')) {
            return redirect()->to(base_url('/'));
        }
        if (!in_array($role, ['admin', 'hrd', 'pimpinan'])) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Anda tidak memiliki izin untuk mengunduh paket dokumen.');
        }

        $documentModel = new DocumentModel();

        // Ambil filter yang sama persis dengan print()
        $filters = [
            'keyword'     => $this->request->getGet('keyword'),
            'category_id' => $this->request->getGet('category_id'),
            'instansi_id' => $this->request->getGet('instansi_id'),
            'status'      => $this->request->getGet('status'),
            'uploaded_by' => $this->request->getGet('uploaded_by'),
            'start_date'  => $this->request->getGet('start_date'),
            'end_date'    => $this->request->getGet('end_date'),
        ];

        $nomorLaporan = $this->request->getGet('nomor_laporan') ?: '-';

        $documents = $documentModel->getDocuments($filters);

        if (empty($documents)) {
            return redirect()->to(base_url('report'))
                ->with('error', 'Tidak ada dokumen yang dapat diunduh sesuai filter yang dipilih.');
        }

        // Siapkan folder temp
        $tempDir = WRITEPATH . 'temp';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipFileName = 'paket-dokumen-laporan-' . date('Ymd-His') . '.zip';
        $zipPath     = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;

        // Buat ZIP
        if (!class_exists('ZipArchive')) {
            return redirect()->to(base_url('report'))
                ->with('error', 'Fitur download paket dokumen membutuhkan extension ZIP PHP. Silakan aktifkan extension ZIP pada server.');
        }
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return redirect()->to(base_url('report'))
                ->with('error', 'Gagal membuat file ZIP. Pastikan folder writable/temp bisa ditulis.');
        }

        $uploadDir    = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'documents';
        $notFoundList = [];
        $no           = 1;

        foreach ($documents as $doc) {
            $filePath = $uploadDir . DIRECTORY_SEPARATOR . ($doc['nama_file'] ?? '');

            if (!empty($doc['nama_file']) && file_exists($filePath)) {
                // Bersihkan judul untuk nama file
                $safeTitle = preg_replace('/[\/\\\\:\*\?"<>\|]/', '', $doc['judul'] ?? 'dokumen');
                $safeTitle = trim(preg_replace('/\s+/', ' ', $safeTitle));
                $ext       = pathinfo($doc['nama_file'], PATHINFO_EXTENSION);
                $entryName = sprintf('%03d - %s.%s', $no, $safeTitle, $ext);

                $zip->addFile($filePath, $entryName);
            } else {
                $notFoundList[] = sprintf('%03d - %s (file tidak ditemukan)', $no, $doc['judul'] ?? 'Tanpa Judul');
            }

            $no++;
        }

        // Buat label filter untuk README
        $kategoriNama  = 'Semua Kategori';
        if (!empty($filters['category_id'])) {
            $categoryModel = new CategoryModel();
            $kategori = $categoryModel->find($filters['category_id']);
            if ($kategori) $kategoriNama = $kategori['nama_kategori'];
        }

        $instansiNama  = 'Semua Instansi';
        if (!empty($filters['instansi_id'])) {
            $instansiModel = new \App\Models\InstansiModel();
            $instansi = $instansiModel->find($filters['instansi_id']);
            if ($instansi) $instansiNama = $instansi['nama_instansi'];
        }

        $uploaderNama  = 'Semua Uploader';
        if (!empty($filters['uploaded_by'])) {
            $userModel = new \App\Models\UserModel();
            $user = $userModel->find($filters['uploaded_by']);
            if ($user) $uploaderNama = $user['nama'];
        }

        $statusNama   = !empty($filters['status']) ? ucfirst($filters['status']) : 'Semua Status';
        $keywordLabel = !empty($filters['keyword']) ? $filters['keyword'] : '-';

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $periode = date('d/m/Y', strtotime($filters['start_date'])) . ' - ' . date('d/m/Y', strtotime($filters['end_date']));
        } elseif (!empty($filters['start_date'])) {
            $periode = 'Sejak ' . date('d/m/Y', strtotime($filters['start_date']));
        } elseif (!empty($filters['end_date'])) {
            $periode = 'Sampai ' . date('d/m/Y', strtotime($filters['end_date']));
        } else {
            $periode = 'Semua Waktu';
        }

        // Buat README-LAPORAN.txt
        $readme  = "LAPORAN ARSIP DOKUMEN\r\n";
        $readme .= "================================================\r\n";
        $readme .= "Nomor Laporan  : {$nomorLaporan}\r\n";
        $readme .= "Tanggal Cetak  : " . date('d/m/Y H:i:s') . "\r\n";
        $readme .= "Keyword        : {$keywordLabel}\r\n";
        $readme .= "Kategori       : {$kategoriNama}\r\n";
        $readme .= "Instansi/Mitra : {$instansiNama}\r\n";
        $readme .= "Status         : {$statusNama}\r\n";
        $readme .= "Uploader       : {$uploaderNama}\r\n";
        $readme .= "Periode        : {$periode}\r\n";
        $readme .= "Total Dokumen  : " . count($documents) . "\r\n";

        if (!empty($notFoundList)) {
            $readme .= "\r\n---\r\nFile Tidak Ditemukan:\r\n";
            foreach ($notFoundList as $item) {
                $readme .= "  - {$item}\r\n";
            }
        }

        $zip->addFromString('README-LAPORAN.txt', $readme);
        $zip->close();

        // Stream ke browser, lalu hapus file temp
        $response = $this->response
            ->download($zipPath, null)
            ->setFileName($zipFileName);

        // Hapus file temp setelah selesai (register_shutdown_function)
        register_shutdown_function(function () use ($zipPath) {
            if (file_exists($zipPath)) {
                @unlink($zipPath);
            }
        });

        return $response;
    }
}
