<!-- ============================================================
     HALAMAN: Detail Dokumen
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb navigasi -->
<nav aria-label="breadcrumb" class="mb-4 animate-in">
    <ol class="breadcrumb" style="font-size:.85rem;">
        <li class="breadcrumb-item"><a href="<?= base_url('document') ?>" class="text-decoration-none">Dokumen</a></li>
        <li class="breadcrumb-item active"><?= esc($document['judul']) ?></li>
    </ol>
</nav>

<div class="row g-4">
    <!-- Kolom Kiri: Informasi Dokumen -->
    <div class="col-lg-8 animate-in">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold" style="font-size:1.05rem;">
                    <i class="bi bi-file-earmark-text-fill me-2" style="color:var(--dms-primary);"></i>
                    Informasi Dokumen
                </h5>
                <?php
                    $tipe = strtolower($document['tipe_file']);
                    $cssClass = 'file-type-' . $tipe;
                ?>
                <span class="file-type-badge <?= $cssClass ?>">
                    <?= strtoupper(esc($document['tipe_file'])) ?>
                </span>
            </div>
            <div class="card-body" style="padding:28px;">
                <!-- Judul -->
                <h4 class="fw-bold mb-3" style="color:var(--dms-dark);">
                    <?= esc($document['judul']) ?>
                </h4>

                <!-- Grid detail informasi -->
                <div class="row g-4 mt-1">
                    <div class="col-sm-6">
                        <div class="detail-label">Nomor Dokumen</div>
                        <div class="detail-value">
                            <?php if (!empty($document['nomor_dokumen'])) : ?>
                                <span class="badge bg-light text-dark border" style="font-weight:500; font-size:.85rem;">
                                    <?= esc($document['nomor_dokumen']) ?>
                                </span>
                            <?php else : ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-label">Kategori</div>
                        <div class="detail-value">
                            <span class="badge bg-light text-dark border" style="font-weight:500; font-size:.85rem;">
                                <?= esc($document['nama_kategori'] ?? '-') ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-label">Instansi / Mitra</div>
                        <div class="detail-value">
                            <span class="badge bg-light text-dark border" style="font-weight:500; font-size:.85rem;">
                                <?= esc($document['nama_instansi'] ?? 'Internal CKDU') ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span class="badge-<?= $document['status'] ?>">
                                <?= $document['status'] == 'aktif' ? '● Aktif' : '● Arsip' ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="detail-label">Deskripsi</div>
                        <div class="detail-value">
                            <?= esc($document['deskripsi'] ?: 'Tidak ada deskripsi') ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-label">Nama File Asli</div>
                        <div class="detail-value"><?= esc($document['nama_file_asli']) ?></div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-label">Ukuran File</div>
                        <div class="detail-value">
                            <?php
                                $ukuran = $document['ukuran_file'];
                                if ($ukuran >= 1048576) {
                                    echo number_format($ukuran / 1048576, 1) . ' MB';
                                } elseif ($ukuran >= 1024) {
                                    echo number_format($ukuran / 1024, 1) . ' KB';
                                } else {
                                    echo $ukuran . ' B';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-label">Diupload Oleh</div>
                        <div class="detail-value">
                            <i class="bi bi-person-fill me-1" style="color:var(--dms-primary);"></i>
                            <?= esc($document['nama_uploader'] ?? '-') ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="detail-label">Tanggal Upload</div>
                        <div class="detail-value">
                            <i class="bi bi-calendar3 me-1" style="color:var(--dms-primary);"></i>
                            <?= date('d M Y, H:i', strtotime($document['created_at'])) ?> WIB
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Riwayat Versi Dokumen -->
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="font-size:1rem;">
                    <i class="bi bi-clock-history me-2" style="color:var(--dms-primary);"></i>
                    Riwayat Versi Dokumen
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:.9rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Versi</th>
                                <th>Nama File</th>
                                <th>Ukuran</th>
                                <th>Catatan</th>
                                <th>Diunggah Oleh</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($versions)) : ?>
                                <?php foreach ($versions as $index => $v) : ?>
                                    <tr>
                                        <td>
                                            <?php if ($index === 0) : ?>
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Versi Terbaru</span>
                                            <?php else : ?>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-1">Versi <?= $v['nomor_versi'] ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width:200px;" title="<?= esc($v['nama_file_asli']) ?>">
                                                <?= esc($v['nama_file_asli']) ?>
                                            </div>
                                        </td>
                                        <td class="text-nowrap">
                                            <?php
                                                $ukuran = $v['ukuran_file'];
                                                if ($ukuran >= 1048576) {
                                                    echo number_format($ukuran / 1048576, 1) . ' MB';
                                                } elseif ($ukuran >= 1024) {
                                                    echo number_format($ukuran / 1024, 1) . ' KB';
                                                } else {
                                                    echo $ukuran . ' B';
                                                }
                                            ?>
                                        </td>
                                        <td><?= esc($v['catatan'] ?: '-') ?></td>
                                        <td><?= esc($v['nama_uploader'] ?? '-') ?></td>
                                        <td class="text-nowrap"><?= date('d M Y, H:i', strtotime($v['created_at'])) ?></td>
                                        <td class="text-nowrap">
                                            <?php 
                                                $ext = strtolower(pathinfo($v['nama_file_asli'], PATHINFO_EXTENSION));
                                                $previewable = in_array($ext, ['pdf', 'jpg', 'jpeg', 'png']);
                                            ?>
                                            <?php if ($previewable) : ?>
                                                <a href="<?= base_url('document/version/preview/' . $v['id']) ?>" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2" title="Preview">
                                                    <i class="bi bi-eye-fill"></i>
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('document/version/download/' . $v['id']) ?>" class="btn btn-sm btn-outline-secondary py-0 px-2" title="Download">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat versi dokumen.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Aksi Dokumen -->
    <div class="col-lg-4 animate-in">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0 fw-bold" style="font-size:.95rem;">
                    <i class="bi bi-lightning-fill me-2" style="color:var(--dms-warning);"></i>
                    Aksi Cepat
                </h6>
            </div>
            <div class="card-body d-grid gap-2">
                <?php
                    $role = session()->get('user_role');
                    $userId = (int) session()->get('user_id');
                    $canModify = $role === 'admin' || ($role === 'hrd' && (int) ($document['uploaded_by'] ?? 0) === $userId);
                ?>

                <!-- Tombol Preview PDF (hanya tampil jika file PDF) -->
                <?php if (strtolower($document['tipe_file']) === 'pdf') : ?>
                <a href="<?= base_url('document/preview/' . $document['id']) ?>"
                   target="_blank"
                   class="btn btn-dms-primary">
                    <i class="bi bi-eye-fill me-2"></i> Lihat (Preview PDF)
                </a>
                <?php endif; ?>

                <!-- Tombol Preview untuk gambar -->
                <?php if (in_array(strtolower($document['tipe_file']), ['jpg', 'jpeg', 'png'])) : ?>
                <a href="<?= base_url('document/preview/' . $document['id']) ?>"
                   target="_blank"
                   class="btn btn-dms-primary">
                    <i class="bi bi-image me-2"></i> Lihat (Preview Gambar)
                </a>
                <?php endif; ?>

                <!-- Tombol Download: semua role -->
                <a href="<?= base_url('document/download/' . $document['id']) ?>"
                   class="btn btn-outline-primary" style="border-radius:10px;">
                    <i class="bi bi-download me-2"></i> Download File
                </a>

                <?php if ($canModify) : ?>
                <!-- Tombol Edit / Revisi -->
                <a href="<?= base_url('document/edit/' . $document['id']) ?>"
                   class="btn btn-outline-warning" style="border-radius:10px;">
                    <i class="bi bi-pencil-square me-2"></i> Edit / Revisi
                </a>
                <?php endif; ?>

                <!-- Tombol Kembali -->
                <a href="<?= base_url('document') ?>"
                   class="btn btn-outline-secondary" style="border-radius:10px;">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Daftar
                </a>

                <?php if ($canModify) : ?>
                <hr>

                <!-- Tombol Hapus (dengan konfirmasi) -->
                <button type="button" class="btn btn-outline-danger w-100" style="border-radius:10px;"
                        data-bs-toggle="modal" data-bs-target="#deleteDocumentModal"
                        data-document-title="<?= esc($document['judul']) ?>"
                        data-delete-url="<?= base_url('document/delete/' . $document['id']) ?>">
                    <i class="bi bi-trash3-fill me-2"></i> Hapus Dokumen
                </button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Card Info Terakhir Update -->
        <div class="card mt-3">
            <div class="card-body" style="padding:18px 22px;">
                <div class="d-flex align-items-center gap-3">
                    <div style="width:40px; height:40px; background:rgba(67,97,238,.1); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="bi bi-clock-history" style="color:var(--dms-primary); font-size:1.1rem;"></i>
                    </div>
                    <div>
                        <div style="font-size:.75rem; color:var(--dms-text-muted); font-weight:600; text-transform:uppercase; letter-spacing:.5px;">
                            Terakhir Diperbarui
                        </div>
                        <div style="font-size:.9rem; font-weight:500;">
                            <?= date('d M Y, H:i', strtotime($document['updated_at'])) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
