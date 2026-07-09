<!-- ============================================================
     HALAMAN: Daftar Kategori (Manajemen Kategori)
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Toolbar: Judul + Tombol Tambah -->
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--dms-dark);">
            <i class="bi bi-folder2-open me-2" style="color: var(--dms-primary);"></i>
            Manajemen Kategori
        </h4>
        <p class="text-muted mb-0" style="font-size: .85rem;">
            Kelola kategori dokumen perusahaan
        </p>
    </div>
    <?php if (in_array(session()->get('user_role'), ['admin', 'hrd'])) : ?>
    <a href="<?= base_url('category/create') ?>" class="btn btn-dms-primary">
        <i class="bi bi-plus-lg me-2"></i> Tambah Kategori
    </a>
    <?php endif; ?>
</div>

<!-- Tabel Kategori -->
<?php if (!empty($categories)) : ?>
<div class="card animate-in">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th style="width:130px;">Jml. Dokumen</th>
                        <th>Dibuat</th>
                        <th style="width:150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($categories as $cat) : ?>
                    <tr>
                        <td class="text-muted"><?= $no++ ?></td>
                        <td>
                            <!--
                                Nama kategori dijadikan tautan ke halaman Dokumen
                                dengan filter category_id, sehingga klik langsung
                                menampilkan dokumen-dokumen di kategori ini.
                            -->
                            <a href="<?= base_url('document?category_id=' . $cat['id']) ?>"
                               class="text-decoration-none fw-semibold" style="color: var(--dms-dark);"
                               title="Lihat dokumen dalam kategori ini">
                                <i class="bi bi-folder-fill me-1" style="color: var(--dms-primary); opacity:.6;"></i>
                                <?= esc($cat['nama_kategori']) ?>
                                <i class="bi bi-box-arrow-up-right ms-1" style="font-size:.7rem; opacity:.4;"></i>
                            </a>
                        </td>
                        <td class="text-muted" style="font-size:.85rem;">
                            <?= esc($cat['deskripsi'] ?: '—') ?>
                        </td>
                        <td>
                            <!--
                                Badge jumlah dokumen:
                                - Jika > 0, tampilkan angka dengan badge biru
                                - Jika 0, tampilkan "Kosong" dengan badge abu-abu
                            -->
                            <?php if ($cat['jumlah_dokumen'] > 0) : ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.8rem; font-weight:600; padding:5px 12px; border-radius:20px;">
                                    <?= $cat['jumlah_dokumen'] ?> dokumen
                                </span>
                            <?php else : ?>
                                <span class="badge bg-light text-muted border" style="font-size:.8rem; font-weight:500; padding:5px 12px; border-radius:20px;">
                                    Kosong
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted" style="font-size:.82rem;">
                            <?= date('d M Y', strtotime($cat['created_at'])) ?>
                        </td>
                        <td>
                            <?php if (in_array(session()->get('user_role'), ['admin', 'hrd'])) : ?>
                            <div class="d-flex gap-1">
                                <!-- Tombol Edit -->
                                <a href="<?= base_url('category/edit/' . $cat['id']) ?>"
                                   class="btn btn-sm btn-outline-warning" title="Edit"
                                   style="border-radius:8px;">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <!-- Tombol Hapus -->
                                <form action="<?= base_url('category/delete/' . $cat['id']) ?>"
                                      method="POST" style="display:inline;"
                                      onsubmit="return confirm('Yakin ingin menghapus kategori &quot;<?= esc($cat['nama_kategori']) ?>&quot;?')">
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
    <!-- Footer tabel -->
    <div class="card-footer bg-transparent text-muted" style="font-size:.8rem; padding:12px 22px;">
        Menampilkan <?= count($categories) ?> kategori
    </div>
</div>

<?php else : ?>
<!-- Empty State -->
<div class="card animate-in">
    <div class="card-body empty-state">
        <div class="empty-icon">📂</div>
        <h5>Belum ada kategori</h5>
        <p class="text-muted mb-3">Mulai dengan menambahkan kategori dokumen pertama</p>
        <?php if (in_array(session()->get('user_role'), ['admin', 'hrd'])) : ?>
        <a href="<?= base_url('category/create') ?>" class="btn btn-dms-primary">
            <i class="bi bi-plus-lg me-2"></i> Tambah Kategori
        </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
