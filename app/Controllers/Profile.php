<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * ============================================================
 * Profile Controller - Edit Profil User yang Sedang Login
 * ============================================================
 * 
 * Controller ini mengambil dan memperbarui data profil berdasarkan
 * session()->get('user_id'). Setiap user bisa mengubah nama, email,
 * dan password mereka sendiri.
 */
class Profile extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * INDEX - Tampilkan form profil user yang sedang login
     * URL: GET /profile
     */
    public function index()
    {
        $userId = session()->get('user_id');
        $user   = $this->userModel->find($userId);

        $data = [
            'title'      => 'Profil Saya',
            'user'       => $user,
            'validation' => \Config\Services::validation(),
        ];

        return view('profile/index', $data);
    }

    /**
     * UPDATE - Proses update profil
     * URL: POST /profile/update
     * 
     * Aturan:
     * - Nama dan email wajib diisi
     * - Email harus unik (kecuali email diri sendiri)
     * - Password opsional: jika dikosongkan, password lama TIDAK berubah
     * - Jika diisi, password di-hash dengan BCRYPT
     */
    public function update()
    {
        $userId = session()->get('user_id');

        $rules = [
            'nama' => [
                'label'  => 'Nama Lengkap',
                'rules'  => 'required|trim|min_length[2]|max_length[100]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'min_length' => '{field} minimal {param} karakter.',
                    'max_length' => '{field} maksimal {param} karakter.',
                ],
            ],
            'email' => [
                'label'  => 'Email',
                'rules'  => "required|trim|valid_email|max_length[255]|is_unique[users.email,id,{$userId}]",
                'errors' => [
                    'required'    => '{field} wajib diisi.',
                    'valid_email' => 'Format {field} tidak valid.',
                    'max_length'  => '{field} maksimal {param} karakter.',
                    'is_unique'   => '{field} "{value}" sudah digunakan oleh pengguna lain.',
                ],
            ],
        ];

        // Validasi password hanya jika diisi
        if (!empty($this->request->getPost('password'))) {
            $rules['password'] = [
                'label'  => 'Password Baru',
                'rules'  => 'min_length[6]',
                'errors' => [
                    'min_length' => '{field} minimal {param} karakter.',
                ],
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Siapkan data update
        $dataUpdate = [
            'nama'  => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
        ];

        // ============================================================
        // CEK PASSWORD: Jika diisi, hash dan masukkan ke data update
        // ============================================================
        $passwordBaru = $this->request->getPost('password');
        if (!empty($passwordBaru)) {
            $dataUpdate['password'] = password_hash($passwordBaru, PASSWORD_BCRYPT);
        }

        if ($this->userModel->update($userId, $dataUpdate)) {
            // Perbarui nama dan email di session agar langsung terlihat di topbar
            session()->set([
                'user_nama'  => $dataUpdate['nama'],
                'user_email' => $dataUpdate['email'],
            ]);
            
            $this->logActivity('Edit Profil', $dataUpdate['nama'], 'Memperbarui profil diri sendiri');
        }

        return redirect()->to('/profile')
            ->with('success', 'Profil berhasil diperbarui!');
    }
}
