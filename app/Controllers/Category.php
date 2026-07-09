<?php

namespace App\Controllers;

// Import Model Kategori
use App\Models\CategoryModel;

/**
 * ============================================================
 * Category Controller - CRUD lengkap untuk Manajemen Kategori
 * ============================================================
 * 
 * Controller ini mengelola data kategori dokumen (tabel: categories).
 * Setiap dokumen di DMS pasti memiliki 1 kategori.
 * 
 * Fungsi CRUD:
 *   index()  -> Tampilkan daftar semua kategori (READ)
 *   create() -> Tampilkan form tambah kategori baru
 *   store()  -> Proses simpan kategori baru (CREATE)
 *   edit()   -> Tampilkan form edit kategori
 *   update() -> Proses update data kategori (UPDATE)
 *   delete() -> Hapus kategori (DELETE)
 * 
 * Perbandingan dengan PHP Native:
 * ---------------------------------------------------------------
 * PHP Native: Semua CRUD biasanya ada di 1 file besar, misal:
 *   kategori.php?aksi=tambah
 *   kategori.php?aksi=edit&id=1
 *   kategori.php?aksi=hapus&id=1
 * 
 * CI4 (MVC): Setiap aksi punya fungsinya sendiri yang rapi.
 *   /category           -> index()
 *   /category/create    -> create()
 *   /category/edit/1    -> edit($id)
 *   dst.
 * ---------------------------------------------------------------
 */
class Category extends BaseController
{
    // Properti untuk menyimpan instance Model
    protected $categoryModel;

    /**
     * Constructor - dijalankan otomatis saat Controller dipanggil.
     */
    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Cek apakah user boleh mengelola (tambah/edit/hapus) kategori.
     * Hanya admin dan hrd yang diperbolehkan.
     */
    private function canManageCategory(): bool
    {
        return in_array(session()->get('user_role'), ['admin', 'hrd']);
    }

    /**
     * ============================================================
     * INDEX - Tampilkan daftar semua kategori
     * ============================================================
     * URL: GET /category
     * 
     * Menampilkan tabel berisi semua kategori beserta jumlah
     * dokumen yang terkait di masing-masing kategori.
     */
    public function index()
    {
        // Ambil semua kategori, urutkan berdasarkan nama
        // orderBy('nama_kategori', 'ASC') = ORDER BY nama_kategori ASC
        // findAll() = SELECT * FROM categories ORDER BY nama_kategori ASC
        $categories = $this->categoryModel
            ->orderBy('nama_kategori', 'ASC')
            ->findAll();

        // Hitung jumlah dokumen per kategori
        // Kita gunakan Query Builder untuk melakukan COUNT() per category_id
        $db = \Config\Database::connect();
        foreach ($categories as &$cat) {
            $cat['jumlah_dokumen'] = $db->table('documents')
                ->where('category_id', $cat['id'])
                ->countAllResults();
        }
        // Lepas referensi agar tidak menyebabkan bug di loop berikutnya
        unset($cat);

        $data = [
            'title'      => 'Manajemen Kategori',
            'categories' => $categories,
        ];

        return view('category/index', $data);
    }

    /**
     * ============================================================
     * CREATE - Tampilkan form tambah kategori baru
     * ============================================================
     * URL: GET /category/create
     * 
     * Hanya menampilkan halaman form kosong.
     * Proses simpan dilakukan oleh store().
     */
    public function create()
    {
        if (!$this->canManageCategory()) {
            return redirect()->to('/category')->with('error', 'Anda tidak memiliki izin untuk mengelola kategori.');
        }

        $data = [
            'title'      => 'Tambah Kategori',
            'validation' => \Config\Services::validation(),
        ];

        return view('category/create', $data);
    }

    /**
     * ============================================================
     * STORE - Proses simpan kategori baru ke database
     * ============================================================
     * URL: POST /category/store
     * 
     * Menerima data dari form create, validasi, lalu simpan.
     * 
     * Perbandingan PHP Native:
     *   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     *       $nama = $_POST['nama_kategori'];
     *       if (empty($nama)) { echo "Wajib diisi!"; }
     *       $query = "INSERT INTO categories (nama_kategori) VALUES ('$nama')";
     *       mysqli_query($koneksi, $query);
     *   }
     * 
     * CI4: Validasi otomatis + insert lewat Model.
     */
    public function store()
    {
        if (!$this->canManageCategory()) {
            return redirect()->to('/category')->with('error', 'Anda tidak memiliki izin untuk mengelola kategori.');
        }

        // Aturan validasi
        $rules = [
            'nama_kategori' => [
                'label'  => 'Nama Kategori',
                'rules'  => 'required|min_length[2]|max_length[100]|is_unique[categories.nama_kategori]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'min_length' => '{field} minimal {param} karakter.',
                    'max_length' => '{field} maksimal {param} karakter.',
                    'is_unique'  => '{field} "{value}" sudah ada. Gunakan nama lain.',
                ],
            ],
            'deskripsi' => [
                'label'  => 'Deskripsi',
                'rules'  => 'permit_empty|max_length[500]',
                'errors' => [
                    'max_length' => '{field} maksimal {param} karakter.',
                ],
            ],
        ];

        // Jalankan validasi
        if (!$this->validate($rules)) {
            // Gagal -> kembali ke form dengan pesan error
            return redirect()->back()->withInput();
        }

        // Simpan ke database via Model
        // insert() otomatis mengisi created_at & updated_at
        $this->categoryModel->insert([
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
        ]);

        return redirect()->to('/category')
            ->with('success', 'Kategori "' . $this->request->getPost('nama_kategori') . '" berhasil ditambahkan!');
    }

    /**
     * ============================================================
     * EDIT - Tampilkan form edit kategori
     * ============================================================
     * URL: GET /category/edit/(:num)
     * 
     * Menampilkan form yang sudah terisi data kategori lama.
     * User tinggal mengubah dan menekan tombol simpan.
     */
    public function edit($id = null)
    {
        if (!$this->canManageCategory()) {
            return redirect()->to('/category')->with('error', 'Anda tidak memiliki izin untuk mengelola kategori.');
        }

        // Cari kategori berdasarkan ID
        // find($id) = SELECT * FROM categories WHERE id = $id LIMIT 1
        $category = $this->categoryModel->find($id);

        // Jika tidak ditemukan, tampilkan 404
        if ($category === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Kategori dengan ID ' . $id . ' tidak ditemukan.'
            );
        }

        $data = [
            'title'      => 'Edit Kategori - ' . $category['nama_kategori'],
            'category'   => $category,
            'validation' => \Config\Services::validation(),
        ];

        return view('category/edit', $data);
    }

    /**
     * ============================================================
     * UPDATE - Proses update data kategori di database
     * ============================================================
     * URL: POST /category/update/(:num)
     * 
     * Menerima data dari form edit, validasi, lalu update.
     * 
     * Catatan penting tentang is_unique saat update:
     *   Saat mengedit, nama kategori mungkin tidak berubah.
     *   Jika kita pakai is_unique biasa, CI4 akan menolak
     *   karena nama sudah ada di database (yaitu record ini sendiri).
     *   Solusinya: tambahkan pengecualian untuk ID yang sedang diedit.
     *   Format: is_unique[tabel.kolom,kolom_id,nilai_id]
     */
    public function update($id = null)
    {
        if (!$this->canManageCategory()) {
            return redirect()->to('/category')->with('error', 'Anda tidak memiliki izin untuk mengelola kategori.');
        }

        // Pastikan kategori ada
        $category = $this->categoryModel->find($id);
        if ($category === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Kategori tidak ditemukan.'
            );
        }

        // Aturan validasi (perhatikan is_unique dengan pengecualian ID)
        $rules = [
            'nama_kategori' => [
                'label'  => 'Nama Kategori',
                // is_unique[categories.nama_kategori,id,{$id}]
                // Artinya: cek unik di tabel categories kolom nama_kategori,
                //          KECUALI baris yang kolom id-nya = $id (record ini sendiri)
                'rules'  => "required|min_length[2]|max_length[100]|is_unique[categories.nama_kategori,id,{$id}]",
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'min_length' => '{field} minimal {param} karakter.',
                    'max_length' => '{field} maksimal {param} karakter.',
                    'is_unique'  => '{field} "{value}" sudah digunakan oleh kategori lain.',
                ],
            ],
            'deskripsi' => [
                'label'  => 'Deskripsi',
                'rules'  => 'permit_empty|max_length[500]',
                'errors' => [
                    'max_length' => '{field} maksimal {param} karakter.',
                ],
            ],
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        // Update data di database
        // update($id, $data) = UPDATE categories SET ... WHERE id = $id
        $this->categoryModel->update($id, [
            'nama_kategori' => $this->request->getPost('nama_kategori'),
            'deskripsi'     => $this->request->getPost('deskripsi'),
        ]);

        return redirect()->to('/category')
            ->with('success', 'Kategori berhasil diperbarui menjadi "' . $this->request->getPost('nama_kategori') . '"!');
    }

    /**
     * ============================================================
     * DELETE - Hapus kategori dari database
     * ============================================================
     * URL: POST /category/delete/(:num)
     * 
     * Sebelum menghapus, kita cek apakah masih ada dokumen
     * yang menggunakan kategori ini. Jika ada, tolak penghapusan
     * agar data tidak rusak (referential integrity).
     */
    public function delete($id = null)
    {
        if (!$this->canManageCategory()) {
            return redirect()->to('/category')->with('error', 'Anda tidak memiliki izin untuk mengelola kategori.');
        }

        // Cari kategori
        $category = $this->categoryModel->find($id);

        if ($category === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Kategori tidak ditemukan.'
            );
        }

        // Cek apakah kategori masih dipakai oleh dokumen
        $db = \Config\Database::connect();
        $jumlahDokumen = $db->table('documents')
            ->where('category_id', $id)
            ->countAllResults();

        if ($jumlahDokumen > 0) {
            // Tolak penghapusan jika masih ada dokumen terkait
            return redirect()->to('/category')
                ->with('error', 'Kategori "' . $category['nama_kategori'] . '" tidak bisa dihapus karena masih digunakan oleh ' . $jumlahDokumen . ' dokumen.');
        }

        // Aman untuk dihapus
        // delete($id) = DELETE FROM categories WHERE id = $id
        $this->categoryModel->delete($id);

        return redirect()->to('/category')
            ->with('success', 'Kategori "' . $category['nama_kategori'] . '" berhasil dihapus!');
    }
}
