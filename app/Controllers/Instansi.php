<?php

namespace App\Controllers;

use App\Models\InstansiModel;

/**
 * ============================================================
 * Instansi Controller - CRUD Mitra Kerja / Instansi
 * ============================================================
 */
class Instansi extends BaseController
{
    protected $instansiModel;

    public function __construct()
    {
        $this->instansiModel = new InstansiModel();
    }

    /**
     * RBAC: Cek apakah user boleh mengelola (CUD) instansi.
     * Hanya admin dan hrd yang diperbolehkan.
     * Pimpinan boleh membaca/lihat, tapi tidak boleh CUD.
     */
    private function canManageInstansi(): bool
    {
        return in_array(session()->get('user_role'), ['admin', 'hrd']);
    }

    /**
     * INDEX - Tampilkan daftar semua instansi
     * URL: GET /instansi
     */
    public function index()
    {
        $data = [
            'title'    => 'Data Instansi',
            'instansi' => $this->instansiModel->orderBy('nama_instansi', 'ASC')->findAll(),
        ];

        return view('instansi/index', $data);
    }

    /**
     * CREATE - Tampilkan form tambah instansi
     * URL: GET /instansi/create
     */
    public function create()
    {
        if (!$this->canManageInstansi()) {
            $this->logActivity('Akses Ditolak', '', 'Akses ditolak mengelola instansi');
            return redirect()->to('/instansi')->with('error', 'Anda tidak memiliki izin untuk mengelola instansi.');
        }

        $data = [
            'title'      => 'Tambah Instansi',
            'validation' => \Config\Services::validation(),
        ];

        return view('instansi/create', $data);
    }

    /**
     * STORE - Proses simpan instansi baru ke database
     * URL: POST /instansi/store
     */
    public function store()
    {
        if (!$this->canManageInstansi()) {
            $this->logActivity('Akses Ditolak', '', 'Akses ditolak mengelola instansi');
            return redirect()->to('/instansi')->with('error', 'Anda tidak memiliki izin untuk mengelola instansi.');
        }

        $rules = [
            'nama_instansi' => [
                'label'  => 'Nama Instansi',
                'rules'  => 'required|trim|max_length[255]|is_unique[instansi.nama_instansi]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'max_length' => '{field} maksimal {param} karakter.',
                    'is_unique'  => '{field} sudah digunakan. Gunakan nama lain.',
                ],
            ],
            'alamat' => [
                'label'  => 'Alamat',
                'rules'  => 'permit_empty|trim|max_length[500]',
                'errors' => [
                    'max_length' => '{field} maksimal {param} karakter.',
                ],
            ],
            'no_telp' => [
                'label'  => 'Nomor Telepon',
                'rules'  => 'permit_empty|max_length[20]|regex_match[/^[0-9\s\+\-\(\)]+$/]',
                'errors' => [
                    'max_length'  => '{field} maksimal {param} karakter.',
                    'regex_match' => 'Format {field} tidak valid. Hanya boleh berisi angka, spasi, +, -, dan tanda kurung.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->instansiModel->insert([
            'nama_instansi' => $this->request->getPost('nama_instansi'),
            'alamat'        => $this->request->getPost('alamat'),
            'no_telp'       => $this->request->getPost('no_telp'),
        ]);

        $this->logActivity('Tambah Instansi', $this->request->getPost('nama_instansi'), 'Tambah instansi "' . $this->request->getPost('nama_instansi') . '"');

        return redirect()->to('/instansi')
            ->with('success', 'Instansi "' . $this->request->getPost('nama_instansi') . '" berhasil ditambahkan!');
    }

    /**
     * EDIT - Tampilkan form edit instansi
     * URL: GET /instansi/edit/(:num)
     */
    public function edit($id = null)
    {
        if (!$this->canManageInstansi()) {
            $this->logActivity('Akses Ditolak', '', 'Akses ditolak mengelola instansi');
            return redirect()->to('/instansi')->with('error', 'Anda tidak memiliki izin untuk mengelola instansi.');
        }

        $instansi = $this->instansiModel->find($id);

        if ($instansi === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Instansi tidak ditemukan.'
            );
        }

        $data = [
            'title'      => 'Edit Instansi - ' . $instansi['nama_instansi'],
            'instansi'   => $instansi,
            'validation' => \Config\Services::validation(),
        ];

        return view('instansi/edit', $data);
    }

    /**
     * UPDATE - Proses update instansi
     * URL: POST /instansi/update/(:num)
     */
    public function update($id = null)
    {
        if (!$this->canManageInstansi()) {
            $this->logActivity('Akses Ditolak', '', 'Akses ditolak mengelola instansi');
            return redirect()->to('/instansi')->with('error', 'Anda tidak memiliki izin untuk mengelola instansi.');
        }

        $instansi = $this->instansiModel->find($id);

        if ($instansi === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Instansi tidak ditemukan.'
            );
        }

        $rules = [
            'nama_instansi' => [
                'label'  => 'Nama Instansi',
                'rules'  => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'min_length' => '{field} minimal {param} karakter.',
                    'max_length' => '{field} maksimal {param} karakter.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        $this->instansiModel->update($id, [
            'nama_instansi' => $this->request->getPost('nama_instansi'),
            'alamat'        => $this->request->getPost('alamat'),
            'no_telp'       => $this->request->getPost('no_telp'),
        ]);

        $this->logActivity('Edit Instansi', $this->request->getPost('nama_instansi'), 'Edit instansi "' . $this->request->getPost('nama_instansi') . '"');

        return redirect()->to('/instansi')
            ->with('success', 'Instansi "' . $this->request->getPost('nama_instansi') . '" berhasil diperbarui!');
    }

    /**
     * DELETE - Hapus instansi
     * URL: POST /instansi/delete/(:num)
     */
    public function delete($id = null)
    {
        if (!$this->canManageInstansi()) {
            $this->logActivity('Akses Ditolak', '', 'Akses ditolak mengelola instansi');
            return redirect()->to('/instansi')->with('error', 'Anda tidak memiliki izin untuk mengelola instansi.');
        }

        $instansi = $this->instansiModel->find($id);

        if ($instansi === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Instansi tidak ditemukan.'
            );
        }

        $this->instansiModel->delete($id);

        $this->logActivity('Hapus Instansi', $instansi['nama_instansi'], 'Hapus instansi "' . $instansi['nama_instansi'] . '"');

        return redirect()->to('/instansi')
            ->with('success', 'Instansi "' . $instansi['nama_instansi'] . '" berhasil dihapus!');
    }
}
