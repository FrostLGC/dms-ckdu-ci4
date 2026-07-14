<!-- ============================================================
     HALAMAN: Tambah Pengguna Baru
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4 animate-in">
    <ol class="breadcrumb" style="font-size:.85rem;">
        <li class="breadcrumb-item"><a href="<?= base_url('user') ?>" class="text-decoration-none">Pengguna</a></li>
        <li class="breadcrumb-item active">Tambah Baru</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-7 animate-in">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold" style="font-size:1.05rem;">
                    <i class="bi bi-person-plus-fill me-2" style="color:var(--dms-primary);"></i>
                    Tambah Pengguna Baru
                </h5>
            </div>
            <div class="card-body" style="padding:28px;">
                <?php $errors = session()->getFlashdata('errors'); ?>
                
                <?php if (!empty($errors)) : ?>
                <div class="alert alert-danger animate-in" role="alert" style="border-radius:10px;">
                    <div class="d-flex gap-2">
                        <i class="bi bi-exclamation-octagon-fill fs-5"></i>
                        <div>
                            <strong class="d-block mb-1">Terdapat kesalahan pada form:</strong>
                            <ul class="mb-0 ps-3" style="font-size: 0.9rem;">
                            <?php foreach ($errors as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?= form_open('user/store') ?>

                    <!-- INPUT: Nama Lengkap -->
                    <div class="mb-4">
                        <label for="nama" class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>"
                               id="nama" name="nama"
                               value="<?= old('nama') ?>"
                               placeholder="Masukkan nama lengkap pengguna" required>
                        <?php if (isset($errors['nama'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['nama']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- INPUT: Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email"
                               class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                               id="email" name="email"
                               value="<?= old('email') ?>"
                               placeholder="contoh: staf@ckdu.com" required>
                        <?php if (isset($errors['email'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- INPUT: Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            Password <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                               id="password" name="password"
                               placeholder="Minimal 6 karakter" required minlength="6">
                        <?php if (isset($errors['password'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['password']) ?></div>
                        <?php endif; ?>
                        <div class="form-text">
                            <i class="bi bi-shield-lock me-1"></i>
                            Password akan dienkripsi menggunakan BCRYPT sebelum disimpan.
                        </div>
                    </div>

                    <!-- DROPDOWN: Role -->
                    <div class="mb-4">
                        <label for="role" class="form-label">
                            Role <span class="text-danger">*</span>
                        </label>
                        <select class="form-select <?= isset($errors['role']) ? 'is-invalid' : '' ?>"
                                id="role" name="role" required>
                            <option value=""> Pilih Role </option>
                            <option value="admin" <?= old('role') == 'admin' ? 'selected' : '' ?>>
                                Admin (Akses penuh)
                            </option>
                            <option value="hrd" <?= old('role') == 'hrd' ? 'selected' : '' ?>>
                                Staf HRD (Kelola dokumen & kategori)
                            </option>
                            <option value="pimpinan" <?= old('role') == 'pimpinan' ? 'selected' : '' ?>>
                                Pimpinan (Lihat dokumen & instansi)
                            </option>
                        </select>
                        <?php if (isset($errors['role'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['role']) ?></div>
                        <?php endif; ?>
                    </div>

                    <hr class="my-4">

                    <!-- TOMBOL AKSI -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dms-primary">
                            <i class="bi bi-check-lg me-2"></i> Simpan Pengguna
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
