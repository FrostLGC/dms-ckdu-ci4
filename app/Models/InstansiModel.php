<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * ============================================================
 * InstansiModel - Model untuk mengelola data instansi (mitra kerja)
 * ============================================================
 */
class InstansiModel extends Model
{
    protected $table         = 'instansi';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'nama_instansi',
        'alamat',
        'no_telp',
    ];
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
