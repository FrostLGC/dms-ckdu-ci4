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

        $data = [
            'title'      => 'Cetak Laporan',
            'categories' => $categoryModel->findAll(),
        ];

        return view('report/index', $data);
    }

    public function print()
    {
        $documentModel = new DocumentModel();

        // Ambil parameter filter dari request GET
        $filters = [
            'category_id' => $this->request->getGet('category_id'),
            'start_date'  => $this->request->getGet('start_date'),
            'end_date'    => $this->request->getGet('end_date'),
        ];

        // Dapatkan data dokumen yang difilter
        $documents = $documentModel->getDocuments($filters);

        // Jika filter kategori aktif, ambil nama kategori untuk judul laporan
        $kategoriNama = 'Semua Kategori';
        if (!empty($filters['category_id'])) {
            $categoryModel = new CategoryModel();
            $kategori = $categoryModel->find($filters['category_id']);
            if ($kategori) {
                $kategoriNama = $kategori['nama_kategori'];
            }
        }

        $data = [
            'title'        => 'Laporan Arsip Dokumen',
            'documents'    => $documents,
            'filters'      => $filters,
            'kategoriNama' => $kategoriNama,
        ];

        return view('report/print', $data);
    }
}
