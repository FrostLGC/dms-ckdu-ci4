<!-- ============================================================
     HALAMAN: Dashboard
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Greeting -->
<div class="mb-4 animate-in">
    <h4 class="fw-bold" style="color: var(--dms-dark);">
        Selamat Datang di DMS
    </h4>
    <p class="text-muted mb-0" style="font-size: .9rem;">
        Ringkasan data dokumen PT. Cipta Karya Dharma Utama
    </p>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3 animate-in">
        <div class="stat-card stat-gradient-1">
            <div class="stat-icon"><i class="bi bi-file-earmark-text"></i></div>
            <div class="stat-number"><?= $totalDokumen ?? 0 ?></div>
            <div class="stat-label">Total Dokumen</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 animate-in">
        <div class="stat-card stat-gradient-2">
            <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
            <div class="stat-number"><?= $totalAktif ?? 0 ?></div>
            <div class="stat-label">Dokumen Aktif</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 animate-in">
        <div class="stat-card stat-gradient-3">
            <div class="stat-icon"><i class="bi bi-archive"></i></div>
            <div class="stat-number"><?= $totalArsip ?? 0 ?></div>
            <div class="stat-label">Dokumen Arsip</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3 animate-in">
        <div class="stat-card stat-gradient-4">
            <div class="stat-icon"><i class="bi bi-folder2-open"></i></div>
            <div class="stat-number"><?= $totalKategori ?? 0 ?></div>
            <div class="stat-label">Kategori</div>
        </div>
    </div>
</div>

<!-- ============================================================
     BARIS BAWAH: Dokumen Terbaru (kiri) + Aktivitas Terbaru (kanan)
     Menggunakan grid Bootstrap col-lg-7 dan col-lg-5 agar proporsional
     ============================================================ -->
<div class="row g-4">
    <!-- KOLOM KIRI: Dokumen Terbaru -->
    <div class="col-lg-7 animate-in">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-clock-history me-2" style="color:var(--dms-primary);"></i>
                    Dokumen Terbaru
                </h6>
                <a href="<?= base_url('document') ?>" class="btn btn-sm btn-outline-primary" style="border-radius:8px; font-size:.8rem;">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($recentDocs)) : ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentDocs as $doc) : ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url('document/detail/' . $doc['id']) ?>"
                                       class="text-decoration-none fw-semibold" style="color:var(--dms-primary);">
                                        <?= esc($doc['judul']) ?>
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border" style="font-weight:500;">
                                        <?= esc($doc['nama_kategori'] ?? '-') ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="file-type-badge file-type-<?= strtolower($doc['tipe_file']) ?>">
                                        <?= strtoupper(esc($doc['tipe_file'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-<?= $doc['status'] ?>">
                                        <?= $doc['status'] == 'aktif' ? '● Aktif' : '● Arsip' ?>
                                    </span>
                                </td>
                                <td class="text-muted" style="font-size:.82rem;">
                                    <?= date('d M Y', strtotime($doc['created_at'])) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else : ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-inbox" style="font-size:2.5rem; opacity:.3;"></i>
                    <p class="mt-2 mb-0">Belum ada dokumen</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: Aktivitas Terbaru (Audit Log) -->
    <div class="col-lg-5 animate-in">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-activity me-2" style="color:var(--dms-warning);"></i>
                    Aktivitas Terbaru
                </h6>
                <a href="<?= base_url('auditlog') ?>" class="btn btn-sm btn-outline-warning" style="border-radius:8px; font-size:.8rem;">
                    Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($recentLogs)) : ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($recentLogs as $log) : ?>
                    <div class="list-group-item border-0" style="padding:14px 20px;">
                        <div class="d-flex align-items-start gap-3">
                            <!-- Ikon aksi -->
                            <?php
                                // Tentukan ikon dan warna berdasarkan jenis aksi
                                $ikonAksi  = 'bi-cloud-arrow-up-fill';
                                $warnaAksi = 'var(--dms-primary)';
                                $bgAksi    = 'rgba(99, 102, 241, 0.1)';

                                if ($log['aksi'] === 'Hapus') {
                                    $ikonAksi  = 'bi-trash3-fill';
                                    $warnaAksi = 'var(--dms-danger)';
                                    $bgAksi    = 'rgba(239, 68, 68, 0.1)';
                                } elseif ($log['aksi'] === 'Edit') {
                                    $ikonAksi  = 'bi-pencil-fill';
                                    $warnaAksi = 'var(--dms-warning)';
                                    $bgAksi    = 'rgba(245, 158, 11, 0.1)';
                                }
                            ?>
                            <div style="width:36px; height:36px; border-radius:10px; background:<?= $bgAksi ?>; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i class="bi <?= $ikonAksi ?>" style="color:<?= $warnaAksi ?>; font-size:.9rem;"></i>
                            </div>
                            <div style="min-width:0;">
                                <div class="fw-semibold" style="font-size:.85rem; color:var(--dms-dark);">
                                    <?= esc($log['aksi']) ?>
                                    <?php if (!empty($log['document_name'])) : ?>
                                        "<?= esc($log['document_name']) ?>"
                                    <?php endif; ?>
                                </div>
                                <div class="text-muted" style="font-size:.78rem;">
                                    <i class="bi bi-person me-1"></i> <?= esc($log['nama_user'] ?? 'Unknown') ?>
                                    <span class="mx-1">·</span>
                                    <i class="bi bi-clock me-1"></i> <?= date('d M Y, H:i', strtotime($log['created_at'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else : ?>
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-clock-history" style="font-size:2.5rem; opacity:.3;"></i>
                    <p class="mt-2 mb-0">Belum ada aktivitas tercatat</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
