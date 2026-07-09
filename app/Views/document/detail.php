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
                <form action="<?= base_url('document/delete/' . $document['id']) ?>"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus dokumen ini? Aksi ini tidak bisa dibatalkan.')">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-outline-danger w-100" style="border-radius:10px;">
                        <i class="bi bi-trash3-fill me-2"></i> Hapus Dokumen
                    </button>
                </form>
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
</div>

<?= $this->endSection() ?>
