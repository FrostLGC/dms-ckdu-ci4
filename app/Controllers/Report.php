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

        $data = [
            'title'        => 'Laporan Arsip Dokumen',
            'documents'    => $documents,
            'filters'      => $filters,
            'keywordLabel' => $keywordLabel,
            'kategoriNama' => $kategoriNama,
            'instansiNama' => $instansiNama,
            'uploaderNama' => $uploaderNama,
            'statusNama'   => $statusNama,
        ];

        return view('report/print', $data);
    }
}
