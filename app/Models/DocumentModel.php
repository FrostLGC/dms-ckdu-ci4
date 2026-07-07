<?php

namespace App\Models;

// Mengimpor kelas Model bawaan CI4
// Di PHP Native, kamu biasa koneksi manual pakai mysqli_connect()
// Di CI4, Model ini sudah otomatis terkoneksi ke database
use CodeIgniter\Model;

/**
 * ============================================================
 * DocumentModel - Model untuk mengelola data dokumen
 * ============================================================
 * 
 * Model ini bertugas sebagai "jembatan" antara Controller dan Database.
 * Analoginya di PHP Native:
 *   - Dulu kamu tulis query langsung di file PHP (campur aduk)
 *   - Di CI4, semua query dikumpulkan di Model agar rapi dan bisa dipakai ulang
 */
class DocumentModel extends Model
{
    // ============================================================
    // KONFIGURASI DASAR MODEL CI4
    // ============================================================

    // Nama tabel yang dikelola oleh model ini
    // Sama seperti: "SELECT * FROM documents" -> tabel "documents"
    protected $table = 'documents';

    // Nama kolom Primary Key
    protected $primaryKey = 'id';

    // Kolom-kolom yang BOLEH diisi melalui insert/update
    // Ini adalah fitur keamanan CI4 agar tidak sembarang kolom bisa diubah
    // Analoginya: whitelist kolom yang aman untuk diisi user
    protected $allowedFields = [
        'judul',
        'deskripsi',
        'nama_file',
        'nama_file_asli',
        'ukuran_file',
        'tipe_file',
        'category_id',
        'instansi_id',
        'nomor_dokumen',
        'uploaded_by',
        'status',
    ];

    // Aktifkan fitur timestamp otomatis
    // CI4 akan otomatis mengisi "created_at" dan "updated_at"
    // Jadi kamu tidak perlu set manual seperti di PHP Native: date('Y-m-d H:i:s')
    protected $useTimestamps = true;

    // Format timestamp yang digunakan (datetime standar MySQL)
    protected $dateFormat = 'datetime';

    // Nama kolom timestamp (sesuaikan dengan nama kolom di tabel)
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // ============================================================
    // FUNGSI-FUNGSI QUERY (CUSTOM METHOD)
    // ============================================================

    /**
     * Ambil semua dokumen beserta nama kategori dan nama user yang upload.
     * 
     * Perbandingan dengan PHP Native:
     * ---------------------------------------------------------------
     * PHP Native:
     *   $query = "SELECT d.*, c.nama_kategori, u.nama AS nama_uploader
     *             FROM documents d
     *             JOIN categories c ON d.category_id = c.id
     *             JOIN users u ON d.uploaded_by = u.id
     *             ORDER BY d.created_at DESC";
     *   $result = mysqli_query($koneksi, $query);
     * 
     * CI4 Query Builder (di bawah):
     *   Lebih aman dari SQL Injection dan lebih mudah dibaca.
     * ---------------------------------------------------------------
     * 
     * @param array $filters  Array filter opsional, contoh:
     *                        ['keyword' => 'kontrak', 'category_id' => 2, 'status' => 'aktif']
     *                        Jika kosong atau key bernilai null/empty, filter diabaikan.
     * @return array          Array berisi data dokumen yang cocok
     */
    public function getDocuments($filters = [])
    {
        // Mulai membangun query dengan Query Builder CI4
        // select() = kolom yang ingin diambil
        // join()   = gabungkan dengan tabel lain (seperti JOIN di SQL)
        $builder = $this->db->table('documents AS d')
            ->select('
                d.id,
                d.judul,
                d.deskripsi,
                d.nama_file,
                d.nama_file_asli,
                d.ukuran_file,
                d.tipe_file,
                d.nomor_dokumen,
                d.status,
                d.created_at,
                d.updated_at,
                c.nama_kategori,
                i.nama_instansi,
                u.nama AS nama_uploader
            ')
            // JOIN ke tabel categories untuk dapat nama kategori
            // 'c.id = d.category_id' artinya: cocokkan id kategori
            ->join('categories AS c', 'c.id = d.category_id', 'left')
            // JOIN ke tabel instansi
            ->join('instansi AS i', 'i.id = d.instansi_id', 'left')
            // JOIN ke tabel users untuk dapat nama orang yang upload
            ->join('users AS u', 'u.id = d.uploaded_by', 'left');

        // ============================================================
        // FILTER: Pencarian berdasarkan keyword (judul/deskripsi)
        // ============================================================
        // Jika user mengetik kata kunci di form pencarian,
        // kita filter dokumen yang judulnya atau deskripsinya cocok.
        //
        // Perbandingan PHP Native:
        //   $keyword = mysqli_real_escape_string($koneksi, $_GET['keyword']);
        //   $query .= " AND (judul LIKE '%$keyword%' OR deskripsi LIKE '%$keyword%')";
        //
        // CI4:
        //   groupStart() dan groupEnd() menghasilkan tanda kurung di SQL
        //   Hasilnya: AND (judul LIKE '%keyword%' OR deskripsi LIKE '%keyword%')
        //   Tanda kurung penting agar logika OR tidak bentrok dengan filter lain!
        if (!empty($filters['keyword'])) {
            $builder->groupStart()
                        ->like('d.judul', $filters['keyword'])
                        ->orLike('d.deskripsi', $filters['keyword'])
                        ->orLike('d.nomor_dokumen', $filters['keyword'])
                    ->groupEnd();
        }

        // ============================================================
        // FILTER: Berdasarkan kategori
        // ============================================================
        // Jika user memilih kategori tertentu dari dropdown
        //
        // Perbandingan PHP Native:
        //   if (!empty($_GET['category_id'])) {
        //       $query .= " AND d.category_id = " . (int)$_GET['category_id'];
        //   }
        if (!empty($filters['category_id'])) {
            $builder->where('d.category_id', $filters['category_id']);
        }

        // ============================================================
        // FILTER: Berdasarkan status (aktif/arsip)
        // ============================================================
        if (!empty($filters['status'])) {
            $builder->where('d.status', $filters['status']);
        }

        // ============================================================
        // FILTER: Berdasarkan rentang waktu (untuk laporan)
        // ============================================================
        if (!empty($filters['start_date'])) {
            $builder->where('DATE(d.created_at) >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('DATE(d.created_at) <=', $filters['end_date']);
        }

        // Urutkan dari yang terbaru (DESC = descending/menurun)
        $builder->orderBy('d.created_at', 'DESC');

        // Eksekusi query dan kembalikan hasilnya sebagai array
        // getResultArray() = sama seperti mysqli_fetch_all($result, MYSQLI_ASSOC)
        return $builder->get()->getResultArray();
    }

    /**
     * Ambil 1 dokumen berdasarkan ID beserta detail kategori dan uploader.
     * 
     * Perbandingan dengan PHP Native:
     * ---------------------------------------------------------------
     * PHP Native:
     *   $id = mysqli_real_escape_string($koneksi, $_GET['id']);
     *   $query = "SELECT * FROM documents WHERE id = '$id'";
     *   $result = mysqli_query($koneksi, $query);
     *   $data = mysqli_fetch_assoc($result);
     * 
     * CI4 (di bawah):
     *   Parameter sudah otomatis di-escape, jadi aman dari SQL Injection.
     * ---------------------------------------------------------------
     * 
     * @param int $id  ID dokumen yang dicari
     * @return array|null  Data dokumen atau null jika tidak ditemukan
     */
    public function getDocumentById($id)
    {
        $builder = $this->db->table('documents AS d')
            ->select('
                d.*,
                c.nama_kategori,
                c.deskripsi AS deskripsi_kategori,
                i.nama_instansi,
                i.alamat AS alamat_instansi,
                u.nama AS nama_uploader,
                u.email AS email_uploader
            ')
            ->join('categories AS c', 'c.id = d.category_id', 'left')
            ->join('instansi AS i', 'i.id = d.instansi_id', 'left')
            ->join('users AS u', 'u.id = d.uploaded_by', 'left')
            // where() dengan parameter kedua otomatis di-escape oleh CI4
            // Jadi tidak perlu khawatir SQL Injection seperti di PHP Native
            ->where('d.id', $id);

        // getRowArray() = ambil 1 baris saja (seperti mysqli_fetch_assoc)
        return $builder->get()->getRowArray();
    }

    /**
     * Simpan dokumen baru ke database.
     * 
     * Perbandingan dengan PHP Native:
     * ---------------------------------------------------------------
     * PHP Native:
     *   $query = "INSERT INTO documents (judul, deskripsi, ...) 
     *             VALUES ('$judul', '$deskripsi', ...)";
     *   mysqli_query($koneksi, $query);
     *   $id_baru = mysqli_insert_id($koneksi);
     * 
     * CI4 (di bawah):
     *   Cukup kirim array, CI4 yang urus sisanya.
     * ---------------------------------------------------------------
     * 
     * @param array $data  Data dokumen dalam bentuk array asosiatif
     *                     Contoh: ['judul' => 'Kontrak A', 'deskripsi' => '...', ...]
     * @return int|false   ID dokumen yang baru dibuat, atau false jika gagal
     */
    public function insertDocument($data)
    {
        // insert() = masukkan data baru ke tabel
        // CI4 hanya akan memasukkan kolom yang ada di $allowedFields
        // Kolom lain (seperti 'id' atau yang tidak ada di whitelist) akan diabaikan
        $this->insert($data);

        // Kembalikan ID yang baru saja di-generate (auto_increment)
        // Sama seperti mysqli_insert_id() di PHP Native
        return $this->getInsertID();
    }

    /**
     * Update data dokumen berdasarkan ID.
     * 
     * @param int   $id    ID dokumen yang akan diupdate
     * @param array $data  Data yang akan diubah (hanya kolom yang berubah)
     *                     Contoh: ['judul' => 'Judul Baru', 'status' => 'arsip']
     * @return bool        true jika berhasil, false jika gagal
     */
    public function updateDocument($id, $data)
    {
        // update() = perbarui data di tabel WHERE id = $id
        // CI4 otomatis menambahkan WHERE berdasarkan primary key
        return $this->update($id, $data);
    }

    /**
     * Hapus dokumen berdasarkan ID.
     * 
     * PERHATIAN: Karena tabel document_versions punya ON DELETE CASCADE,
     * menghapus dokumen akan OTOMATIS menghapus semua versinya juga.
     * 
     * @param int $id  ID dokumen yang akan dihapus
     * @return bool    true jika berhasil
     */
    public function deleteDocument($id)
    {
        // delete() = hapus data dari tabel WHERE id = $id
        // Sama seperti: "DELETE FROM documents WHERE id = $id"
        return $this->delete($id);
    }

    /**
     * Cari dokumen berdasarkan kata kunci di judul atau deskripsi.
     * 
     * Perbandingan dengan PHP Native:
     * ---------------------------------------------------------------
     * PHP Native:
     *   $keyword = mysqli_real_escape_string($koneksi, $_GET['cari']);
     *   $query = "SELECT * FROM documents 
     *             WHERE judul LIKE '%$keyword%' 
     *             OR deskripsi LIKE '%$keyword%'";
     * 
     * CI4 (di bawah):
     *   Menggunakan groupStart/groupEnd untuk query yang lebih kompleks.
     * ---------------------------------------------------------------
     * 
     * @param string $keyword  Kata kunci pencarian
     * @return array           Array berisi dokumen yang cocok
     */
    public function searchDocuments($keyword)
    {
        $builder = $this->db->table('documents AS d')
            ->select('d.*, c.nama_kategori, i.nama_instansi, u.nama AS nama_uploader')
            ->join('categories AS c', 'c.id = d.category_id', 'left')
            ->join('instansi AS i', 'i.id = d.instansi_id', 'left')
            ->join('users AS u', 'u.id = d.uploaded_by', 'left');

        // groupStart() dan groupEnd() menghasilkan tanda kurung di SQL
        // Hasilnya: WHERE (judul LIKE '%keyword%' OR deskripsi LIKE '%keyword%')
        $builder->groupStart()
                    ->like('d.judul', $keyword)       // LIKE '%keyword%'
                    ->orLike('d.deskripsi', $keyword)  // OR LIKE '%keyword%'
                    ->orLike('d.nomor_dokumen', $keyword) // OR nomor dokumen
                ->groupEnd();

        $builder->orderBy('d.created_at', 'DESC');

        return $builder->get()->getResultArray();
    }
}
