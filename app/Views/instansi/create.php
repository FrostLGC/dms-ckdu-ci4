<!-- ============================================================
     HALAMAN: Tambah Instansi Baru
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<nav aria-label="breadcrumb" class="mb-4 animate-in">
    <ol class="breadcrumb" style="font-size:.85rem;">
        <li class="breadcrumb-item"><a href="<?= base_url('instansi') ?>" class="text-decoration-none">Instansi</a></li>
        <li class="breadcrumb-item active">Tambah Baru</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-7 animate-in">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold" style="font-size:1.05rem;">
                    <i class="bi bi-building me-2" style="color:var(--dms-primary);"></i>
                    Tambah Instansi Baru
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

                <?= form_open('instansi/store') ?>

                    <!-- Nama Instansi -->
                    <div class="mb-4">
                        <label for="nama_instansi" class="form-label">
                            Nama Instansi <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control <?= isset($errors['nama_instansi']) ? 'is-invalid' : '' ?>"
                               id="nama_instansi" name="nama_instansi"
                               value="<?= esc(old('nama_instansi')) ?>"
                               placeholder="Contoh: Dinas Pendidikan Kab. Bandung" required maxlength="255">
                        <?php if (isset($errors['nama_instansi'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['nama_instansi']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-4">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control <?= isset($errors['alamat']) ? 'is-invalid' : '' ?>" 
                                  id="alamat" name="alamat" rows="3" 
                                  placeholder="Alamat lengkap instansi (opsional)" maxlength="500"><?= esc(old('alamat')) ?></textarea>
                        <?php if (isset($errors['alamat'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['alamat']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- No. Telepon -->
                    <div class="mb-4">
                        <label for="no_telp" class="form-label">No. Telepon</label>
                        <input type="tel" class="form-control <?= isset($errors['no_telp']) ? 'is-invalid' : '' ?>" 
                               id="no_telp" name="no_telp"
                               value="<?= esc(old('no_telp')) ?>"
                               placeholder="Contoh: 022-1234567" maxlength="20" pattern="[0-9\s\+\-\(\)]+">
                        <?php if (isset($errors['no_telp'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['no_telp']) ?></div>
                        <?php endif; ?>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dms-primary">
                            <i class="bi bi-check-lg me-2"></i> Simpan Instansi
                        </button>
                        <a href="<?= base_url('instansi') ?>" class="btn btn-outline-secondary" style="border-radius:10px;">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </a>
                    </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
