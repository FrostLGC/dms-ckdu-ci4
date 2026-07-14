<!-- ============================================================
     HALAMAN: Daftar Pengguna (Manajemen Pengguna)
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Toolbar: Judul + Tombol Tambah -->
<div class="d-flex justify-content-between align-items-center mb-4 animate-in">
    <div>
        <h4 class="fw-bold mb-1" style="color: var(--dms-dark);">
            <i class="bi bi-people-fill me-2" style="color: var(--dms-primary);"></i>
            Manajemen Pengguna
        </h4>
        <p class="text-muted mb-0" style="font-size: .85rem;">
            Kelola akun pengguna sistem DMS
        </p>
    </div>
    <a href="<?= base_url('user/create') ?>" class="btn btn-dms-primary">
        <i class="bi bi-person-plus-fill me-2"></i> Tambah Pengguna
    </a>
</div>

<!-- Tabel Pengguna -->
<?php if (!empty($users)) : ?>
<div class="card animate-in">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Terdaftar</th>
                        <th style="width:150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($users as $u) : ?>
                    <tr>
                        <td class="text-muted"><?= $no++ ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <!-- Avatar inisial -->
                                <div style="width:34px; height:34px; border-radius:10px; background:linear-gradient(135deg, var(--dms-primary), var(--dms-secondary)); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:.8rem; flex-shrink:0;">
                                    <?= strtoupper(substr($u['nama'], 0, 1)) ?>
                                </div>
                                <span class="fw-semibold" style="color: var(--dms-dark);">
                                    <?= esc($u['nama']) ?>
                                </span>
                            </div>
                        </td>
                        <td class="text-muted" style="font-size:.85rem;">
                            <i class="bi bi-envelope me-1" style="opacity:.5;"></i>
                            <?= esc($u['email']) ?>
                        </td>
                        <td>
                            <?php if ($u['role'] === 'admin') : ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:.78rem; font-weight:600; padding:5px 12px; border-radius:20px;">
                                    <i class="bi bi-shield-lock-fill me-1"></i> Admin
                                </span>
                            <?php elseif ($u['role'] === 'hrd') : ?>
                                <span class="badge bg-success bg-opacity-10 text-success" style="font-size:.78rem; font-weight:600; padding:5px 12px; border-radius:20px;">
                                    <i class="bi bi-person-badge-fill me-1"></i> HRD
                                </span>
                            <?php elseif ($u['role'] === 'pimpinan') : ?>
                                <span class="badge" style="background-color: #e2e8f0; color: #1e293b; font-size:.78rem; font-weight:600; padding:5px 12px; border-radius:20px;">
                                    <i class="bi bi-eye-fill me-1"></i> Pimpinan
                                </span>
                            <?php else : ?>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size:.78rem; font-weight:600; padding:5px 12px; border-radius:20px;">
                                    <i class="bi bi-person-fill me-1"></i> <?= esc(ucfirst($u['role'])) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted" style="font-size:.82rem;">
                            <?= date('d M Y', strtotime($u['created_at'])) ?>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <!-- Tombol Edit -->
                                <a href="<?= base_url('user/edit/' . $u['id']) ?>"
                                   class="btn btn-sm btn-outline-warning" title="Edit"
                                   style="border-radius:8px;">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <!-- Tombol Hapus (tidak bisa hapus diri sendiri) -->
                                <?php if ($u['id'] != session()->get('user_id')) : ?>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-user"
                                        title="Hapus" style="border-radius:8px;"
                                        data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                        data-user-name="<?= esc($u['nama']) ?>"
                                        data-delete-url="<?= base_url('user/delete/' . $u['id']) ?>">
                                    <i class="bi bi-trash3-fill"></i>
                                </button>
                                <?php else : ?>
                                <button class="btn btn-sm btn-outline-secondary" disabled
                                        title="Tidak bisa hapus diri sendiri" style="border-radius:8px;">
                                    <i class="bi bi-lock-fill"></i>
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
    <!-- Footer tabel -->
    <div class="card-footer bg-transparent text-muted" style="font-size:.8rem; padding:12px 22px;">
        Menampilkan <?= count($users) ?> pengguna
    </div>
</div>

<?php else : ?>
<!-- Empty State -->
<div class="card animate-in">
    <div class="card-body empty-state">
        <div class="empty-icon">👤</div>
        <h5>Belum ada pengguna</h5>
        <p class="text-muted mb-3">Mulai dengan menambahkan pengguna pertama</p>
        <a href="<?= base_url('user/create') ?>" class="btn btn-dms-primary">
            <i class="bi bi-person-plus-fill me-2"></i> Tambah Pengguna
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Modal Konfirmasi Hapus Pengguna -->
<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="deleteUserModalLabel" style="color:var(--dms-dark);">Hapus Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-4 pb-4">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:3rem; opacity:0.9;"></i>
                </div>
                <p class="mb-1" style="font-size:1.05rem;">
                    Apakah Anda yakin ingin menghapus pengguna <strong id="deleteUserName"></strong>?
                </p>
                <p class="text-muted" style="font-size:.85rem;">
                    Akun yang sudah dihapus tidak dapat digunakan untuk masuk ke sistem.
                </p>
            </div>
            <div class="modal-footer border-0 pt-0 d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal" style="border-radius:8px;">Batal</button>
                <form id="formDeleteUser" method="POST" style="display:inline;">
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
    const deleteUserModal = document.getElementById('deleteUserModal');
    if (deleteUserModal) {
        deleteUserModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const userName = button.getAttribute('data-user-name');
            const deleteUrl = button.getAttribute('data-delete-url');

            const modalUserName = deleteUserModal.querySelector('#deleteUserName');
            const formDelete = deleteUserModal.querySelector('#formDeleteUser');

            if (modalUserName) modalUserName.textContent = userName;
            if (formDelete) formDelete.setAttribute('action', deleteUrl);
        });
    }
});
</script>
<?= $this->endSection() ?>
