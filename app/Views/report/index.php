<!-- ============================================================
     HALAMAN: Filter Cetak Laporan
     Meng-extend layout/main.php
     ============================================================ -->
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="mb-4 animate-in">
    <h4 class="fw-bold mb-1" style="color: var(--dms-dark);">
        <i class="bi bi-printer me-2" style="color: var(--dms-primary);"></i>
        Cetak Laporan
    </h4>
    <p class="text-muted mb-0" style="font-size: .85rem;">
        Filter dan cetak laporan data arsip dokumen
    </p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8 animate-in">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 fw-bold" style="font-size:1.05rem;">
                    Parameter Laporan
                </h5>
            </div>
            <div class="card-body" style="padding:28px;">
                <!-- Target _blank agar hasil print terbuka di tab baru -->
                <form action="<?= base_url('report/print') ?>" method="GET" target="_blank">
                    <div class="row g-3 mb-4">
                        <!-- Nomor Laporan Opsional -->
                        <div class="col-md-12">
                            <label for="nomor_laporan" class="form-label">Nomor Laporan / Surat (Opsional)</label>
                            <input type="text" class="form-control" id="nomor_laporan" name="nomor_laporan" placeholder="Contoh: 029/CKDU/DIR/IV/2026">
                            <div class="form-text">Kosongkan jika laporan tidak menggunakan nomor surat.</div>
                        </div>

                        <!-- Keyword -->
                        <div class="col-md-12">
                            <label for="keyword" class="form-label">Keyword / Nomor Dokumen</label>
                            <input type="text" class="form-control" id="keyword" name="keyword" placeholder="Cari judul, nomor dokumen, atau deskripsi...">
                        </div>
                        
                        <!-- Kategori -->
                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Kategori Dokumen</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($categories as $cat) : ?>
                                    <option value="<?= $cat['id'] ?>">
                                        <?= esc($cat['nama_kategori']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Instansi -->
                        <div class="col-md-6">
                            <label for="instansi_id" class="form-label">Instansi / Mitra</label>
                            <select class="form-select" id="instansi_id" name="instansi_id">
                                <option value="">Semua Instansi</option>
                                <?php foreach ($instansis as $inst) : ?>
                                    <option value="<?= $inst['id'] ?>">
                                        <?= esc($inst['nama_instansi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status Dokumen</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="arsip">Arsip</option>
                            </select>
                        </div>

                        <!-- Uploader -->
                        <div class="col-md-6">
                            <label for="uploaded_by" class="form-label">Uploader / Pengunggah</label>
                            <select class="form-select" id="uploaded_by" name="uploaded_by">
                                <option value="">Semua Uploader</option>
                                <?php foreach ($uploaders as $user) : ?>
                                    <option value="<?= $user['id'] ?>">
                                        <?= esc($user['nama']) ?> (<?= esc(ucfirst($user['role'])) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Tanggal -->
                        <div class="col-md-6">
                            <label for="start_date" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="col-md-6">
                            <label for="end_date" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dms-primary">
                            <i class="bi bi-printer-fill me-2"></i> Buat Laporan
                        </button>
                        <a href="<?= base_url('report') ?>" class="btn btn-outline-secondary" style="border-radius:10px;">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
