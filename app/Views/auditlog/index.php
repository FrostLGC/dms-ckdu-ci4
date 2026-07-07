<!-- ============================================================
     HALAMAN: Audit Log (Riwayat Aktivitas Lengkap)
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

<!-- Tabel Audit Log -->
<?php if (!empty($logs)) : ?>
<div class="card animate-in">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th style="width:110px;">Aksi</th>
                        <th>Nama Dokumen</th>
                        <th>Keterangan</th>
                        <th>Pengguna</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($logs as $log) : ?>
                    <tr>
                        <td class="text-muted"><?= $no++ ?></td>
                        <td>
                            <?php
                                // Tentukan badge dan warna berdasarkan jenis aksi
                                $badgeClass = 'bg-primary bg-opacity-10 text-primary';
                                $ikonAksi   = 'bi-cloud-arrow-up-fill';

                                if ($log['aksi'] === 'Hapus') {
                                    $badgeClass = 'bg-danger bg-opacity-10 text-danger';
                                    $ikonAksi   = 'bi-trash3-fill';
                                } elseif ($log['aksi'] === 'Edit') {
                                    $badgeClass = 'bg-warning bg-opacity-10 text-warning';
                                    $ikonAksi   = 'bi-pencil-fill';
                                }
                            ?>
                            <span class="badge <?= $badgeClass ?>"
                                  style="font-size:.78rem; font-weight:600; padding:5px 12px; border-radius:20px;">
                                <i class="bi <?= $ikonAksi ?> me-1"></i>
                                <?= esc($log['aksi']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="fw-semibold" style="color: var(--dms-dark);">
                                <?= esc($log['document_name']) ?>
                            </span>
                        </td>
                        <td class="text-muted" style="font-size:.85rem;">
                            <?= esc($log['keterangan'] ?? '—') ?>
                        </td>
                        <td style="font-size:.85rem;">
                            <i class="bi bi-person-fill me-1" style="color:var(--dms-primary); opacity:.5;"></i>
                            <?= esc($log['nama_user'] ?? 'Unknown') ?>
                        </td>
                        <td class="text-muted" style="font-size:.82rem;">
                            <!-- Tampilkan tanggal dan jam -->
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
    <!-- Footer tabel -->
    <div class="card-footer bg-transparent text-muted" style="font-size:.8rem; padding:12px 22px;">
        Menampilkan <?= count($logs) ?> catatan aktivitas
    </div>
</div>

<?php else : ?>
<!-- Empty State -->
<div class="card animate-in">
    <div class="card-body empty-state">
        <div class="empty-icon">📋</div>
        <h5>Belum ada aktivitas tercatat</h5>
        <p class="text-muted mb-3">Log aktivitas akan muncul setelah Anda mulai mengupload atau menghapus dokumen</p>
        <a href="<?= base_url('document/create') ?>" class="btn btn-dms-primary">
            <i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload Dokumen Pertama
        </a>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
