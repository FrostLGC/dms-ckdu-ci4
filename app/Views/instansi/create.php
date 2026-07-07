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
                <?= form_open('instansi/store') ?>

                    <!-- Nama Instansi -->
                    <div class="mb-4">
                        <label for="nama_instansi" class="form-label">
                            Nama Instansi <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control <?= $validation->hasError('nama_instansi') ? 'is-invalid' : '' ?>"
                               id="nama_instansi" name="nama_instansi"
                               value="<?= old('nama_instansi') ?>"
                               placeholder="Contoh: Dinas Pendidikan Kab. Bandung">
                        <?php if ($validation->hasError('nama_instansi')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('nama_instansi') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-4">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat"
                                  rows="3" placeholder="Alamat lengkap instansi (opsional)"><?= old('alamat') ?></textarea>
                    </div>

                    <!-- No. Telepon -->
                    <div class="mb-4">
                        <label for="no_telp" class="form-label">No. Telepon</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp"
                               value="<?= old('no_telp') ?>"
                               placeholder="Contoh: 022-1234567">
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
