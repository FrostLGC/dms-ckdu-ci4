<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * CategoryModel - Model untuk mengelola data kategori dokumen
 * 
 * Model sederhana untuk tabel "categories".
 * Fungsi dasar seperti findAll(), find($id), insert(), update(), delete()
 * sudah disediakan otomatis oleh CI4 tanpa perlu kita tulis manual.
 */
class CategoryModel extends Model
{
    protected $table         = 'categories';       // Nama tabel
    protected $primaryKey    = 'id';               // Primary key
    protected $allowedFields = [                   // Kolom yang boleh diisi
        'nama_kategori',
        'deskripsi',
    ];
    protected $useTimestamps = true;               // Otomatis isi created_at & updated_at
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
