<!-- ============================================================
     HALAMAN: Upload Dokumen Baru (Form)
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb navigasi -->
<nav aria-label="breadcrumb" class="mb-4 animate-in">
    <ol class="breadcrumb" style="font-size:.85rem;">
        <li class="breadcrumb-item"><a href="<?= base_url('document') ?>" class="text-decoration-none">Dokumen</a></li>
        <li class="breadcrumb-item active">Upload Baru</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-8 animate-in">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold" style="font-size:1.05rem;">
                    <i class="bi bi-cloud-arrow-up-fill me-2" style="color:var(--dms-primary);"></i>
                    Upload Dokumen Baru
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
                            <div class="mt-2 small text-danger fw-bold border-top border-danger pt-2">
                                <i class="bi bi-info-circle me-1"></i> File Dokumen perlu dipilih ulang karena pembatasan keamanan browser.
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!--
                    form_open_multipart() = buat <form> dengan:
                    1. method="POST"
                    2. enctype="multipart/form-data" (WAJIB untuk upload file)
                    3. CSRF token otomatis (keamanan)
                -->
                <?= form_open_multipart('document/upload') ?>

                    <!-- INPUT: Nomor Dokumen / Surat (Opsional) -->
                    <div class="mb-4">
                        <label for="nomor_dokumen" class="form-label">
                            Nomor Dokumen / Surat <span class="text-muted">(Opsional)</span>
                        </label>
                        <input type="text"
                               class="form-control <?= isset($errors['nomor_dokumen']) ? 'is-invalid' : '' ?>"
                               id="nomor_dokumen" name="nomor_dokumen"
                               value="<?= esc(old('nomor_dokumen')) ?>"
                               placeholder="Contoh: 001/HRD/CKDU/I/2026" maxlength="100">
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Kosongkan jika dokumen tidak memiliki nomor dokumen atau nomor surat.
                        </div>
                        <?php if (isset($errors['nomor_dokumen'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['nomor_dokumen']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- INPUT: Judul Dokumen -->
                    <div class="mb-4">
                        <label for="judul" class="form-label">
                            Judul Dokumen <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control <?= isset($errors['judul']) ? 'is-invalid' : '' ?>"
                               id="judul" name="judul"
                               value="<?= esc(old('judul')) ?>"
                               placeholder="Contoh: Kontrak Proyek Pembangunan Gedung A" required maxlength="255">
                        <?php if (isset($errors['judul'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['judul']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- INPUT: Kategori (Dropdown Select) -->
                    <div class="mb-4">
                        <label for="category_id" class="form-label">
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <select class="form-select <?= isset($errors['category_id']) ? 'is-invalid' : '' ?>"
                                id="category_id" name="category_id" required>
                            <option value=""> Pilih Kategori </option>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= $cat['id'] ?>"
                                        <?= old('category_id') == $cat['id'] ? 'selected' : '' ?>>
                                    <?= esc($cat['nama_kategori']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['category_id'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['category_id']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- INPUT: Instansi (Dropdown Select) -->
                    <div class="mb-4">
                        <label for="instansi_id" class="form-label">
                            Instansi / Mitra Kerja
                        </label>
                        <select class="form-select <?= isset($errors['instansi_id']) ? 'is-invalid' : '' ?>"
                                id="instansi_id" name="instansi_id">
                            <option value=""> Opsional (Internal CKDU) </option>
                            <?php foreach ($instansis as $inst) : ?>
                                <option value="<?= $inst['id'] ?>"
                                        <?= old('instansi_id') == $inst['id'] ? 'selected' : '' ?>>
                                    <?= esc($inst['nama_instansi']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['instansi_id'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['instansi_id']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- INPUT: Deskripsi (Textarea) -->
                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control <?= isset($errors['deskripsi']) ? 'is-invalid' : '' ?>" id="deskripsi" name="deskripsi"
                                  rows="3"
                                  placeholder="Jelaskan isi dokumen secara singkat (opsional)" maxlength="1000"><?= esc(old('deskripsi')) ?></textarea>
                        <?php if (isset($errors['deskripsi'])) : ?>
                            <div class="invalid-feedback"><?= esc($errors['deskripsi']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- INPUT: File Dokumen (Upload) -->
                    <div class="mb-4">
                        <label for="dokumen" class="form-label">
                            File Dokumen <span class="text-danger">*</span>
                        </label>
                        <input type="file"
                               class="form-control <?= isset($errors['dokumen']) ? 'is-invalid' : '' ?>"
                               id="dokumen" name="dokumen"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" required>
                        <!-- Informasi format yang diizinkan -->
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Format: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG. Maks: 10 MB.
                        </div>
                        <?php if (isset($errors['dokumen'])) : ?>
                            <div class="invalid-feedback d-block"><?= esc($errors['dokumen']) ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Preview nama file yang dipilih -->
                    <div id="filePreview" class="mb-4" style="display:none;">
                        <div class="alert alert-light border d-flex align-items-center gap-3 py-2" style="border-radius:10px;">
                            <i class="bi bi-file-earmark-check-fill" style="font-size:1.5rem; color:var(--dms-success);"></i>
                            <div>
                                <div class="fw-semibold" id="fileName" style="font-size:.9rem;"></div>
                                <small class="text-muted" id="fileSize"></small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- TOMBOL AKSI -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dms-primary">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i> Upload Dokumen
                        </button>
                        <a href="<?= base_url('document') ?>" class="btn btn-outline-secondary" style="border-radius:10px;">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </a>
                    </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- Section untuk JavaScript tambahan -->
<?= $this->section('scripts') ?>
<script>
    // Preview file yang dipilih sebelum upload
    document.getElementById('dokumen').addEventListener('change', function(e) {
        const preview = document.getElementById('filePreview');
        const nameEl  = document.getElementById('fileName');
        const sizeEl  = document.getElementById('fileSize');

        if (this.files && this.files[0]) {
            const file = this.files[0];
            const sizeMB = (file.size / 1048576).toFixed(2);

            nameEl.textContent = file.name;
            sizeEl.textContent = sizeMB + ' MB';
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    });
</script>
<?= $this->endSection() ?>
