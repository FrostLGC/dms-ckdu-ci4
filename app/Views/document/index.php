<!-- ============================================================
     HALAMAN: Daftar Dokumen (dengan Pencarian & Filter)
     ============================================================
     Halaman ini meng-EXTEND layout utama (layout/main.php).

     Cara kerja CI4 Layout:
     - extend() = "Saya mau pakai template layout/main.php sebagai bingkai"
     - section('content') = "Ini adalah konten yang akan disisipkan ke layout"
     - endSection() = "Konten selesai"
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Toolbar: Judul halaman + tombol aksi -->
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--dms-dark);">
            <i class="bi bi-file-earmark-text-fill me-2" style="color: var(--dms-primary);"></i>
            Daftar Dokumen
        </h4>
        <p class="text-muted mb-0" style="font-size: .85rem;">
            Kelola semua dokumen perusahaan di satu tempat
        </p>
    </div>
    <?php if (in_array(session()->get('user_role'), ['admin', 'hrd'])) : ?>
    <a href="<?= base_url('document/create') ?>" class="btn btn-dms-primary">
        <i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload Dokumen
    </a>
    <?php endif; ?>
</div>

<!-- ============================================================
     FORM PENCARIAN & FILTER
     ============================================================
     method="GET" artinya data dikirim lewat URL (bukan POST).
     Ini penting karena:
     1. URL bisa di-bookmark/share: /document?keyword=kontrak&status=aktif
     2. Tombol Back browser tetap berfungsi
     3. Tidak perlu CSRF token (tidak mengubah data)

     Perbandingan PHP Native:
       <form action="dokumen.php" method="GET">
         <input name="cari" value="<?= $_GET['cari'] ?? '' ?>">
       </form>
       Lalu di query: WHERE judul LIKE '%{$_GET['cari']}%'

     CI4:
       Form dikirim ke /document (halaman ini juga)
       Controller menangkap parameter via $this->request->getGet()
       Model menerapkan filter via Query Builder
     ============================================================ -->
<div class="card mb-4 animate-in">
    <div class="card-body" style="padding:20px 22px;">
        <form action="<?= base_url('document') ?>" method="GET" id="filterForm">
            <div class="row g-3 align-items-end">

                <!-- Input: Cari Judul/Deskripsi -->
                <div class="col-lg-3 col-md-6">
                    <label for="keyword" class="form-label" style="font-size:.8rem;">
                        <i class="bi bi-search me-1"></i> Cari Dokumen
                    </label>
                    <input type="text"
                           class="form-control"
                           id="keyword" name="keyword"
                           value="<?= esc($filters['keyword'] ?? '') ?>"
                           placeholder="Ketik judul, nomor, atau deskripsi...">
                </div>

                <!-- Dropdown: Kategori -->
                <div class="col-lg-2 col-md-6">
                    <label for="category_id" class="form-label" style="font-size:.8rem;">
                        <i class="bi bi-folder2 me-1"></i> Kategori
                    </label>
                    <select class="form-select" id="category_id" name="category_id">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $cat) : ?>
                            <option value="<?= $cat['id'] ?>"
                                    <?= ($filters['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= esc($cat['nama_kategori']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Dropdown: Instansi/Mitra -->
                <div class="col-lg-2 col-md-6">
                    <label for="instansi_id" class="form-label" style="font-size:.8rem;">
                        <i class="bi bi-building me-1"></i> Instansi / Mitra
                    </label>
                    <select class="form-select" id="instansi_id" name="instansi_id">
                        <option value="">Semua Instansi</option>
                        <?php foreach ($instansis as $inst) : ?>
                            <option value="<?= $inst['id'] ?>"
                                    <?= ($filters['instansi_id'] ?? '') == $inst['id'] ? 'selected' : '' ?>>
                                <?= esc($inst['nama_instansi']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Dropdown: Status -->
                <div class="col-lg-2 col-md-4">
                    <label for="status" class="form-label" style="font-size:.8rem;">
                        <i class="bi bi-toggle-on me-1"></i> Status
                    </label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="aktif" <?= ($filters['status'] ?? '') == 'aktif' ? 'selected' : '' ?>>
                            Aktif
                        </option>
                        <option value="arsip" <?= ($filters['status'] ?? '') == 'arsip' ? 'selected' : '' ?>>
                            Arsip
                        </option>
                    </select>
                </div>

                <!-- Tombol Filter & Reset -->
                <div class="col-lg-3 col-md-12 d-flex align-items-end mt-3 mt-lg-0">
                    <div class="d-flex gap-2 w-100">
                        <a href="<?= base_url('document') ?>"
                           class="btn btn-light border text-secondary d-flex align-items-center justify-content-center gap-1 flex-fill" style="border-radius:10px;">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </a>
                        <button type="submit" class="btn btn-dms-primary d-flex align-items-center justify-content-center gap-1 flex-fill"> 
                            Filter
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<!-- ============================================================
     INDIKATOR FILTER AKTIF
     Menampilkan badge yang menunjukkan filter apa saja yang aktif.
     Membantu user memahami mengapa daftar terbatas.
     ============================================================ -->
<?php
    // Cek apakah ada filter yang aktif
    $hasFilter = !empty($filters['keyword']) || !empty($filters['category_id']) || !empty($filters['status']) || !empty($filters['instansi_id']);
?>
<?php if ($hasFilter) : ?>
<div class="d-flex align-items-center gap-2 mb-3 animate-in" style="flex-wrap:wrap;">
    <span class="text-muted" style="font-size:.82rem;">
        <i class="bi bi-funnel-fill me-1"></i> Filter aktif:
    </span>
    <?php if (!empty($filters['keyword'])) : ?>
        <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.78rem; font-weight:600; padding:5px 12px; border-radius:20px;">
            🔍 "<?= esc($filters['keyword']) ?>"
        </span>
    <?php endif; ?>
    <?php if (!empty($filters['category_id'])) : ?>
        <?php
            $namaKat = '-';
            foreach ($categories as $cat) {
                if ($cat['id'] == $filters['category_id']) {
                    $namaKat = $cat['nama_kategori'];
                    break;
                }
            }
        ?>
        <span class="badge bg-info bg-opacity-10 text-info" style="font-size:.78rem; font-weight:600; padding:5px 12px; border-radius:20px;">
            📂 <?= esc($namaKat) ?>
        </span>
    <?php endif; ?>
    <?php if (!empty($filters['instansi_id'])) : ?>
        <?php
            $namaInst = '-';
            foreach ($instansis as $inst) {
                if ($inst['id'] == $filters['instansi_id']) {
                    $namaInst = $inst['nama_instansi'];
                    break;
                }
            }
        ?>
        <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size:.78rem; font-weight:600; padding:5px 12px; border-radius:20px;">
            🏢 <?= esc($namaInst) ?>
        </span>
    <?php endif; ?>
    <?php if (!empty($filters['status'])) : ?>
        <span class="badge <?= $filters['status'] == 'aktif' ? 'bg-success bg-opacity-10 text-success' : 'bg-warning bg-opacity-10 text-warning' ?>"
              style="font-size:.78rem; font-weight:600; padding:5px 12px; border-radius:20px;">
            <?= $filters['status'] == 'aktif' ? '● Aktif' : '● Arsip' ?>
        </span>
    <?php endif; ?>
    <a href="<?= base_url('document') ?>" class="text-muted text-decoration-none" style="font-size:.78rem;">
        <i class="bi bi-x-circle me-1"></i> Hapus semua filter
    </a>
</div>
<?php endif; ?>

<!-- Tabel Dokumen dalam Card -->
<?php if (!empty($documents)) : ?>
<div class="card animate-in">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Judul Dokumen</th>
                        <th>No. Dokumen</th>
                        <th>Kategori</th>
                        <th>Instansi</th>
                        <th>Tipe</th>
                        <th>Ukuran</th>
                        <th>Uploader</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($documents as $doc) : ?>
                    <tr>
                        <td class="text-muted"><?= $no++ ?></td>
                        <td>
                            <a href="<?= base_url('document/detail/' . $doc['id']) ?>"
                               class="text-decoration-none fw-semibold" style="color: var(--dms-primary);">
                                <?= esc($doc['judul']) ?>
                            </a>
                        </td>
                        <td>
                            <?php if (!empty($doc['nomor_dokumen'])) : ?>
                                <span class="badge bg-light text-dark border" style="font-weight:500;">
                                    <?= esc($doc['nomor_dokumen']) ?>
                                </span>
                            <?php else : ?>
                                <span class="badge bg-light text-muted border" style="font-weight:500;">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border" style="font-weight:500;">
                                <?= esc($doc['nama_kategori'] ?? '-') ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border" style="font-weight:500;">
                                <?= esc($doc['nama_instansi'] ?? 'Internal') ?>
                            </span>
                        </td>
                        <td>
                            <?php
                                // Beri warna badge sesuai tipe file
                                $tipe = strtolower($doc['tipe_file']);
                                $cssClass = 'file-type-' . $tipe;
                            ?>
                            <span class="file-type-badge <?= $cssClass ?>">
                                <?= strtoupper(esc($doc['tipe_file'])) ?>
                            </span>
                        </td>
                        <td class="text-muted" style="font-size:.85rem;">
                            <?php
                                $ukuran = $doc['ukuran_file'];
                                if ($ukuran >= 1048576) {
                                    echo number_format($ukuran / 1048576, 1) . ' MB';
                                } elseif ($ukuran >= 1024) {
                                    echo number_format($ukuran / 1024, 1) . ' KB';
                                } else {
                                    echo $ukuran . ' B';
                                }
                            ?>
                        </td>
                        <td style="font-size:.85rem;"><?= esc($doc['nama_uploader'] ?? '-') ?></td>
                        <td>
                            <span class="badge-<?= $doc['status'] ?>">
                                <?= $doc['status'] == 'aktif' ? '● Aktif' : '● Arsip' ?>
                            </span>
                        </td>
                        <td style="font-size:.82rem;" class="text-muted">
                            <?= date('d M Y', strtotime($doc['created_at'])) ?>
                        </td>
                        <td>
                            <?php
                                $role = session()->get('user_role');
                                $userId = (int) session()->get('user_id');
                                $canModify = $role === 'admin' || ($role === 'hrd' && (int) ($doc['uploaded_by'] ?? 0) === $userId);
                            ?>
                            <div class="d-flex gap-1">
                                <!-- Tombol Detail: semua role -->
                                <a href="<?= base_url('document/detail/' . $doc['id']) ?>"
                                   class="btn btn-sm btn-outline-primary" title="Detail"
                                   style="border-radius:8px;">
                                    <i class="bi bi-eye-fill"></i>
                                </a>
                                <?php if ($canModify) : ?>
                                <!-- Tombol Edit/Revisi -->
                                <a href="<?= base_url('document/edit/' . $doc['id']) ?>"
                                   class="btn btn-sm btn-outline-warning" title="Edit / Revisi"
                                   style="border-radius:8px;">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <!-- Tombol Hapus -->
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        title="Hapus" style="border-radius:8px;"
                                        data-bs-toggle="modal" data-bs-target="#deleteDocumentModal"
                                        data-document-title="<?= esc($doc['judul']) ?>"
                                        data-delete-url="<?= base_url('document/delete/' . $doc['id']) ?>">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Footer tabel: jumlah dokumen -->
    <div class="card-footer bg-transparent text-muted" style="font-size:.8rem; padding:12px 22px;">
        <?php if ($hasFilter) : ?>
            Ditemukan <strong><?= count($documents) ?></strong> dokumen sesuai filter
        <?php else : ?>
            Menampilkan <?= count($documents) ?> dokumen
        <?php endif; ?>
    </div>
</div>

<?php else : ?>
<!-- Empty State -->
<div class="card animate-in">
    <div class="card-body empty-state">
        <?php if ($hasFilter) : ?>
            <!-- Empty state khusus saat filter aktif tapi tidak ada hasil -->
            <div class="empty-icon">🔍</div>
            <h5>Tidak ada dokumen yang cocok</h5>
            <p class="text-muted mb-3">Coba ubah kata kunci atau filter pencarian Anda</p>
            <a href="<?= base_url('document') ?>" class="btn btn-outline-primary" style="border-radius:10px;">
                <i class="bi bi-arrow-counterclockwise me-2"></i> Reset Filter
            </a>
        <?php else : ?>
            <!-- Empty state saat belum ada dokumen sama sekali -->
            <div class="empty-icon">📁</div>
            <h5>Belum ada dokumen</h5>
            <p class="text-muted mb-3">Mulai dengan mengupload dokumen pertama Anda</p>
            <a href="<?= base_url('document/create') ?>" class="btn btn-dms-primary">
                <i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload Dokumen
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Modal Konfirmasi Hapus Dokumen -->
<div class="modal fade" id="deleteDocumentModal" tabindex="-1" aria-labelledby="deleteDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="deleteDocumentModalLabel" style="color:var(--dms-dark);">Hapus Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-4 pb-4">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:3rem; opacity:0.9;"></i>
                </div>
                <p class="mb-1" style="font-size:1.05rem;">
                    Apakah Anda yakin ingin menghapus dokumen <strong id="deleteDocumentTitle"></strong>?
                </p>
                <p class="text-muted" style="font-size:.85rem;">
                    Dokumen beserta riwayat versinya tidak dapat digunakan kembali setelah dihapus.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0 d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius:8px;">Batal</button>
                <form id="formDeleteDocument" method="POST" style="display:inline;">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger px-4" style="border-radius:8px;">
                        <i class="bi bi-trash3-fill me-1"></i> Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteDocumentModal = document.getElementById('deleteDocumentModal');
    if (deleteDocumentModal) {
        deleteDocumentModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const docTitle = button.getAttribute('data-document-title');
            const deleteUrl = button.getAttribute('data-delete-url');

            const modalDocTitle = deleteDocumentModal.querySelector('#deleteDocumentTitle');
            const formDelete = deleteDocumentModal.querySelector('#formDeleteDocument');

            if (modalDocTitle) modalDocTitle.textContent = docTitle;
            if (formDelete) formDelete.setAttribute('action', deleteUrl);
        });
    }
});
</script>
<?= $this->endSection() ?>
