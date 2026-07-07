<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    
    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    
    <style>
        body {
            background-color: #fff;
            color: #000;
            font-family: 'Times New Roman', Times, serif;
        }
        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
        }
        .kop-surat h2 {
            margin: 0;
            font-weight: bold;
            font-size: 24px;
        }
        .kop-surat p {
            margin: 0;
            font-size: 14px;
        }
        .garis-kop {
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            margin-top: 10px;
            margin-bottom: 20px;
            padding: 1px 0;
        }
        .judul-laporan {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .info-laporan {
            margin-bottom: 15px;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            vertical-align: middle;
        }
        th {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
            text-align: center;
        }
        
        /* Pengaturan khusus saat di-print */
        @media print {
            @page {
                size: A4 landscape;
                margin: 2cm;
            }
            .btn-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <div class="container-fluid mt-4">
        
        <!-- Tombol Print Manual (Disembunyikan saat dicetak) -->
        <div class="text-end mb-4 btn-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer"></i> Cetak Sekarang
            </button>
        </div>

        <!-- Kop Surat -->
        <div class="kop-surat">
            <h2>PT. CIPTA KARYA DHARMA UTAMA</h2>
            <p>Gedung Menara Merdeka Lt. 12, Jl. Jend. Sudirman Kav. 45, Jakarta Selatan 12920</p>
            <p>Telepon: (021) 555-1234 | Email: info@ckdu.co.id | Website: www.ckdu.co.id</p>
        </div>
        <div class="garis-kop"></div>

        <!-- Judul Laporan -->
        <div class="judul-laporan">
            LAPORAN ARSIP DOKUMEN
        </div>

        <!-- Info Parameter Filter -->
        <div class="info-laporan">
            <div class="row">
                <div class="col-8">
                    <strong>Kategori:</strong> <?= esc($kategoriNama) ?><br>
                    <strong>Periode:</strong> 
                    <?php
                        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                            echo date('d/m/Y', strtotime($filters['start_date'])) . ' - ' . date('d/m/Y', strtotime($filters['end_date']));
                        } elseif (!empty($filters['start_date'])) {
                            echo 'Sejak ' . date('d/m/Y', strtotime($filters['start_date']));
                        } elseif (!empty($filters['end_date'])) {
                            echo 'Sampai ' . date('d/m/Y', strtotime($filters['end_date']));
                        } else {
                            echo 'Semua Waktu';
                        }
                    ?>
                </div>
                <div class="col-4 text-end">
                    <strong>Tanggal Cetak:</strong> <?= date('d/m/Y') ?>
                </div>
            </div>
        </div>

        <!-- Tabel Data -->
        <table>
            <thead>
                <tr>
                    <th style="width: 40px;">No</th>
                    <th>Judul Dokumen</th>
                    <th>Kategori</th>
                    <th>Instansi/Mitra</th>
                    <th>Status</th>
                    <th>Tgl. Upload</th>
                    <th>Uploader</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($documents)) : ?>
                    <tr>
                        <td colspan="7" class="text-center py-4">Tidak ada data dokumen yang sesuai dengan filter.</td>
                    </tr>
                <?php else : ?>
                    <?php $no = 1; foreach ($documents as $doc) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= esc($doc['judul']) ?></td>
                            <td><?= esc($doc['nama_kategori'] ?? '-') ?></td>
                            <td><?= esc($doc['nama_instansi'] ?? 'Internal') ?></td>
                            <td class="text-center">
                                <?= ucfirst(esc($doc['status'])) ?>
                            </td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($doc['created_at'])) ?></td>
                            <td><?= esc($doc['nama_uploader'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

    <!-- Script otomatis print saat halaman dimuat -->
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
