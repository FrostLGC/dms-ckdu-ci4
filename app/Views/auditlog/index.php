<!-- ============================================================
     HALAMAN: Audit Log (Riwayat Aktivitas Lengkap) - Iterasi 17
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Toolbar: Judul + Deskripsi -->
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--dms-dark);">
            <i class="bi bi-clock-history me-2" style="color: var(--dms-primary);"></i>
            Audit Log
        </h4>
        <p class="text-muted mb-0" style="font-size: .85rem;">
            Riwayat seluruh aktivitas di Sistem Manajemen Dokumen
        </p>
    </div>
</div>

<!-- Tombol Filter Cepat Periode -->
<?php
    $today      = date('Y-m-d');
    $today7     = date('Y-m-d', strtotime('-6 days'));
    $today30    = date('Y-m-d', strtotime('-29 days'));
    $sd         = $filters['start_date'] ?? '';
    $ed         = $filters['end_date']   ?? '';
    $kw         = $filters['keyword']    ?? '';
    $act        = $filters['action']     ?? '';
    $uid        = $filters['user_id']    ?? '';

    // Deteksi tombol aktif berdasarkan nilai filter saat ini
    $isToday  = ($sd === $today && $ed === $today);
    $is7days  = ($sd === $today7 && $ed === $today);
    $is30days = ($sd === $today30 && $ed === $today);
    $isAll    = (empty($sd) && empty($ed));

    // Helper: bangun URL filter cepat (tetap bawa keyword/action/user_id)
    $baseQs = http_build_query(array_filter(['keyword' => $kw, 'action' => $act, 'user_id' => $uid], fn($v) => $v !== ''));
    $baseQs = $baseQs ? '&' . $baseQs : '';
?>
<div class="mb-3 d-flex flex-wrap gap-2 animate-in">
    <a href="<?= base_url('auditlog') ?>?quick=today<?= $baseQs ?>"
       class="btn btn-sm <?= $isToday ? 'btn-dms-primary' : 'btn-outline-secondary' ?>"
       style="border-radius:20px; font-size:.82rem; display:inline-flex; align-items:center; justify-content:center; gap:0.35rem; padding:0.375rem 1rem; line-height:1.5;">
        <i class="bi bi-calendar-day"></i> Hari Ini
    </a>
    <a href="<?= base_url('auditlog') ?>?quick=7days<?= $baseQs ?>"
       class="btn btn-sm <?= $is7days ? 'btn-dms-primary' : 'btn-outline-secondary' ?>"
       style="border-radius:20px; font-size:.82rem; display:inline-flex; align-items:center; justify-content:center; gap:0.35rem; padding:0.375rem 1rem; line-height:1.5;">
        <i class="bi bi-calendar-week"></i> 7 Hari Terakhir
    </a>
    <a href="<?= base_url('auditlog') ?>?quick=30days<?= $baseQs ?>"
       class="btn btn-sm <?= $is30days ? 'btn-dms-primary' : 'btn-outline-secondary' ?>"
       style="border-radius:20px; font-size:.82rem; display:inline-flex; align-items:center; justify-content:center; gap:0.35rem; padding:0.375rem 1rem; line-height:1.5;">
        <i class="bi bi-calendar-month"></i> 30 Hari Terakhir
    </a>
    <a href="<?= base_url('auditlog') ?>?quick=all<?= $baseQs ?>"
       class="btn btn-sm <?= $isAll ? 'btn-dms-primary' : 'btn-outline-secondary' ?>"
       style="border-radius:20px; font-size:.82rem; display:inline-flex; align-items:center; justify-content:center; gap:0.35rem; padding:0.375rem 1rem; line-height:1.5;">
        <i class="bi bi-archive"></i> Semua Riwayat
    </a>
</div>

<!-- Pesan Error Tanggal -->
<?php if (!empty($dateError)) : ?>
<div class="alert alert-warning alert-sm py-2 mb-3 animate-in" style="border-radius:10px; font-size:.85rem;">
    <i class="bi bi-exclamation-triangle-fill me-1"></i>
    <?= esc($dateError) ?> - rentang tanggal telah dibalik secara otomatis.
</div>
<?php endif; ?>

<!-- Form Filter Audit Log -->
<div class="card mb-4 animate-in">
    <div class="card-body p-3">
        <form action="<?= base_url('auditlog') ?>" method="get">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-6 col-xl-2">
                    <label class="form-label text-muted" style="font-size: .85rem;">Pencarian</label>
                    <input type="text" class="form-control form-control-sm" name="keyword"
                           placeholder="Cari aktivitas, dokumen, pengguna..."
                           value="<?= esc($filters['keyword'] ?? '') ?>">
                </div>
                <div class="col-12 col-md-6 col-xl-2">
                    <label class="form-label text-muted" style="font-size: .85rem;">Jenis Aksi</label>
                    <select name="action" class="form-select form-select-sm">
                        <option value="">Semua Aksi</option>
                        <option value="Login"          <?= ($filters['action'] ?? '') === 'Login'          ? 'selected' : '' ?>>Login</option>
                        <option value="Logout"         <?= ($filters['action'] ?? '') === 'Logout'         ? 'selected' : '' ?>>Logout</option>
                        <option value="Upload"         <?= ($filters['action'] ?? '') === 'Upload'         ? 'selected' : '' ?>>Upload</option>
                        <option value="Edit"           <?= ($filters['action'] ?? '') === 'Edit'           ? 'selected' : '' ?>>Edit</option>
                        <option value="Revisi"         <?= ($filters['action'] ?? '') === 'Revisi'         ? 'selected' : '' ?>>Revisi</option>
                        <option value="Hapus"          <?= ($filters['action'] ?? '') === 'Hapus'          ? 'selected' : '' ?>>Hapus</option>
                        <option value="Preview"        <?= ($filters['action'] ?? '') === 'Preview'        ? 'selected' : '' ?>>Preview</option>
                        <option value="Download"       <?= ($filters['action'] ?? '') === 'Download'       ? 'selected' : '' ?>>Download</option>
                        <option value="Cetak Laporan"  <?= ($filters['action'] ?? '') === 'Cetak Laporan'  ? 'selected' : '' ?>>Cetak Laporan</option>
                        <option value="Download Paket" <?= ($filters['action'] ?? '') === 'Download Paket' ? 'selected' : '' ?>>Download Paket</option>
                        <option value="Akses Ditolak"  <?= ($filters['action'] ?? '') === 'Akses Ditolak'  ? 'selected' : '' ?>>Akses Ditolak</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-xl-2">
                    <label class="form-label text-muted" style="font-size: .85rem;">Pengguna</label>
                    <select name="user_id" class="form-select form-select-sm">
                        <option value="">Semua Pengguna</option>
                        <?php if (isset($users)) : foreach ($users as $user) : ?>
                        <option value="<?= esc($user['id']) ?>" <?= ($filters['user_id'] ?? '') == $user['id'] ? 'selected' : '' ?>>
                            <?= esc($user['nama']) ?>
                        </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-xl-2">
                    <label class="form-label text-muted" style="font-size: .85rem;">Dari Tanggal</label>
                    <input type="date" class="form-control form-control-sm" name="start_date"
                           value="<?= esc($filters['start_date'] ?? '') ?>">
                </div>
                <div class="col-12 col-md-6 col-xl-2">
                    <label class="form-label text-muted" style="font-size: .85rem;">Sampai Tanggal</label>
                    <input type="date" class="form-control form-control-sm" name="end_date"
                           value="<?= esc($filters['end_date'] ?? '') ?>">
                </div>
                <div class="col-12 col-md-6 col-xl-2 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <a href="<?= base_url('auditlog') ?>" class="btn btn-light border text-secondary d-flex align-items-center justify-content-center gap-1 flex-fill" style="border-radius:10px;">
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

<!-- Tabel Audit Log -->
<?php if (!empty($logs)) : ?>
<?php
    $from  = $offset + 1;
    $to    = min($offset + $perPage, $totalLogs);
?>
<div class="card animate-in">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;"></th>
                        <th style="width:110px;">Aksi</th>
                        <th>Objek</th>
                        <th>Keterangan</th>
                        <th>Pengguna</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = $offset + 1; foreach ($logs as $log) : ?>
                    <tr>
                        <td class="text-muted"><?= $no++ ?></td>
                        <td>
                            <?php
                                $aksiType   = $log['aksi'];
                                $badgeClass = 'bg-secondary bg-opacity-10 text-secondary';
                                $ikonAksi   = 'bi-activity';

                                switch ($aksiType) {
                                    case 'Login':
                                        $ikonAksi = 'bi-box-arrow-in-right';
                                        $badgeClass = 'bg-success bg-opacity-10 text-success';
                                        break;
                                    case 'Logout':
                                        $ikonAksi = 'bi-box-arrow-right';
                                        $badgeClass = 'bg-secondary bg-opacity-10 text-secondary';
                                        break;
                                    case 'Upload':
                                        $ikonAksi = 'bi-cloud-arrow-up-fill';
                                        $badgeClass = 'bg-primary bg-opacity-10 text-primary';
                                        break;
                                    case 'Edit':
                                        $ikonAksi = 'bi-pencil-fill';
                                        $badgeClass = 'bg-warning bg-opacity-10 text-warning';
                                        break;
                                    case 'Revisi':
                                        $ikonAksi = 'bi-arrow-repeat';
                                        $badgeClass = 'bg-info bg-opacity-10 text-info';
                                        break;
                                    case 'Hapus':
                                        $ikonAksi = 'bi-trash3-fill';
                                        $badgeClass = 'bg-danger bg-opacity-10 text-danger';
                                        break;
                                    case 'Preview':
                                        $ikonAksi = 'bi-eye-fill';
                                        $badgeClass = 'bg-primary bg-opacity-10 text-primary';
                                        break;
                                    case 'Download':
                                        $ikonAksi = 'bi-download';
                                        $badgeClass = 'bg-success bg-opacity-10 text-success';
                                        break;
                                    case 'Cetak Laporan':
                                        $ikonAksi = 'bi-printer-fill';
                                        $badgeClass = 'badge-cetak-laporan';
                                        break;
                                    case 'Download Paket':
                                        $ikonAksi = 'bi-file-earmark-zip-fill';
                                        $badgeClass = 'bg-success bg-opacity-10 text-success';
                                        break;
                                    case 'Akses Ditolak':
                                        $ikonAksi = 'bi-shield-fill-exclamation';
                                        $badgeClass = 'bg-danger bg-opacity-10 text-danger';
                                        break;
                                }
                            ?>
                            <span class="badge <?= $badgeClass ?>"
                                  style="font-size:.78rem; font-weight:600; padding:5px 12px; border-radius:20px;">
                                <i class="bi <?= $ikonAksi ?> me-1"></i>
                                <?= esc($log['aksi']) ?>
                            </span>
                        </td>
                        <td>
                            <?php
                                $objek = '';
                                $isBadge = false;
                                $objekBadgeClass = '';

                                if (!empty($log['document_name'])) {
                                    $objek = $log['document_name'];
                                } else {
                                    $isBadge = true;
                                    switch ($log['aksi']) {
                                        case 'Login':
                                        case 'Logout':
                                            $objek = 'Sistem';
                                            $objekBadgeClass = 'bg-secondary bg-opacity-10 text-secondary';
                                            break;
                                        case 'Cetak Laporan':
                                            $objek = 'Laporan Dokumen';
                                            $objekBadgeClass = 'bg-primary bg-opacity-10 text-primary';
                                            break;
                                        case 'Download Paket':
                                            $objek = 'Paket Dokumen';
                                            $objekBadgeClass = 'bg-success bg-opacity-10 text-success';
                                            break;
                                        case 'Akses Ditolak':
                                            $objek = 'Keamanan Sistem';
                                            $objekBadgeClass = 'bg-danger bg-opacity-10 text-danger';
                                            break;
                                        default:
                                            $objek = 'Sistem';
                                            $objekBadgeClass = 'bg-secondary bg-opacity-10 text-secondary';
                                            break;
                                    }
                                }
                            ?>
                            <?php if ($isBadge) : ?>
                                <span class="badge <?= $objekBadgeClass ?>" style="font-size:.78rem; font-weight:600; padding:5px 12px; border-radius:20px;">
                                    <?= esc($objek) ?>
                                </span>
                            <?php else : ?>
                                <span class="fw-semibold" style="color: var(--dms-dark);">
                                    <?= esc($objek) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted" style="font-size:.85rem;">
                            <?php
                                $safeKet = esc($log['keterangan'] ?? '-');
                                $safeKet = str_replace("\nCatatan:", "\n<span class=\"fw-semibold text-dark\">Catatan:</span>", $safeKet);
                                echo nl2br($safeKet);
                            ?>
                        </td>
                        <td style="font-size:.85rem;">
                            <i class="bi bi-person-fill me-1" style="color:var(--dms-primary); opacity:.5;"></i>
                            <?= esc($log['nama_user'] ?? 'Unknown') ?>
                        </td>
                        <td class="text-muted" style="font-size:.82rem;">
                            <?= date('d M Y', strtotime($log['created_at'])) ?>
                            <br>
                            <small style="opacity:.7;">
                                <i class="bi bi-clock me-1"></i>
                                <?= date('H:i:s', strtotime($log['created_at'])) ?>
                            </small>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Footer tabel: info jumlah + pagination -->
    <div class="card-footer bg-transparent" style="padding:12px 22px;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <!-- Info jumlah -->
            <span class="text-muted" style="font-size:.8rem;">
                Menampilkan <strong><?= $from ?>–<?= $to ?></strong> dari
                <strong><?= $totalLogs ?></strong> aktivitas
            </span>

            <!-- Navigasi Pagination -->
            <?php if ($totalPages > 1) : ?>
            <?php
                $pqs = $queryParams; // parameter filter tanpa 'page'
                $buildPageUrl = function(int $p) use ($pqs): string {
                    $q = array_merge($pqs, ['page' => $p]);
                    return base_url('auditlog') . '?' . http_build_query($q);
                };
            ?>
            <nav aria-label="Navigasi Halaman Audit Log">
                <ul class="pagination pagination-sm mb-0">
                    <!-- Prev -->
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $buildPageUrl($currentPage - 1) ?>" style="border-radius:8px 0 0 8px;">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>

                    <?php
                        // Tampilkan maks 5 halaman di sekitar halaman aktif
                        $startP = max(1, $currentPage - 2);
                        $endP   = min($totalPages, $currentPage + 2);
                        if ($startP > 1) : ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= $buildPageUrl(1) ?>">1</a>
                    </li>
                    <?php if ($startP > 2) : ?>
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                    <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($p = $startP; $p <= $endP; $p++) : ?>
                    <li class="page-item <?= $p === $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="<?= $buildPageUrl($p) ?>"><?= $p ?></a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($endP < $totalPages) : ?>
                    <?php if ($endP < $totalPages - 1) : ?>
                    <li class="page-item disabled"><span class="page-link">…</span></li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="<?= $buildPageUrl($totalPages) ?>"><?= $totalPages ?></a>
                    </li>
                    <?php endif; ?>

                    <!-- Next -->
                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="<?= $buildPageUrl($currentPage + 1) ?>" style="border-radius:0 8px 8px 0;">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php else : ?>
<!-- Empty State -->
<div class="card animate-in">
    <div class="card-body empty-state">
        <div class="empty-icon"><i class="bi bi-calendar"></i></div>
        <?php if (!empty($filters['start_date']) && $filters['start_date'] === date('Y-m-d') && $filters['end_date'] === date('Y-m-d')) : ?>
        <h5>Tidak ada aktivitas hari ini.</h5>
        <p class="text-muted mb-3">Belum ada yang tercatat pada tanggal <?= date('d M Y') ?>.</p>
        <?php else : ?>
        <h5>Tidak ada aktivitas yang sesuai dengan filter.</h5>
        <p class="text-muted mb-3">Silakan sesuaikan kriteria pencarian Anda.</p>
        <?php endif; ?>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
            <a href="<?= base_url('auditlog') ?>?quick=all" class="btn btn-outline-primary btn-sm" style="border-radius:20px;">
                <i class="bi bi-archive me-1"></i> Tampilkan Semua Riwayat
            </a>
            <a href="<?= base_url('auditlog') ?>" class="btn btn-light border btn-sm" style="border-radius:20px;">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filter
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<style>
.badge-cetak-laporan {
    background-color: rgba(139, 92, 246, 0.1) !important;
    color: #8b5cf6 !important;
}
</style>

<?= $this->endSection() ?>
