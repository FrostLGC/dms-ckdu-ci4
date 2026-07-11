<!-- ============================================================
     HALAMAN: Dashboard
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
.stat-card-wrap {
    display: block;
    text-decoration: none;
}
.stat-card-wrap .stat-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.stat-card-wrap:hover .stat-card {
    transform: translateY(-3px);
    box-shadow: 0 8px 12px rgba(0,0,0,0.1);
}
</style>

<!-- Greeting -->
<div class="mb-4 animate-in">
    <h4 class="fw-bold" style="color: var(--dms-dark);">
        Selamat Datang di DMS
    </h4>
    <p class="text-muted mb-0" style="font-size: .9rem;">
        Ringkasan data dokumen PT. Cipta Karya Dharma Utama
    </p>
</div>

<!-- Navigation Tabs -->
<ul class="nav nav-tabs mb-4 animate-in" id="dashboardTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= ($activeTab !== 'monitoring') ? 'active' : '' ?> fw-bold" id="ringkasan-tab" data-bs-toggle="tab" data-bs-target="#ringkasan" type="button" role="tab" aria-controls="ringkasan" aria-selected="<?= ($activeTab !== 'monitoring') ? 'true' : 'false' ?>">
            <i class="bi bi-grid me-1"></i> Ringkasan
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link <?= ($activeTab === 'monitoring') ? 'active' : '' ?> fw-bold" id="monitoring-tab" data-bs-toggle="tab" data-bs-target="#monitoring" type="button" role="tab" aria-controls="monitoring" aria-selected="<?= ($activeTab === 'monitoring') ? 'true' : 'false' ?>">
            <i class="bi bi-graph-up me-1"></i> Monitoring
        </button>
    </li>
</ul>

<div class="tab-content" id="dashboardTabContent">
    <!-- ============================================================
         TAB RINGKASAN
         ============================================================ -->
    <div class="tab-pane fade <?= ($activeTab !== 'monitoring') ? 'show active' : '' ?>" id="ringkasan" role="tabpanel" aria-labelledby="ringkasan-tab">
        
        <!-- Stat Cards -->
<div class="row g-3 mb-3">
    <div class="col-sm-6 col-xl-3 animate-in">
        <a href="<?= base_url('document') ?>" class="stat-card-wrap" title="Lihat Semua Dokumen">
            <div class="stat-card stat-gradient-1">
                <div class="stat-icon"><i class="bi bi-file-earmark-text"></i></div>
                <div class="stat-number"><?= $totalDokumen ?? 0 ?></div>
                <div class="stat-label">Total Dokumen</div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3 animate-in">
        <a href="<?= base_url('document?status=aktif') ?>" class="stat-card-wrap" title="Lihat Dokumen Aktif">
            <div class="stat-card stat-gradient-2">
                <div class="stat-icon"><i class="bi bi-check-circle"></i></div>
                <div class="stat-number"><?= $totalAktif ?? 0 ?></div>
                <div class="stat-label">Dokumen Aktif</div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3 animate-in">
        <a href="<?= base_url('document?status=arsip') ?>" class="stat-card-wrap" title="Lihat Dokumen Arsip">
            <div class="stat-card stat-gradient-3">
                <div class="stat-icon"><i class="bi bi-archive"></i></div>
                <div class="stat-number"><?= $totalArsip ?? 0 ?></div>
                <div class="stat-label">Dokumen Arsip</div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3 animate-in">
        <a href="<?= base_url('category') ?>" class="stat-card-wrap" title="Kelola Kategori">
            <div class="stat-card stat-gradient-4">
                <div class="stat-icon"><i class="bi bi-folder2-open"></i></div>
                <div class="stat-number"><?= $totalKategori ?? 0 ?></div>
                <div class="stat-label">Kategori</div>
            </div>
        </a>
    </div>
    </div> <!-- End of Stat Cards Row -->

    <!-- ============================================================
         BARIS BAWAH: Dokumen Terbaru (kiri) + Aktivitas Terbaru (kanan)
         Menggunakan grid Bootstrap col-lg-7 dan col-lg-5 agar proporsional
         ============================================================ -->
    <div class="row g-4 mb-4">
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
                                    $aksiType = $log['aksi'];
                                    $ikonAksi  = 'bi-activity';
                                    $warnaAksi = 'var(--dms-secondary)';
                                    $bgAksi    = 'rgba(107, 114, 128, 0.1)';

                                    switch ($aksiType) {
                                        case 'Upload':
                                            $ikonAksi  = 'bi-cloud-arrow-up-fill';
                                            $warnaAksi = 'var(--dms-primary)';
                                            $bgAksi    = 'rgba(99, 102, 241, 0.1)';
                                            break;
                                        case 'Edit':
                                            $ikonAksi  = 'bi-pencil-fill';
                                            $warnaAksi = 'var(--dms-warning)';
                                            $bgAksi    = 'rgba(245, 158, 11, 0.1)';
                                            break;
                                        case 'Revisi':
                                            $ikonAksi  = 'bi-arrow-repeat';
                                            $warnaAksi = 'var(--dms-info)';
                                            $bgAksi    = 'rgba(6, 182, 212, 0.1)';
                                            break;
                                        case 'Hapus':
                                            $ikonAksi  = 'bi-trash3-fill';
                                            $warnaAksi = 'var(--dms-danger)';
                                            $bgAksi    = 'rgba(239, 68, 68, 0.1)';
                                            break;
                                        case 'Preview':
                                            $ikonAksi  = 'bi-eye-fill';
                                            $warnaAksi = 'var(--dms-primary)';
                                            $bgAksi    = 'rgba(99, 102, 241, 0.1)';
                                            break;
                                        case 'Download':
                                            $ikonAksi  = 'bi-download';
                                            $warnaAksi = 'var(--dms-success)';
                                            $bgAksi    = 'rgba(34, 197, 94, 0.1)';
                                            break;
                                        case 'Cetak Laporan':
                                            $ikonAksi  = 'bi-printer-fill';
                                            $warnaAksi = 'var(--dms-secondary)';
                                            $bgAksi    = 'rgba(107, 114, 128, 0.1)';
                                            break;
                                        case 'Download Paket':
                                            $ikonAksi  = 'bi-file-earmark-zip-fill';
                                            $warnaAksi = 'var(--dms-success)';
                                            $bgAksi    = 'rgba(34, 197, 94, 0.1)';
                                            break;
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
    </div> <!-- End Row Dokumen & Aktivitas -->

    </div> <!-- End Tab Ringkasan -->

    <!-- ============================================================
         TAB MONITORING
         ============================================================ -->
    <div class="tab-pane fade <?= ($activeTab === 'monitoring') ? 'show active' : '' ?>" id="monitoring" role="tabpanel" aria-labelledby="monitoring-tab">
        
        <div class="d-flex justify-content-between align-items-center mb-3 animate-in">
            <h5 class="fw-bold mb-0" style="color: var(--dms-dark);">Monitoring Dokumen</h5>
            <!-- Filter Periode -->
            <form method="get" action="<?= base_url('dashboard') ?>" id="formPeriod">
                <input type="hidden" name="tab" value="monitoring">
                <select name="period" class="form-select form-select-sm" onchange="document.getElementById('formPeriod').submit();" style="min-width:180px; padding-right:2.5rem; border-radius:8px; cursor:pointer;">
                    <option value="7" <?= (isset($period) && $period == 7) ? 'selected' : '' ?>>7 Hari Terakhir</option>
                    <option value="30" <?= (!isset($period) || $period == 30) ? 'selected' : '' ?>>30 Hari Terakhir</option>
                    <option value="90" <?= (isset($period) && $period == 90) ? 'selected' : '' ?>>90 Hari Terakhir</option>
                </select>
            </form>
        </div>

<!-- Ringkasan Aktivitas Periode -->
<div class="card mb-3 animate-in">
    <div class="card-body">
        <h6 class="fw-bold mb-3">Ringkasan Aktivitas (<?= $period ?? 30 ?> Hari Terakhir)</h6>
        <div class="row g-2 text-center">
            <div class="col-6 col-md">
                <div class="p-2 border rounded h-100">
                    <i class="bi bi-cloud-arrow-up-fill text-primary mb-1 d-block fs-5"></i>
                    <div class="fs-5 fw-bold"><?= $activitySummary['Upload'] ?? 0 ?></div>
                    <small class="text-muted" style="font-size: .8rem;">Upload</small>
                </div>
            </div>
            <div class="col-6 col-md">
                <div class="p-2 border rounded h-100">
                    <i class="bi bi-pencil-fill text-warning mb-1 d-block fs-5"></i>
                    <div class="fs-5 fw-bold"><?= $activitySummary['Edit'] ?? 0 ?></div>
                    <small class="text-muted" style="font-size: .8rem;">Edit</small>
                </div>
            </div>
            <div class="col-6 col-md">
                <div class="p-2 border rounded h-100">
                    <i class="bi bi-arrow-repeat text-info mb-1 d-block fs-5"></i>
                    <div class="fs-5 fw-bold"><?= $activitySummary['Revisi'] ?? 0 ?></div>
                    <small class="text-muted" style="font-size: .8rem;">Revisi</small>
                </div>
            </div>
            <div class="col-6 col-md">
                <div class="p-2 border rounded h-100">
                    <i class="bi bi-eye-fill text-primary mb-1 d-block fs-5"></i>
                    <div class="fs-5 fw-bold"><?= $activitySummary['Preview'] ?? 0 ?></div>
                    <small class="text-muted" style="font-size: .8rem;">Preview</small>
                </div>
            </div>
            <div class="col-12 col-md">
                <div class="p-2 border rounded h-100">
                    <i class="bi bi-download text-success mb-1 d-block fs-5"></i>
                    <div class="fs-5 fw-bold"><?= $activitySummary['Download'] ?? 0 ?></div>
                    <small class="text-muted" style="font-size: .8rem;">Download</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grafik Kategori dan Instansi -->
<div class="row g-3 mb-4">
    <!-- Grafik Kategori -->
    <div class="col-lg-6 animate-in">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white pb-0 border-0 pt-3">
                <h6 class="fw-bold mb-0">Dokumen Berdasarkan Kategori</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 250px;">
                <?php if (empty($categoryChart)): ?>
                    <div class="text-center text-muted">
                        <i class="bi bi-bar-chart text-secondary opacity-50" style="font-size:2rem;"></i>
                        <p class="mt-2 mb-0">Belum ada data dokumen untuk ditampilkan.</p>
                    </div>
                <?php else: ?>
                    <div style="width: 100%; max-height: 250px;">
                        <canvas id="categoryChart"></canvas>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Grafik Instansi -->
    <div class="col-lg-6 animate-in">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white pb-0 border-0 pt-3">
                <h6 class="fw-bold mb-0">Dokumen Berdasarkan Instansi</h6>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center" style="min-height: 250px;">
                <?php if (empty($instansiChart)): ?>
                    <div class="text-center text-muted">
                        <i class="bi bi-bar-chart text-secondary opacity-50" style="font-size:2rem;"></i>
                        <p class="mt-2 mb-0">Belum ada data dokumen untuk ditampilkan.</p>
                    </div>
                <?php else: ?>
                    <div style="width: 100%; max-height: 250px;">
                        <canvas id="instansiChart"></canvas>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div> <!-- End Row Chart Kategori & Instansi -->

    </div> <!-- End Tab Monitoring -->
</div> <!-- End Tab Content -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Muat file Chart.js lokal -->
<script src="<?= base_url('assets/js/chart.umd.min.js') ?>"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let catChart = null;
    let instChart = null;

    // 1. Grafik Kategori
    <?php if (!empty($categoryChart)): ?>
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    const catData = <?= json_encode($categoryChart) ?>;
    
    catChart = new Chart(catCtx, {
        type: 'doughnut',
        data: {
            labels: catData.map(item => item.nama_kategori),
            datasets: [{
                data: catData.map(item => item.total_dokumen),
                backgroundColor: [
                    '#6366f1', '#f59e0b', '#10b981', '#ef4444', 
                    '#06b6d4', '#8b5cf6', '#ec4899', '#64748b'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right' }
            }
        }
    });
    <?php endif; ?>

    // 2. Grafik Instansi
    <?php if (!empty($instansiChart)): ?>
    const instCtx = document.getElementById('instansiChart').getContext('2d');
    const instData = <?= json_encode($instansiChart) ?>;
    
    instChart = new Chart(instCtx, {
        type: 'bar',
        data: {
            labels: instData.map(item => item.nama_instansi),
            datasets: [{
                label: 'Jumlah Dokumen',
                data: instData.map(item => item.total_dokumen),
                backgroundColor: [
                    '#6366f1', '#22c55e', '#f59e0b', '#ef4444', 
                    '#06b6d4', '#8b5cf6', '#ec4899', '#64748b'
                ],
                borderColor: [
                    '#6366f1', '#22c55e', '#f59e0b', '#ef4444', 
                    '#06b6d4', '#8b5cf6', '#ec4899', '#64748b'
                ],
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
    <?php endif; ?>

    // Redraw charts when Monitoring tab is shown to fix size issue when initialized in hidden state
    const monitoringTab = document.getElementById('monitoring-tab');
    if (monitoringTab) {
        monitoringTab.addEventListener('shown.bs.tab', function () {
            if (catChart) catChart.resize();
            if (instChart) instChart.resize();
        });
    }
});
</script>
<?= $this->endSection() ?>
