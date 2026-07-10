<!-- ============================================================
     HALAMAN: Daftar Instansi (Mitra Kerja)
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php $role = session()->get('user_role'); ?>

<!-- Toolbar: Judul + Tombol Tambah -->
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--dms-dark);">
            <i class="bi bi-building me-2" style="color: var(--dms-primary);"></i>
            Data Instansi / Mitra Kerja
        </h4>
        <p class="text-muted mb-0" style="font-size: .85rem;">
            <?php if (in_array($role, ['admin', 'hrd'])) : ?>
                Kelola data instansi dan klik nama untuk melihat dokumen terkait.
            <?php else : ?>
                Klik nama instansi untuk melihat dokumen berdasarkan mitra kerja.
            <?php endif; ?>
        </p>
    </div>
    <?php if (in_array($role, ['admin', 'hrd'])) : ?>
    <a href="<?= base_url('instansi/create') ?>" class="btn btn-dms-primary">
        <i class="bi bi-plus-lg me-2"></i> Tambah Instansi
    </a>
    <?php endif; ?>
</div>

<!-- Tabel Instansi -->
<?php if (!empty($instansi)) : ?>
<div class="card animate-in">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Nama Instansi</th>
                        <th>Alamat</th>
                        <th>No. Telepon</th>
                        <th>Dibuat</th>
                        <th style="width:150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($instansi as $item) : ?>
                    <tr>
                        <td class="text-muted"><?= $no++ ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:34px; height:34px; border-radius:10px; background:linear-gradient(135deg, #667eea, #764ba2); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:.8rem; flex-shrink:0;">
                                    <?= strtoupper(substr($item['nama_instansi'], 0, 1)) ?>
                                </div>
                                <!--
                                    Nama instansi dijadikan tautan ke halaman Dokumen
                                    dengan filter instansi_id, sehingga klik langsung
                                    menampilkan dokumen dari instansi ini.
                                -->
                                <a href="<?= base_url('document?instansi_id=' . $item['id']) ?>"
                                   class="text-decoration-none fw-semibold" style="color: var(--dms-dark);"
                                   title="Lihat dokumen dari instansi ini">
                                    <?= esc($item['nama_instansi']) ?>
                                    <i class="bi bi-box-arrow-up-right ms-1" style="font-size:.7rem; opacity:.4;"></i>
                                </a>
                            </div>
                        </td>
                        <td class="text-muted" style="font-size:.85rem;">
                            <?= esc($item['alamat'] ?: '—') ?>
                        </td>
                        <td class="text-muted" style="font-size:.85rem;">
                            <?php if (!empty($item['no_telp'])) : ?>
                                <i class="bi bi-telephone me-1" style="opacity:.5;"></i>
                                <?= esc($item['no_telp']) ?>
                            <?php else : ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td class="text-muted" style="font-size:.82rem;">
                            <?= date('d M Y', strtotime($item['created_at'])) ?>
                        </td>
                        <td>
                            <?php if (in_array($role, ['admin', 'hrd'])) : ?>
                            <div class="d-flex gap-1">
                                <!-- Tombol Edit -->
                                <a href="<?= base_url('instansi/edit/' . $item['id']) ?>"
                                   class="btn btn-sm btn-outline-warning" title="Edit"
                                   style="border-radius:8px;">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <!-- Tombol Hapus -->
                                <form action="<?= base_url('instansi/delete/' . $item['id']) ?>"
                                      method="POST" style="display:inline;"
                                      onsubmit="return confirm('Yakin ingin menghapus instansi &quot;<?= esc($item['nama_instansi']) ?>&quot;?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            title="Hapus" style="border-radius:8px;">
                                        <i class="bi bi-trash3-fill"></i>
                                    </button>
                                </form>
                            </div>
                            <?php else : ?>
                                <span class="text-muted">&mdash;</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent text-muted" style="font-size:.8rem; padding:12px 22px;">
        Menampilkan <?= count($instansi) ?> instansi &nbsp;•&nbsp;
        <i class="bi bi-info-circle me-1"></i>
        Klik nama instansi untuk melihat dokumen terkait
    </div>
</div>

<?php else : ?>
<div class="card animate-in">
    <div class="card-body empty-state">
        <div class="empty-icon">🏢</div>
        <h5>Belum ada data instansi</h5>
        <?php if (in_array($role, ['admin', 'hrd'])) : ?>
        <p class="text-muted mb-3">Mulai dengan menambahkan instansi pertama</p>
        <a href="<?= base_url('instansi/create') ?>" class="btn btn-dms-primary">
            <i class="bi bi-plus-lg me-2"></i> Tambah Instansi
        </a>
        <?php else : ?>
        <p class="text-muted mb-3">Belum ada data instansi yang tersedia.</p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
