<?php

namespace App\Controllers;

// Import Model yang sudah kita buat
use App\Models\DocumentModel;

/**
 * ============================================================
 * Document Controller - Mengatur semua aksi terkait dokumen
 * ============================================================
 * 
 * Controller ini adalah "pengatur lalu lintas" untuk fitur dokumen.
 * 
 * Perbandingan dengan PHP Native:
 * ---------------------------------------------------------------
 * PHP Native: Semua logika ada di 1 file (misal: dokumen.php)
 *   - Koneksi database di atas
 *   - Query di tengah
 *   - HTML di bawah
 *   - Semuanya campur aduk!
 * 
 * CI4 (MVC):
 *   - Model  = ngurusin database (DocumentModel.php)
 *   - View   = ngurusin tampilan HTML (file di folder Views/)
 *   - Controller = ngurusin logika & menghubungkan Model dengan View (file ini)
 * ---------------------------------------------------------------
 * 
 * Setiap fungsi public di Controller = 1 halaman/aksi yang bisa diakses via URL
 * Contoh: fungsi index() diakses via URL: /document atau /document/index
 *         fungsi upload() diakses via URL: /document/upload
 */
class Document extends BaseController
{
    // Deklarasikan properti untuk menyimpan instance Model
    // Di PHP Native, ini seperti variabel $koneksi yang kamu simpan di atas file
    protected $documentModel;

    /**
     * Constructor - dijalankan otomatis saat Controller dipanggil.
     * 
     * Di PHP Native, ini seperti bagian atas file dimana kamu:
     *   require_once 'koneksi.php';
     *   require_once 'fungsi.php';
     */
    public function __construct()
    {
        // Buat instance dari DocumentModel
        // Ini sama seperti: require_once 'model/DocumentModel.php';
        // CI4 otomatis tahu file-nya ada di mana berkat namespace
        $this->documentModel = new DocumentModel();

        // Load helper bawaan CI4 untuk URL (base_url, site_url, dll)
        // dan Form (form_open, form_close, dll)
        helper(['url', 'form']);
    }

    /**
     * ============================================================
     * FUNGSI INDEX - Menampilkan daftar semua dokumen
     * ============================================================
     * 
     * URL: /document atau /document/index
     * Method HTTP: GET
     * 
     * Perbandingan dengan PHP Native:
     * ---------------------------------------------------------------
     * PHP Native (dokumen.php):
     *   [php]
     *   include 'koneksi.php';
     *   $query = mysqli_query($koneksi, "SELECT * FROM documents");
     *   [/php]
     *   <html>
     *   <table>
     *   [php] while($row = mysqli_fetch_assoc($query)) { [/php]
     *     <tr><td>[php] $row['judul'] [/php]</td></tr>
     *   [php] } [/php]
     *   </table>
     * 
     * CI4 (di bawah):
     *   1. Ambil data dari Model
     *   2. Kirim data ke View
     *   3. View yang urus tampilannya (terpisah dari logika)
     * ---------------------------------------------------------------
     */
    public function index()
    {
        // ============================================================
        // LANGKAH 1: Tangkap parameter filter dari URL (GET request)
        // ============================================================
        // Saat user mengisi form pencarian dan klik "Filter",
        // datanya dikirim lewat URL, contoh:
        //   /document?keyword=kontrak&category_id=2&status=aktif
        //
        // getGet('nama') = ambil parameter dari URL (sama seperti $_GET['nama'] di PHP Native)
        // Bedanya: CI4 otomatis sanitize inputnya agar aman
        $keyword    = $this->request->getGet('keyword');
        $categoryId = $this->request->getGet('category_id');
        $status     = $this->request->getGet('status');

        // Susun array filter (hanya yang bernilai)
        // Array ini akan dikirim ke Model untuk diterapkan sebagai WHERE clause
        $filters = [];
        if (!empty($keyword))    $filters['keyword']     = $keyword;
        if (!empty($categoryId)) $filters['category_id'] = $categoryId;
        if (!empty($status))     $filters['status']      = $status;

        // ============================================================
        // LANGKAH 2: Ambil data dokumen dari Model (dengan filter)
        // ============================================================
        // getDocuments($filters) sekarang menerima array filter
        // Jika $filters kosong, semua dokumen akan ditampilkan (tanpa WHERE)
        // Jika ada filter, hanya dokumen yang cocok yang dikembalikan
        $documents = $this->documentModel->getDocuments($filters);

        // ============================================================
        // LANGKAH 3: Ambil daftar kategori untuk dropdown filter
        // ============================================================
        // Kita perlu mengirim data kategori ke View agar dropdown
        // "Semua Kategori" bisa diisi secara dinamis dari database
        $categoryModel = new \App\Models\CategoryModel();
        $categories = $categoryModel->orderBy('nama_kategori', 'ASC')->findAll();

        // ============================================================
        // LANGKAH 4: Siapkan data untuk View
        // ============================================================
        $data = [
            'title'      => 'Daftar Dokumen',
            'documents'  => $documents,
            'categories' => $categories,       // Untuk dropdown filter
            'filters'    => [                  // Untuk mengisi kembali form setelah submit
                'keyword'     => $keyword,
                'category_id' => $categoryId,
                'status'      => $status,
            ],
        ];

        return view('document/index', $data);
    }

    /**
     * ============================================================
     * FUNGSI DETAIL - Menampilkan detail 1 dokumen
     * ============================================================
     * 
     * URL: /document/detail/1 (angka 1 = ID dokumen)
     * Method HTTP: GET
     * 
     * Parameter $id otomatis diambil dari URL oleh CI4.
     * Contoh URL /document/detail/5 -> $id = 5
     */
    public function detail($id = null)
    {
        // Ambil data dokumen berdasarkan ID
        $document = $this->documentModel->getDocumentById($id);

        // Jika dokumen tidak ditemukan, tampilkan halaman 404
        // throw = lempar error (cara CI4 menangani halaman tidak ditemukan)
        if ($document === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Dokumen dengan ID ' . $id . ' tidak ditemukan.'
            );
        }

        $data = [
            'title'    => 'Detail Dokumen - ' . $document['judul'],
            'document' => $document,
        ];

        return view('document/detail', $data);
    }

    /**
     * ============================================================
     * FUNGSI CREATE - Menampilkan form upload dokumen baru
     * ============================================================
     * 
     * URL: /document/create
     * Method HTTP: GET
     * 
     * Fungsi ini hanya menampilkan halaman form.
     * Proses upload yang sebenarnya ada di fungsi upload().
     */
    public function create()
    {
        // Ambil daftar kategori untuk ditampilkan di dropdown <select>
        // Di PHP Native: $kategori = mysqli_query($koneksi, "SELECT * FROM categories");
        $categoryModel = new \App\Models\CategoryModel();
        $instansiModel = new \App\Models\InstansiModel();

        $data = [
            'title'      => 'Upload Dokumen Baru',
            'categories' => $categoryModel->findAll(), // findAll() = SELECT * FROM categories
            'instansis'  => $instansiModel->findAll(), // Ambil data instansi
            'validation' => \Config\Services::validation(), // Untuk menampilkan pesan error validasi
        ];

        return view('document/create', $data);
    }

    /**
     * ============================================================
     * FUNGSI UPLOAD - Proses upload & simpan dokumen ke database
     * ============================================================
     * 
     * URL: /document/upload
     * Method HTTP: POST (dikirim dari form)
     * 
     * Ini adalah fungsi paling penting di Iterasi 1!
     * 
     * Perbandingan dengan PHP Native:
     * ---------------------------------------------------------------
     * PHP Native:
     *   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
     *     $file = $_FILES['dokumen'];
     *     $nama_asli = $file['name'];
     *     $tmp = $file['tmp_name'];
     *     $ukuran = $file['size'];
     *     
     *     // Validasi manual
     *     if ($ukuran > 10000000) { echo "File terlalu besar!"; }
     *     
     *     // Pindahkan file
     *     $nama_baru = time() . '_' . $nama_asli;
     *     move_uploaded_file($tmp, 'uploads/' . $nama_baru);
     *     
     *     // Simpan ke database
     *     $query = "INSERT INTO documents (...) VALUES (...)";
     *     mysqli_query($koneksi, $query);
     *   }
     * 
     * CI4 (di bawah):
     *   Menggunakan library upload bawaan CI4 yang lebih aman dan praktis.
     * ---------------------------------------------------------------
     */
    public function upload()
    {
        // ============================================================
        // LANGKAH 1: VALIDASI INPUT
        // ============================================================
        // CI4 punya sistem validasi bawaan yang jauh lebih rapi dari PHP Native
        // Di PHP Native kamu harus cek satu-satu: if(empty($judul)) { ... }
        // Di CI4, cukup definisikan aturannya dalam array:

        $rules = [
            'judul' => [
                'label'  => 'Judul Dokumen',
                'rules'  => 'required|min_length[3]|max_length[255]',
                // Pesan error kustom dalam Bahasa Indonesia
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'min_length' => '{field} minimal {param} karakter.',
                    'max_length' => '{field} maksimal {param} karakter.',
                ],
            ],
            'category_id' => [
                'label'  => 'Kategori',
                'rules'  => 'required|numeric',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'numeric'  => '{field} harus berupa angka.',
                ],
            ],
            'dokumen' => [
                'label'  => 'File Dokumen',
                // Aturan validasi file CI4:
                // uploaded[dokumen]     = pastikan file benar-benar diupload
                // max_size[dokumen,10240] = maksimal 10MB (10240 KB)
                // ext_in[...]           = hanya boleh ekstensi tertentu
                'rules'  => 'uploaded[dokumen]|max_size[dokumen,10240]|ext_in[dokumen,pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png]',
                'errors' => [
                    'uploaded' => '{field} wajib diupload.',
                    'max_size' => '{field} maksimal 10 MB.',
                    'ext_in'   => '{field} harus berformat: pdf, doc, docx, xls, xlsx, ppt, pptx, jpg, jpeg, atau png.',
                ],
            ],
            'nomor_dokumen' => [
                'label'  => 'Nomor Dokumen',
                'rules'  => 'permit_empty|max_length[100]',
                'errors' => [
                    'max_length' => '{field} maksimal {param} karakter.',
                ],
            ],
        ];

        // Jalankan validasi. Jika GAGAL, kembali ke halaman form dengan pesan error
        if (!$this->validate($rules)) {
            // withInput() = bawa kembali data yang sudah diketik user agar tidak hilang
            // Sama seperti di PHP Native: value="[php] $_POST['judul'] ?? '' [/php]"
            return redirect()->back()->withInput();
        }

        // ============================================================
        // LANGKAH 2: AMBIL FILE YANG DIUPLOAD
        // ============================================================

        // getFile('dokumen') = ambil file dari input dengan name="dokumen"
        // Di PHP Native: $_FILES['dokumen']
        $file = $this->request->getFile('dokumen');

        // Cek apakah file valid dan benar-benar diupload (bukan hasil manipulasi)
        // isValid() = cek apakah tidak ada error saat upload
        // hasMoved() = cek apakah file belum dipindahkan (mencegah duplikat proses)
        if (!$file->isValid() || $file->hasMoved()) {
            // Jika file bermasalah, kembali ke form dengan pesan error
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mengupload file. Silakan coba lagi.');
        }

        // ============================================================
        // LANGKAH 3: PINDAHKAN FILE KE FOLDER UPLOADS
        // ============================================================

        // Ambil nama file asli dari user (contoh: "Kontrak Proyek A.pdf")
        $namaAsli = $file->getClientName();

        // Buat nama file baru yang unik agar tidak bentrok jika ada nama sama
        // getRandomName() = generate nama acak (contoh: "1686744000_abc123def456.pdf")
        // Di PHP Native: $nama_baru = time() . '_' . rand() . '.' . $ekstensi;
        $namaBaru = $file->getRandomName();

        // Tentukan folder tujuan upload
        // WRITEPATH = path ke folder "writable/" di root proyek CI4
        // Jadi file akan disimpan di: writable/uploads/documents/
        $folderUpload = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'documents';

        // Pindahkan file dari lokasi temporary ke folder tujuan
        // move() = sama seperti move_uploaded_file() di PHP Native
        // Parameter 1: folder tujuan
        // Parameter 2: nama file baru
        $file->move($folderUpload, $namaBaru);

        // ============================================================
        // LANGKAH 4: SIMPAN DATA KE DATABASE
        // ============================================================

        // Ambil data dari form yang dikirim via POST
        // getPost('nama_field') = sama seperti $_POST['nama_field'] di PHP Native
        // Bedanya: CI4 sudah otomatis sanitize inputnya
        $dataDocument = [
            'judul'          => $this->request->getPost('judul'),
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'nama_file'      => $namaBaru,                    // Nama file yang sudah di-rename
            'nama_file_asli' => $namaAsli,                    // Nama file asli dari user
            'ukuran_file'    => $file->getSize(),              // Ukuran file dalam bytes
            'tipe_file'      => $file->getClientExtension(),   // Ekstensi file (pdf, docx, dll)
            'category_id'    => $this->request->getPost('category_id'),
            'instansi_id'    => empty($this->request->getPost('instansi_id')) ? null : $this->request->getPost('instansi_id'),
            'nomor_dokumen'  => empty($this->request->getPost('nomor_dokumen')) ? null : $this->request->getPost('nomor_dokumen'),
            'uploaded_by'    => session()->get('user_id') ?? 1, // ID user yang login (sementara hardcode 1)
            'status'         => 'aktif',
        ];

        // Panggil fungsi insertDocument() dari Model untuk menyimpan ke database
        $insertedId = $this->documentModel->insertDocument($dataDocument);

        // Cek apakah penyimpanan berhasil
        if ($insertedId) {
            // ============================================================
            // LANGKAH 5: SIMPAN JUGA KE TABEL VERSI (versi pertama)
            // ============================================================
            $versionModel = new \App\Models\DocumentVersionModel();
            $versionModel->insert([
                'document_id'    => $insertedId,
                'nomor_versi'    => 1,
                'nama_file'      => $namaBaru,
                'nama_file_asli' => $namaAsli,
                'ukuran_file'    => $file->getSize(),
                'catatan'        => 'Upload pertama (versi awal)',
                'uploaded_by'    => session()->get('user_id') ?? 1,
            ]);

            // ============================================================
            // LANGKAH 6: CATAT AKTIVITAS KE AUDIT LOG
            // ============================================================
            // Setiap upload berhasil, kita rekam ke tabel audit_logs
            // agar ada jejak siapa mengupload dokumen apa dan kapan.
            $auditLogModel = new \App\Models\AuditLogModel();
            $auditLogModel->insertLog([
                'user_id'       => session()->get('user_id') ?? 1,
                'aksi'          => 'Upload',
                'document_name' => $dataDocument['judul'],
                'keterangan'    => 'Mengupload dokumen baru: "' . $dataDocument['judul'] . '" (' . strtoupper($file->getClientExtension()) . ')',
            ]);

            // Redirect ke halaman daftar dokumen dengan pesan sukses
            return redirect()->to('/document')
                ->with('success', 'Dokumen "' . $dataDocument['judul'] . '" berhasil diupload!');
        }

        // Jika gagal simpan ke database
        return redirect()->back()
            ->withInput()
            ->with('error', 'Gagal menyimpan data dokumen ke database.');
    }

    /**
     * ============================================================
     * FUNGSI DELETE - Hapus dokumen dari database dan file dari server
     * ============================================================
     * 
     * URL: /document/delete/1 (angka 1 = ID dokumen)
     * Method HTTP: POST (untuk keamanan, delete tidak boleh via GET)
     */
    public function delete($id = null)
    {
        // Ambil data dokumen untuk mendapatkan nama file yang akan dihapus
        $document = $this->documentModel->getDocumentById($id);

        if ($document === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Dokumen tidak ditemukan.'
            );
        }

        // Hapus file fisik dari server (jika masih ada)
        $filePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . $document['nama_file'];
        if (file_exists($filePath)) {
            // unlink() = hapus file, sama seperti di PHP Native
            unlink($filePath);
        }

        // Hapus data dari database
        // Versi dokumen akan otomatis terhapus karena ON DELETE CASCADE
        $this->documentModel->deleteDocument($id);

        // ============================================================
        // CATAT AKTIVITAS HAPUS KE AUDIT LOG
        // ============================================================
        $auditLogModel = new \App\Models\AuditLogModel();
        $auditLogModel->insertLog([
            'user_id'       => session()->get('user_id') ?? 1,
            'aksi'          => 'Hapus',
            'document_name' => $document['judul'],
            'keterangan'    => 'Menghapus dokumen: "' . $document['judul'] . '"',
        ]);

        return redirect()->to('/document')
            ->with('success', 'Dokumen "' . $document['judul'] . '" berhasil dihapus.');
    }

    /**
     * ============================================================
     * FUNGSI EDIT - Menampilkan form edit/revisi dokumen
     * ============================================================
     * 
     * URL: /document/edit/1 (angka 1 = ID dokumen)
     * Method HTTP: GET
     * 
     * Form ini menampilkan data dokumen yang sudah ada, dan user bisa:
     * - Mengubah judul, kategori, deskripsi, status (aktif/arsip)
     * - Upload file baru (opsional, untuk revisi dokumen)
     */
    public function edit($id = null)
    {
        $document = $this->documentModel->getDocumentById($id);

        if ($document === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Dokumen dengan ID ' . $id . ' tidak ditemukan.'
            );
        }

        // Ambil daftar kategori dan instansi untuk dropdown
        $categoryModel = new \App\Models\CategoryModel();
        $instansiModel = new \App\Models\InstansiModel();

        $data = [
            'title'      => 'Edit Dokumen - ' . $document['judul'],
            'document'   => $document,
            'categories' => $categoryModel->findAll(),
            'instansis'  => $instansiModel->findAll(),
            'validation' => \Config\Services::validation(),
        ];

        return view('document/edit', $data);
    }

    /**
     * ============================================================
     * FUNGSI UPDATE - Proses update metadata & revisi file dokumen
     * ============================================================
     * 
     * URL: /document/update/1
     * Method HTTP: POST
     * 
     * Logika update:
     * 1. Validasi input (judul, kategori wajib; file opsional)
     * 2. Update metadata di tabel documents (judul, deskripsi, kategori, status)
     * 3. JIKA ada file baru yang diunggah:
     *    a. Pindahkan file baru ke folder uploads
     *    b. Update nama_file & ukuran_file di tabel documents
     *    c. Simpan ke tabel document_versions sebagai versi terbaru
     * 4. Catat ke audit log
     */
    public function update($id = null)
    {
        $document = $this->documentModel->getDocumentById($id);

        if ($document === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Dokumen tidak ditemukan.'
            );
        }

        // ============================================================
        // LANGKAH 1: VALIDASI INPUT
        // ============================================================
        $rules = [
            'judul' => [
                'label'  => 'Judul Dokumen',
                'rules'  => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required'   => '{field} wajib diisi.',
                    'min_length' => '{field} minimal {param} karakter.',
                    'max_length' => '{field} maksimal {param} karakter.',
                ],
            ],
            'category_id' => [
                'label'  => 'Kategori',
                'rules'  => 'required|numeric',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'numeric'  => '{field} harus berupa angka.',
                ],
            ],
            'status' => [
                'label'  => 'Status',
                'rules'  => 'required|in_list[aktif,arsip]',
                'errors' => [
                    'required' => '{field} wajib dipilih.',
                    'in_list'  => '{field} harus berisi aktif atau arsip.',
                ],
            ],
            'nomor_dokumen' => [
                'label'  => 'Nomor Dokumen',
                'rules'  => 'permit_empty|max_length[100]',
                'errors' => [
                    'max_length' => '{field} maksimal {param} karakter.',
                ],
            ],
        ];

        // Validasi file hanya jika ada file yang diunggah
        $file = $this->request->getFile('dokumen');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $rules['dokumen'] = [
                'label'  => 'File Dokumen',
                'rules'  => 'uploaded[dokumen]|max_size[dokumen,10240]|ext_in[dokumen,pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png]',
                'errors' => [
                    'max_size' => '{field} maksimal 10 MB.',
                    'ext_in'   => 'Format {field} tidak diizinkan.',
                ],
            ];
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput();
        }

        // ============================================================
        // LANGKAH 2: UPDATE METADATA DOKUMEN
        // ============================================================
        $dataUpdate = [
            'judul'       => $this->request->getPost('judul'),
            'deskripsi'   => $this->request->getPost('deskripsi'),
            'category_id' => $this->request->getPost('category_id'),
            'instansi_id' => empty($this->request->getPost('instansi_id')) ? null : $this->request->getPost('instansi_id'),
            'nomor_dokumen' => empty($this->request->getPost('nomor_dokumen')) ? null : $this->request->getPost('nomor_dokumen'),
            'status'      => $this->request->getPost('status'),
        ];

        // ============================================================
        // LANGKAH 3: CEK APAKAH ADA FILE BARU (REVISI)
        // ============================================================
        // Jika user mengunggah file baru, artinya ini adalah REVISI dokumen.
        // File lama TIDAK dihapus (tetap tersimpan di server).
        // File baru disimpan sebagai versi terbaru.
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $namaAsli = $file->getClientName();
            $namaBaru = $file->getRandomName();
            $folderUpload = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'documents';

            // Pindahkan file baru ke folder upload
            $file->move($folderUpload, $namaBaru);

            // Update data file di tabel documents
            $dataUpdate['nama_file']      = $namaBaru;
            $dataUpdate['nama_file_asli'] = $namaAsli;
            $dataUpdate['ukuran_file']    = $file->getSize();
            $dataUpdate['tipe_file']      = $file->getClientExtension();

            // ============================================================
            // SIMPAN KE TABEL VERSI (revisi baru)
            // ============================================================
            // Hitung nomor versi terbaru + 1
            $versionModel = new \App\Models\DocumentVersionModel();
            $lastVersion = $versionModel->where('document_id', $id)
                                        ->orderBy('nomor_versi', 'DESC')
                                        ->first();
            $nomorVersiBaru = ($lastVersion ? $lastVersion['nomor_versi'] : 0) + 1;

            $versionModel->insert([
                'document_id'    => $id,
                'nomor_versi'    => $nomorVersiBaru,
                'nama_file'      => $namaBaru,
                'nama_file_asli' => $namaAsli,
                'ukuran_file'    => $file->getSize(),
                'catatan'        => 'Revisi versi ' . $nomorVersiBaru,
                'uploaded_by'    => session()->get('user_id') ?? 1,
            ]);
        }

        // Update data dokumen di database
        $this->documentModel->updateDocument($id, $dataUpdate);

        // ============================================================
        // LANGKAH 4: CATAT KE AUDIT LOG
        // ============================================================
        $aksi = ($file && $file->isValid()) ? 'Revisi' : 'Edit';
        $auditLogModel = new \App\Models\AuditLogModel();
        $auditLogModel->insertLog([
            'user_id'       => session()->get('user_id') ?? 1,
            'aksi'          => $aksi,
            'document_name' => $this->request->getPost('judul'),
            'keterangan'    => 'Merevisi dokumen: "' . $this->request->getPost('judul') . '"',
        ]);

        return redirect()->to('/document')
            ->with('success', 'Dokumen "' . $this->request->getPost('judul') . '" berhasil diperbarui!');
    }

    /**
     * ============================================================
     * FUNGSI PREVIEW - Tampilkan file langsung di browser (tab baru)
     * ============================================================
     * 
     * URL: /document/preview/1
     * Method HTTP: GET
     * 
     * Fungsi ini melakukan "streaming" file ke browser tanpa mengunduhnya.
     * Khususnya berguna untuk file PDF dan gambar yang bisa langsung
     * ditampilkan oleh browser.
     * 
     * Cara kerjanya:
     * 1. Cari data dokumen di database berdasarkan ID
     * 2. Cek apakah file fisik masih ada di server
     * 3. Kirim file ke browser dengan header yang tepat:
     *    - Content-Type: memberitahu browser jenis file (PDF/gambar/dll)
     *    - Content-Disposition: inline = tampilkan di browser (bukan download)
     * 
     * Perbandingan dengan PHP Native:
     * ---------------------------------------------------------------
     * PHP Native:
     *   header('Content-Type: application/pdf');
     *   header('Content-Disposition: inline; filename="file.pdf"');
     *   readfile('uploads/file.pdf');
     *   exit;
     * 
     * CI4: Menggunakan $this->response untuk mengirim file.
     * ---------------------------------------------------------------
     */
    public function preview($id = null)
    {
        // Ambil data dokumen dari database
        $document = $this->documentModel->getDocumentById($id);

        // Jika dokumen tidak ditemukan di database, tampilkan 404
        if ($document === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Dokumen tidak ditemukan.'
            );
        }

        // Susun path lengkap ke file fisik di server
        $filePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . $document['nama_file'];

        // Cek apakah file fisik masih ada di folder uploads
        if (!file_exists($filePath)) {
            // Jika file sudah dihapus manual dari folder, tampilkan error
            return redirect()->back()
                ->with('error', 'File fisik "' . $document['nama_file_asli'] . '" tidak ditemukan di server.');
        }

        // Tentukan MIME type berdasarkan ekstensi file
        // MIME type memberitahu browser cara menampilkan file:
        //   - application/pdf -> browser tampilkan PDF viewer
        //   - image/jpeg      -> browser tampilkan gambar
        //   - application/octet-stream -> browser download (default)
        $mimeTypes = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'  => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt'  => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];

        $ext = strtolower($document['tipe_file']);
        $mimeType = $mimeTypes[$ext] ?? 'application/octet-stream';

        // Kirim header HTTP agar browser tahu cara menampilkan file
        // Content-Type    = jenis file
        // Content-Disposition: inline = tampilkan langsung di browser (bukan download)
        // Content-Length  = ukuran file (agar progress bar browser bisa tampil)
        //
        // Ini sama persis seperti di PHP Native:
        //   header('Content-Type: application/pdf');
        //   header('Content-Disposition: inline; filename="namafile.pdf"');
        //   header('Content-Length: ' . filesize($filepath));
        //   readfile($filepath);
        //   exit;
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $document['nama_file_asli'] . '"')
            ->setHeader('Content-Length', (string) filesize($filePath))
            ->setHeader('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->setBody(file_get_contents($filePath));
    }

    /**
     * ============================================================
     * FUNGSI DOWNLOAD - Unduh file ke komputer user
     * ============================================================
     * 
     * URL: /document/download/1
     * Method HTTP: GET
     * 
     * Berbeda dengan preview, fungsi ini memaksa browser
     * untuk mengunduh file (bukan menampilkannya).
     * Perbedaannya ada di header: Content-Disposition: attachment (bukan inline)
     */
    public function download($id = null)
    {
        // Ambil data dokumen
        $document = $this->documentModel->getDocumentById($id);

        if ($document === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(
                'Dokumen tidak ditemukan.'
            );
        }

        $filePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'documents' . DIRECTORY_SEPARATOR . $document['nama_file'];

        if (!file_exists($filePath)) {
            return redirect()->back()
                ->with('error', 'File fisik "' . $document['nama_file_asli'] . '" tidak ditemukan di server.');
        }

        // download() adalah helper bawaan CI4 untuk mengunduh file
        // Parameter 1: path ke file di server
        // Parameter 2: nama file yang akan dilihat user saat download
        //
        // Di balik layar, CI4 mengirim header:
        //   Content-Disposition: attachment; filename="namafile.pdf"
        // "attachment" membuat browser mengunduh, bukan menampilkan
        return $this->response->download($filePath, null)
            ->setFileName($document['nama_file_asli']);
    }
}