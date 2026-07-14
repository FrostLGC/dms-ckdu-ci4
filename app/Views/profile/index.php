<!-- ============================================================
     HALAMAN: Profil Saya - Edit Data Profil User yang Login
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-lg-7 animate-in">

        <!-- Header Profil -->
        <div class="card mb-4">
            <div class="card-body d-flex align-items-center gap-4" style="padding:28px;">
                <!-- Avatar besar -->
                <div style="width:70px; height:70px; border-radius:18px; background:linear-gradient(135deg, var(--dms-primary), var(--dms-secondary)); display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:1.6rem; flex-shrink:0;">
                    <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                </div>
                <div>
                    <h4 class="fw-bold mb-1" style="color:var(--dms-dark);">
                        <?= esc($user['nama']) ?>
                    </h4>
                    <p class="text-muted mb-1" style="font-size:.9rem;">
                        <i class="bi bi-envelope me-1"></i> <?= esc($user['email']) ?> 
                    </p>
                    <?php
                        $roleLower = strtolower($user['role']);
                        if ($roleLower === 'admin') {
                            $badgeClass = 'bg-primary bg-opacity-10 text-primary';
                            $badgeStyle = '';
                            $badgeIcon  = 'bi-shield-lock-fill';
                            $badgeText  = 'Admin';
                        } elseif ($roleLower === 'hrd') {
                            $badgeClass = 'bg-success bg-opacity-10 text-success';
                            $badgeStyle = '';
                            $badgeIcon  = 'bi-person-badge-fill';
                            $badgeText  = 'HRD';
                        } elseif ($roleLower === 'pimpinan') {
                            $badgeClass = '';
                            $badgeStyle = 'background-color: #e2e8f0; color: #1e293b;';
                            $badgeIcon  = 'bi-eye-fill';
                            $badgeText  = 'Pimpinan';
                        } else {
                            $badgeClass = 'bg-secondary bg-opacity-10 text-secondary';
                            $badgeStyle = '';
                            $badgeIcon  = 'bi-person-fill';
                            $badgeText  = ucfirst(esc($user['role']));
                        }
                    ?>
                    <span class="badge <?= $badgeClass ?>" style="font-size:.78rem; font-weight:600; padding:5px 12px; border-radius:20px; <?= $badgeStyle ?>">
                        <i class="bi <?= $badgeIcon ?> me-1"></i> <?= $badgeText ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Edit Profil -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold" style="font-size:1.05rem;">
                    <i class="bi bi-person-gear me-2" style="color:var(--dms-primary);"></i>
                    Edit Profil
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

                <?= form_open('profile/update') ?>

                    <!-- Nama Lengkap -->
                    <div class="mb-4">
                        <label for="nama" class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>"
                               id="nama" name="nama"
                               value="<?= esc(old('nama', $user['nama'])) ?>" required minlength="2" maxlength="100">
                        <?php if (isset($errors['nama'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['nama']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email"
                               class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                               id="email" name="email"
                               value="<?= esc(old('email', $user['email'])) ?>" required maxlength="255">
                        <?php if (isset($errors['email'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['email']) ?></div>
                        <?php endif; ?>
                    </div>

                    <hr class="my-4">

                    <!-- Password Baru (Opsional) -->
                    <div class="mb-4">
                        <label for="password" class="form-label">
                            Password Baru
                        </label>
                        <input type="password"
                               class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                               id="password" name="password"
                               placeholder="Kosongkan jika tidak ingin mengubah password" minlength="6">
                        <?php if (isset($errors['password'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['password']) ?></div>
                        <?php endif; ?>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Kosongkan jika tidak ingin mengubah password. Minimal 6 karakter untuk mengganti.
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dms-primary">
                            <i class="bi bi-check-lg me-2"></i> Simpan Perubahan
                        </button>
                        <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary" style="border-radius:10px;">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
