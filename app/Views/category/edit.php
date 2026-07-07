<!-- ============================================================
     HALAMAN: Edit Kategori
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4 animate-in">
    <ol class="breadcrumb" style="font-size:.85rem;">
        <li class="breadcrumb-item"><a href="<?= base_url('category') ?>" class="text-decoration-none">Kategori</a></li>
        <li class="breadcrumb-item active">Edit — <?= esc($category['nama_kategori']) ?></li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-7 animate-in">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold" style="font-size:1.05rem;">
                    <i class="bi bi-pencil-square me-2" style="color:var(--dms-warning);"></i>
                    Edit Kategori
                </h5>
                <!-- Info kapan kategori dibuat -->
                <small class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i>
                    Dibuat: <?= date('d M Y', strtotime($category['created_at'])) ?>
                </small>
            </div>
            <div class="card-body" style="padding:28px;">
                <!--
                    form_open() mengarah ke /category/update/{id}
                    Data akan dikirim via POST ke fungsi update() di Controller
                -->
                <?= form_open('category/update/' . $category['id']) ?>

                    <!-- INPUT: Nama Kategori (terisi data lama) -->
                    <div class="mb-4">
                        <label for="nama_kategori" class="form-label">
                            Nama Kategori <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control <?= $validation->hasError('nama_kategori') ? 'is-invalid' : '' ?>"
                               id="nama_kategori" name="nama_kategori"
                               value="<?= old('nama_kategori', $category['nama_kategori']) ?>"
                               placeholder="Masukkan nama kategori">
                        <!--
                            old('nama_kategori', $category['nama_kategori'])
                            Artinya:
                            1. Jika ada data dari form sebelumnya (setelah validasi gagal), tampilkan itu
                            2. Jika tidak, tampilkan data asli dari database ($category)
                            
                            Di PHP Native, ini seperti:
                            value="< isset($_POST['nama_kategori']) ? $_POST['nama_kategori'] : $kategori['nama_kategori'] ?>"
                        -->
                        <?php if ($validation->hasError('nama_kategori')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('nama_kategori') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- INPUT: Deskripsi (terisi data lama) -->
                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control <?= $validation->hasError('deskripsi') ? 'is-invalid' : '' ?>"
                                  id="deskripsi" name="deskripsi"
                                  rows="3"
                                  placeholder="Jelaskan kegunaan kategori ini (opsional)"><?= old('deskripsi', $category['deskripsi']) ?></textarea>
                        <?php if ($validation->hasError('deskripsi')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('deskripsi') ?></div>
                        <?php endif; ?>
                    </div>

                    <hr class="my-4">

                    <!-- TOMBOL AKSI -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dms-primary">
                            <i class="bi bi-check-lg me-2"></i> Simpan Perubahan
                        </button>
                        <a href="<?= base_url('category') ?>" class="btn btn-outline-secondary" style="border-radius:10px;">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </a>
                    </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
