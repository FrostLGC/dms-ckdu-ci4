<!-- ============================================================
     HALAMAN: Edit Pengguna
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4 animate-in">
    <ol class="breadcrumb" style="font-size:.85rem;">
        <li class="breadcrumb-item"><a href="<?= base_url('user') ?>" class="text-decoration-none">Pengguna</a></li>
        <li class="breadcrumb-item active">Edit — <?= esc($user['nama']) ?></li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-7 animate-in">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold" style="font-size:1.05rem;">
                    <i class="bi bi-pencil-square me-2" style="color:var(--dms-warning);"></i>
                    Edit Pengguna
                </h5>
                <small class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i>
                    Terdaftar: <?= date('d M Y', strtotime($user['created_at'])) ?>
                </small>
            </div>
            <div class="card-body" style="padding:28px;">
                <?= form_open('user/update/' . $user['id']) ?>

                    <!-- INPUT: Nama Lengkap -->
                    <div class="mb-4">
                        <label for="nama" class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control <?= $validation->hasError('nama') ? 'is-invalid' : '' ?>"
                               id="nama" name="nama"
                               value="<?= old('nama', $user['nama']) ?>"
                               placeholder="Masukkan nama lengkap">
                        <?php if ($validation->hasError('nama')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('nama') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- INPUT: Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email"
                               class="form-control <?= $validation->hasError('email') ? 'is-invalid' : '' ?>"
                               id="email" name="email"
                               value="<?= old('email', $user['email']) ?>"
                               placeholder="contoh: staf@ckdu.com">
                        <?php if ($validation->hasError('email')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- INPUT: Password (Opsional saat edit) -->
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            Password Baru
                        </label>
                        <input type="password"
                               class="form-control <?= $validation->hasError('password') ? 'is-invalid' : '' ?>"
                               id="password" name="password"
                               placeholder="Kosongkan jika tidak ingin mengubah password">
                        <?php if ($validation->hasError('password')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                        <?php endif; ?>
                        <!--
                            Penjelasan penting untuk admin:
                            Jika field ini DIKOSONGKAN, password lama TIDAK akan berubah.
                            Ini berguna saat admin hanya ingin mengedit nama/email/role saja.
                        -->
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Kosongkan jika tidak ingin mengubah password. Isi minimal 6 karakter untuk mengganti.
                        </div>
                    </div>

                    <!-- DROPDOWN: Role -->
                    <div class="mb-4">
                        <label for="role" class="form-label">
                            Role <span class="text-danger">*</span>
                        </label>
                        <select class="form-select <?= $validation->hasError('role') ? 'is-invalid' : '' ?>"
                                id="role" name="role">
                            <option value=""> Pilih Role </option>
                            <option value="admin" <?= old('role', $user['role']) == 'admin' ? 'selected' : '' ?>>
                                Admin (Akses penuh)
                            </option>
                            <option value="hrd" <?= old('role', $user['role']) == 'hrd' ? 'selected' : '' ?>>
                                Staf HRD (Kelola dokumen & kategori)
                            </option>
                            <option value="pimpinan" <?= old('role', $user['role']) == 'pimpinan' ? 'selected' : '' ?>>
                                Pimpinan (Lihat dokumen & instansi)
                            </option>
                        </select>
                        <?php if ($validation->hasError('role')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('role') ?></div>
                        <?php endif; ?>
                    </div>

                    <hr class="my-4">

                    <!-- TOMBOL AKSI -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dms-primary">
                            <i class="bi bi-check-lg me-2"></i> Simpan Perubahan
                        </button>
                        <a href="<?= base_url('user') ?>" class="btn btn-outline-secondary" style="border-radius:10px;">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </a>
                    </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
