<?php

namespace App\Controllers;

// Import Model User
use App\Models\UserModel;

/**
 * ============================================================
 * Auth Controller - Menangani proses Login & Logout
 * ============================================================
 * 
 * Controller ini mengatur autentikasi pengguna:
 * 1. login()  -> Verifikasi email & password, buat session
 * 2. logout() -> Hapus session, redirect ke halaman login
 * 
 * Perbandingan dengan PHP Native:
 * ---------------------------------------------------------------
 * PHP Native (login.php):
 *   session_start();
 *   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 *       $email = $_POST['email'];
 *       $password = $_POST['password'];
 *       $query = "SELECT * FROM users WHERE email = '$email'";
 *       $result = mysqli_query($koneksi, $query);
 *       $user = mysqli_fetch_assoc($result);
 *       if ($user && password_verify($password, $user['password'])) {
 *           $_SESSION['user_id'] = $user['id'];
 *           header('Location: dashboard.php');
 *       }
 *   }
 * 
 * CI4 (di bawah):
 *   Menggunakan Model + Session bawaan CI4 yang lebih aman.
 * ---------------------------------------------------------------
 */
class Auth extends BaseController
{
    /**
     * ============================================================
     * FUNGSI LOGIN - Proses verifikasi email & password
     * ============================================================
     * 
     * URL: /auth/login
     * Method HTTP: POST (dikirim dari form login)
     * 
     * Alur kerja:
     * 1. Ambil email & password dari form
     * 2. Cari user di database berdasarkan email
     * 3. Verifikasi password dengan password_verify()
     * 4. Jika cocok -> buat session -> redirect ke dashboard
     * 5. Jika tidak cocok -> kembali ke login dengan pesan error
     */
    public function login()
    {
        // LANGKAH 1: Validasi input form
        // Pastikan email dan password tidak kosong
        $rules = [
            'email' => [
                'label'  => 'Email',
                'rules'  => 'required|valid_email',
                'errors' => [
                    'required'    => '{field} wajib diisi.',
                    'valid_email' => 'Format {field} tidak valid.',
                ],
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => 'required',
                'errors' => [
                    'required' => '{field} wajib diisi.',
                ],
            ],
        ];

        // Jika validasi gagal, kembali ke halaman login
        if (!$this->validate($rules)) {
            return redirect()->to('/')
                ->withInput()
                ->with('error', 'Email dan Password wajib diisi.');
        }

        // LANGKAH 2: Ambil data dari form
        // getPost() = sama seperti $_POST[] di PHP Native, tapi lebih aman
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // LANGKAH 3: Cari user di database berdasarkan email
        $userModel = new UserModel();

        // where('email', $email)->first() = 
        //   SELECT * FROM users WHERE email = 'admin@ckdu.com' LIMIT 1
        // first() mengembalikan 1 baris data (array) atau null jika tidak ditemukan
        $user = $userModel->where('email', $email)->first();

        // LANGKAH 4: Verifikasi
        // Cek apakah user ditemukan DAN password cocok
        if ($user === null) {
            // User dengan email tersebut tidak ditemukan
            return redirect()->to('/')
                ->withInput()
                ->with('error', 'Email tidak terdaftar dalam sistem.');
        }

        // password_verify() = fungsi PHP untuk mengecek password mentah dengan hash
        // Ini adalah cara yang AMAN untuk verifikasi password
        // JANGAN PERNAH bandingkan password mentah langsung (contoh: $password == $user['password'])
        //
        // Cara kerja:
        //   password_verify('password', '$2y$10$xxxxx...') -> true/false
        //   CI4 dan PHP Native sama-sama pakai fungsi ini
        if (!password_verify($password, $user['password'])) {
            // Password tidak cocok
            return redirect()->to('/')
                ->withInput()
                ->with('error', 'Password yang Anda masukkan salah.');
        }

        // LANGKAH 5: Login berhasil! Buat SESSION
        // Session di CI4 mirip dengan $_SESSION di PHP Native
        //
        // PHP Native:
        //   session_start();
        //   $_SESSION['user_id'] = $user['id'];
        //   $_SESSION['user_nama'] = $user['nama'];
        //
        // CI4:
        //   session()->set([...]) -> simpan data ke session
        //
        // Data session ini akan tersedia di semua halaman selama user belum logout
        $sessionData = [
            'user_id'    => $user['id'],
            'user_nama'  => $user['nama'],
            'user_email' => $user['email'],
            'user_role'  => $user['role'],
            'is_logged_in' => true,  // Flag untuk mengecek apakah user sudah login
        ];

        // Simpan data ke session
        session()->set($sessionData);

        // Redirect ke dashboard dengan pesan selamat datang
        return redirect()->to('/dashboard')
            ->with('success', 'Selamat datang, ' . $user['nama'] . '!');
    }

    /**
     * ============================================================
     * FUNGSI LOGOUT - Hapus session dan keluar dari sistem
     * ============================================================
     * 
     * URL: /auth/logout
     * Method HTTP: GET
     * 
     * PHP Native:
     *   session_start();
     *   session_destroy();
     *   header('Location: login.php');
     * 
     * CI4:
     *   session()->destroy() -> hapus semua data session
     */
    public function logout()
    {
        // Hapus semua data session (user di-logout)
        session()->destroy();

        // Redirect ke halaman login dengan pesan
        return redirect()->to('/')
            ->with('success', 'Anda telah berhasil logout.');
    }
}
