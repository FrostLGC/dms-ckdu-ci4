<!-- ============================================================
     HALAMAN: Daftar Instansi (Mitra Kerja)
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Toolbar: Judul + Tombol Tambah -->
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--dms-dark);">
            <i class="bi bi-building me-2" style="color: var(--dms-primary);"></i>
            Data Instansi
        </h4>
        <p class="text-muted mb-0" style="font-size: .85rem;">
            Kelola data instansi / mitra kerja
        </p>
    </div>
    <a href="<?= base_url('instansi/create') ?>" class="btn btn-dms-primary">
        <i class="bi bi-plus-lg me-2"></i> Tambah Instansi
    </a>
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
                                <span class="fw-semibold" style="color: var(--dms-dark);">
                                    <?= esc($item['nama_instansi']) ?>
                                </span>
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
                            <div class="d-flex gap-1">
                                <a href="<?= base_url('instansi/edit/' . $item['id']) ?>"
                                   class="btn btn-sm btn-outline-warning" title="Edit"
                                   style="border-radius:8px;">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
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
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-transparent text-muted" style="font-size:.8rem; padding:12px 22px;">
        Menampilkan <?= count($instansi) ?> instansi
    </div>
</div>

<?php else : ?>
<div class="card animate-in">
    <div class="card-body empty-state">
        <div class="empty-icon">🏢</div>
        <h5>Belum ada data instansi</h5>
        <p class="text-muted mb-3">Mulai dengan menambahkan instansi pertama</p>
        <a href="<?= base_url('instansi/create') ?>" class="btn btn-dms-primary">
            <i class="bi bi-plus-lg me-2"></i> Tambah Instansi
        </a>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
