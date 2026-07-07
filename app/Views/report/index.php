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
                        <div class="col-md-12">
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
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
