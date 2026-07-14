<?php

namespace App\Controllers;

use App\Models\UserModel;

/**
 * ============================================================
 * User Controller - CRUD lengkap untuk Manajemen Pengguna
 * ============================================================
 * 
 * Controller ini mengelola data pengguna (tabel: users).
 * Hanya admin yang seharusnya bisa mengakses halaman ini.
 * 
 * Fungsi CRUD:
 *   index()  -> Tampilkan daftar semua pengguna (READ)
 *   create() -> Tampilkan form tambah pengguna baru
 *   store()  -> Proses simpan pengguna baru (CREATE)
 *   edit()   -> Tampilkan form edit pengguna
 *   update() -> Proses update data pengguna (UPDATE)
 *   delete() -> Hapus pengguna (DELETE)
 * 
 * Catatan keamanan password:
 * - Saat store() (tambah baru), password WAJIB diisi dan di-hash
 * - Saat update() (edit), jika password kosong, password lama TIDAK diubah
 * - Menggunakan password_hash() dengan algoritma BCRYPT (standar industri)
 */
class User extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * ============================================================
     * RBAC CHECK - Proteksi hak akses khusus Admin
     * ============================================================
     * 
     * initController() dijalankan SEBELUM fungsi lain di controller ini.
     * Jika user yang login bukan admin, langsung ditendang ke dashboard.
     * 
     * Kenapa pakai initController() dan bukan constructor?
     * Karena di CI4, $this->request dan session() belum tersedia di constructor.
     * initController() adalah tempat yang tepat untuk cek akses.
     */
    public function initController(
        \CodeIgniter\HTTP\RequestInterface $request,
        \CodeIgniter\HTTP\ResponseInterface $response,
        \Psr\Log\LoggerInterface $logger
    ) {
        // Panggil parent dulu agar CI4 menginisialisasi request, response, dll.
        parent::initController($request, $response, $logger);

        // ============================================================
        // CEK ROLE: Hanya admin yang boleh mengakses User CRUD
        // ============================================================
        // session()->get('user_role') diset saat login di AuthController
        // Jika bukan 'admin', redirect ke dashboard dengan pesan error
        if (session()->get('user_role') !== 'admin') {
            // Catat akses ditolak sebelum redirect
            if (session()->get('is_logged_in')) {
                try {
                    $auditLogModel = new \App\Models\AuditLogModel();
                    $auditLogModel->insertLog([
                        'user_id'       => session()->get('user_id'),
                        'aksi'          => 'Akses Ditolak',
                        'document_name' => '',
                        'keterangan'    => 'Akses ditolak mengelola pengguna',
                    ]);
                } catch (\Throwable $e) {
                    log_message('error', 'Gagal mencatat audit log: ' . $e->getMessage());
                }
            }
            session()->setFlashdata('error', 'Akses ditolak! Halaman ini hanya untuk Admin.');
            header('Location: ' . base_url('dashboard'));
            exit;
        }
    }

    /**
     * ============================================================
     * INDEX - Tampilkan daftar semua pengguna
     * ============================================================
     * URL: GET /user
     */
    public function index()
    {
        $users = $this->userModel
            ->orderBy('nama', 'ASC')
            ->findAll();

        $data = [
            'title' => 'Manajemen Pengguna',
            'users' => $users,
        ];

        return view('user/index', $data);
    }

    /**
     * ============================================================
     * CREATE - Tampilkan form tambah pengguna baru
     * ============================================================
     * URL: GET /user/create
     */
    public function create()
    {
        $data = [
            'title'      => 'Tambah Pengguna',
            'validation' => \Config\Services::validation(),
        ];

        return view('user/create', $data);
    }

    /**
     * ============================================================
     * STORE - Proses simpan pengguna baru ke database
     * ============================================================
     * URL: POST /user/store
     * 
     * Password yang dikirim dari form HARUS di-hash sebelum disimpan.
     * JANGAN PERNAH menyimpan password mentah (plain text) ke database.
     * 
     * Perbandingan PHP Native:
     *   $password = $_POST['password'];
     *   $hash = password_hash($password, PASSWORD_BCRYPT);
     *   $query = "INSERT INTO users (nama, email, password, role)
     *             VALUES ('$nama', '$email', '$hash', '$role')";
     */
    public function store()
    {
        $rules = [
            'nama' => [
                'label'  => 'Nama Lengkap',
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'min_length' => '{field} minimal {param} karakter.',
                    'max_length' => '{field} maksimal {param} karakter.',
                ],
            ],
            'email' => [
                'label'  => 'Email',
                'rules'  => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required'    => '{field} wajib diisi.',
                    'valid_email' => 'Format {field} tidak valid.',
                    'is_unique'   => '{field} "{value}" sudah terdaftar.',
                ],
            ],
            'password' => [
                'label'  => 'Password',
                'rules'  => 'required|min_length[6]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'min_length' => '{field} minimal {param} karakter.',
                ],
            ],
            'role' => [
                'label'  => 'Role',
                'rules'  => 'required|in_list[admin,hrd,pimpinan]',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'in_list'  => '{field} harus berisi admin, hrd, atau pimpinan.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // ============================================================
        // ENKRIPSI PASSWORD
        // ============================================================
        // password_hash() mengubah password mentah menjadi hash yang aman.
        // Hasilnya contoh: "$2y$10$92IXUNpkjO0rOQ5byMi..."
        // 
        // PASSWORD_BCRYPT = algoritma hashing standar industri
        // Setiap kali di-hash, hasilnya BERBEDA (karena ada "salt" acak),
        // tapi password_verify() tetap bisa mencocokkannya.
        //
        // JANGAN PERNAH gunakan md5() atau sha1() untuk password!
        $hashedPassword = password_hash(
            $this->request->getPost('password'),
            PASSWORD_BCRYPT
        );

        $this->userModel->insert([
            'nama'     => $this->request->getPost('nama'),
            'email'    => $this->request->getPost('email'),
            'password' => $hashedPassword,
            'role'     => $this->request->getPost('role'),
        ]);

        $this->logActivity('Tambah Pengguna', $this->request->getPost('nama'), 'Tambah pengguna "' . $this->request->getPost('nama') . '"');

        return redirect()->to('/user')
            ->with('success', 'Pengguna "' . $this->request->getPost('nama') . '" berhasil ditambahkan!');
    }

    /**
     * ============================================================
     * EDIT - Tampilkan form edit pengguna
     * ============================================================
     * URL: GET /user/edit/(:num)
     */
    public function edit($id = null)
    {
        $user = $this->userModel->find($id);

        if ($user === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Pengguna dengan ID ' . $id . ' tidak ditemukan.'
            );
        }

        $data = [
            'title'      => 'Edit Pengguna - ' . $user['nama'],
            'user'       => $user,
            'validation' => \Config\Services::validation(),
        ];

        return view('user/edit', $data);
    }

    /**
     * ============================================================
     * UPDATE - Proses update data pengguna di database
     * ============================================================
     * URL: POST /user/update/(:num)
     * 
     * ATURAN PENTING:
     * - Jika field password DIISI   -> hash password baru & simpan
     * - Jika field password KOSONG   -> JANGAN ubah password lama
     * 
     * Ini penting agar admin bisa mengedit nama/email/role
     * tanpa harus mengisi ulang password setiap kali.
     */
    public function update($id = null)
    {
        $user = $this->userModel->find($id);

        if ($user === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Pengguna tidak ditemukan.'
            );
        }

        // Aturan validasi (is_unique dengan pengecualian ID sendiri)
        $rules = [
            'nama' => [
                'label'  => 'Nama Lengkap',
                'rules'  => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'min_length' => '{field} minimal {param} karakter.',
                    'max_length' => '{field} maksimal {param} karakter.',
                ],
            ],
            'email' => [
                'label'  => 'Email',
                // is_unique[users.email,id,{$id}] = cek unik, KECUALI record ini sendiri
                'rules'  => "required|valid_email|is_unique[users.email,id,{$id}]",
                'errors' => [
                    'required'    => '{field} wajib diisi.',
                    'valid_email' => 'Format {field} tidak valid.',
                    'is_unique'   => '{field} "{value}" sudah digunakan oleh pengguna lain.',
                ],
            ],
            'role' => [
                'label'  => 'Role',
                'rules'  => 'required|in_list[admin,hrd,pimpinan]',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'in_list'  => '{field} harus berisi admin, hrd, atau pimpinan.',
                ],
            ],
        ];

        // Validasi password hanya jika diisi (permit_empty = boleh kosong)
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
            return redirect()->back()->withInput();
        }

        // Siapkan data yang akan diupdate
        $dataUpdate = [
            'nama'  => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'role'  => $this->request->getPost('role'),
        ];

        // ============================================================
        // CEK APAKAH PASSWORD DIISI
        // ============================================================
        // Jika admin mengisi field password -> hash & masukkan ke data update
        // Jika kosong -> SKIP, password lama di database tetap aman
        $passwordBaru = $this->request->getPost('password');
        if (!empty($passwordBaru)) {
            $dataUpdate['password'] = password_hash($passwordBaru, PASSWORD_BCRYPT);
        }

        $this->userModel->update($id, $dataUpdate);

        $this->logActivity('Edit Pengguna', $this->request->getPost('nama'), 'Edit pengguna "' . $this->request->getPost('nama') . '"');

        return redirect()->to('/user')
            ->with('success', 'Data pengguna "' . $this->request->getPost('nama') . '" berhasil diperbarui!');
    }

    /**
     * ============================================================
     * DELETE - Hapus pengguna dari database
     * ============================================================
     * URL: POST /user/delete/(:num)
     * 
     * Proteksi: Admin tidak boleh menghapus dirinya sendiri.
     */
    public function delete($id = null)
    {
        $user = $this->userModel->find($id);

        if ($user === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Pengguna tidak ditemukan.'
            );
        }

        // Proteksi: Jangan izinkan admin menghapus akunnya sendiri
        if ($id == session()->get('user_id')) {
            return redirect()->to('/user')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $this->userModel->delete($id);

        $this->logActivity('Hapus Pengguna', $user['nama'], 'Hapus pengguna "' . $user['nama'] . '"');

        return redirect()->to('/user')
            ->with('success', 'Pengguna "' . $user['nama'] . '" berhasil dihapus!');
    }
}
