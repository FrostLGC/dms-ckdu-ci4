<!-- ============================================================
     HALAMAN: Edit / Revisi Dokumen
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<!-- Breadcrumb navigasi -->
<nav aria-label="breadcrumb" class="mb-4 animate-in">
    <ol class="breadcrumb" style="font-size:.85rem;">
        <li class="breadcrumb-item"><a href="<?= base_url('document') ?>" class="text-decoration-none">Dokumen</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('document/detail/' . $document['id']) ?>" class="text-decoration-none"><?= esc($document['judul']) ?></a></li>
        <li class="breadcrumb-item active">Edit / Revisi</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-lg-8 animate-in">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold" style="font-size:1.05rem;">
                    <i class="bi bi-pencil-square me-2" style="color:var(--dms-warning);"></i>
                    Edit Dokumen
                </h5>
                <span class="badge-<?= $document['status'] ?>">
                    <?= $document['status'] == 'aktif' ? '● Aktif' : '● Arsip' ?>
                </span>
            </div>
            <div class="card-body" style="padding:28px;">
                <!--
                    form_open_multipart() karena ada input file (opsional).
                    enctype="multipart/form-data" diperlukan agar file bisa dikirim.
                -->
                <?= form_open_multipart('document/update/' . $document['id']) ?>

                    <!-- INPUT: Nomor Dokumen / Surat (Opsional) -->
                    <div class="mb-4">
                        <label for="nomor_dokumen" class="form-label">
                            Nomor Dokumen / Surat <span class="text-muted">(Opsional)</span>
                        </label>
                        <input type="text"
                               class="form-control <?= $validation->hasError('nomor_dokumen') ? 'is-invalid' : '' ?>"
                               id="nomor_dokumen" name="nomor_dokumen"
                               value="<?= old('nomor_dokumen', $document['nomor_dokumen'] ?? '') ?>"
                               placeholder="Contoh: 001/HRD/CKDU/I/2026">
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Kosongkan jika dokumen tidak memiliki nomor dokumen atau nomor surat.
                        </div>
                        <?php if ($validation->hasError('nomor_dokumen')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('nomor_dokumen') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- INPUT: Judul Dokumen -->
                    <div class="mb-4">
                        <label for="judul" class="form-label">
                            Judul Dokumen <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control <?= $validation->hasError('judul') ? 'is-invalid' : '' ?>"
                               id="judul" name="judul"
                               value="<?= old('judul', $document['judul']) ?>"
                               placeholder="Contoh: Kontrak Proyek Pembangunan Gedung A">
                        <?php if ($validation->hasError('judul')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('judul') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- DROPDOWN: Kategori -->
                    <div class="mb-4">
                        <label for="category_id" class="form-label">
                            Kategori <span class="text-danger">*</span>
                        </label>
                        <select class="form-select <?= $validation->hasError('category_id') ? 'is-invalid' : '' ?>"
                                id="category_id" name="category_id">
                            <option value=""> Pilih Kategori </option>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= $cat['id'] ?>"
                                        <?= old('category_id', $document['category_id']) == $cat['id'] ? 'selected' : '' ?>>
                                    <?= esc($cat['nama_kategori']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($validation->hasError('category_id')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('category_id') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- DROPDOWN: Instansi -->
                    <div class="mb-4">
                        <label for="instansi_id" class="form-label">
                            Instansi / Mitra Kerja
                        </label>
                        <select class="form-select <?= $validation->hasError('instansi_id') ? 'is-invalid' : '' ?>"
                                id="instansi_id" name="instansi_id">
                            <option value=""> Opsional (Internal CKDU) </option>
                            <?php foreach ($instansis as $inst) : ?>
                                <option value="<?= $inst['id'] ?>"
                                        <?= old('instansi_id', $document['instansi_id']) == $inst['id'] ? 'selected' : '' ?>>
                                    <?= esc($inst['nama_instansi']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($validation->hasError('instansi_id')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('instansi_id') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- TEXTAREA: Deskripsi -->
                    <div class="mb-4">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi"
                                  rows="3"
                                  placeholder="Jelaskan isi dokumen secara singkat (opsional)"><?= old('deskripsi', $document['deskripsi']) ?></textarea>
                    </div>

                    <!-- DROPDOWN: Status -->
                    <div class="mb-4">
                        <label for="status" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select <?= $validation->hasError('status') ? 'is-invalid' : '' ?>"
                                id="status" name="status">
                            <option value="aktif" <?= old('status', $document['status']) == 'aktif' ? 'selected' : '' ?>>
                                Aktif
                            </option>
                            <option value="arsip" <?= old('status', $document['status']) == 'arsip' ? 'selected' : '' ?>>
                                Arsip
                            </option>
                        </select>
                        <?php if ($validation->hasError('status')) : ?>
                            <div class="invalid-feedback"><?= $validation->getError('status') ?></div>
                        <?php endif; ?>
                    </div>

                    <hr class="my-4">

                    <!-- Info File Saat Ini -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="bi bi-file-earmark-check me-1" style="color:var(--dms-primary);"></i>
                            File Saat Ini
                        </label>
                        <div class="alert alert-light border d-flex align-items-center gap-3 py-2 mb-3" style="border-radius:10px;">
                            <div style="width:40px; height:40px; border-radius:10px; background:rgba(67,97,238,.1); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i class="bi bi-file-earmark-fill" style="color:var(--dms-primary); font-size:1.1rem;"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.9rem;"><?= esc($document['nama_file_asli']) ?></div>
                                <small class="text-muted">
                                    <?= strtoupper(esc($document['tipe_file'])) ?> ·
                                    <?php
                                        $ukuran = $document['ukuran_file'];
                                        if ($ukuran >= 1048576) {
                                            echo number_format($ukuran / 1048576, 1) . ' MB';
                                        } elseif ($ukuran >= 1024) {
                                            echo number_format($ukuran / 1024, 1) . ' KB';
                                        } else {
                                            echo $ukuran . ' B';
                                        }
                                    ?>
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- INPUT: File Baru (Revisi - Opsional) -->
                    <div class="mb-4">
                        <label for="dokumen" class="form-label">
                            <i class="bi bi-arrow-repeat me-1" style="color:var(--dms-warning);"></i>
                            Upload File Revisi (Opsional)
                        </label>
                        <input type="file"
                               class="form-control <?= $validation->hasError('dokumen') ? 'is-invalid' : '' ?>"
                               id="dokumen" name="dokumen"
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Kosongkan jika tidak ada revisi file. File lama akan tetap tersimpan sebagai versi sebelumnya.
                        </div>
                        <?php if ($validation->hasError('dokumen')) : ?>
                            <div class="invalid-feedback d-block"><?= $validation->getError('dokumen') ?></div>
                        <?php endif; ?>
                    </div>

                    <!-- Preview file baru yang dipilih -->
                    <div id="filePreview" class="mb-4" style="display:none;">
                        <div class="alert alert-warning border-0 d-flex align-items-center gap-3 py-2" style="border-radius:10px;">
                            <i class="bi bi-arrow-up-circle-fill" style="font-size:1.5rem; color:var(--dms-warning);"></i>
                            <div>
                                <div class="fw-semibold" id="fileName" style="font-size:.9rem;"></div>
                                <small class="text-muted" id="fileSize"></small>
                                <small class="text-warning fw-semibold"> — File ini akan menggantikan versi lama</small>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- TOMBOL AKSI -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dms-primary">
                            <i class="bi bi-check-lg me-2"></i> Simpan Perubahan
                        </button>
                        <a href="<?= base_url('document/detail/' . $document['id']) ?>"
                           class="btn btn-outline-secondary" style="border-radius:10px;">
                            <i class="bi bi-x-lg me-1"></i> Batal
                        </a>
                    </div>

                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<!-- JavaScript: Preview file baru -->
<?= $this->section('scripts') ?>
<script>
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
