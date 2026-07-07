<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel - Model untuk mengelola data pengguna (users)
 * 
 * Model ini digunakan oleh Auth Controller untuk:
 * - Mencari user berdasarkan email saat login
 * - Mengambil data user untuk session
 */
class UserModel extends Model
{
    protected $table         = 'users';           // Nama tabel
    protected $primaryKey    = 'id';              // Primary key
    protected $allowedFields = [                  // Kolom yang boleh diisi
        'nama',
        'email',
        'password',
        'role',
    ];
    protected $useTimestamps = true;              // Otomatis isi created_at & updated_at
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
