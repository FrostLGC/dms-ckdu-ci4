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
            background-color: #f3f4f6;
            color: #000;
            font-family: 'Times New Roman', Times, serif;
        }
        
        .print-page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 20px auto;
            padding: 12mm;
            background: #ffffff;
            box-sizing: border-box;
            box-shadow: 0 0 12px rgba(0,0,0,0.15);
        }
        
        .print-toolbar {
            width: 210mm;
            margin: 20px auto 10px auto;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 8px;
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
        .company-name {
            display: inline-block;
            font-size: 20px;
            font-weight: bold;
            color: #1f5f16;
            border-bottom: 2px solid #000;
            padding-bottom: 2px;
            margin-bottom: 4px;
            margin-top: 0;
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
        .report-meta-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 18px;
            margin-bottom: 18px;
        }
        .report-meta-left,
        .report-meta-right {
            font-size: 12px;
            line-height: 1.6;
        }
        .report-meta-right {
            min-width: 180px;
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
        table.laporan-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 9px;
            word-wrap: break-word;
            overflow-wrap: anywhere;
        }
        table.laporan-table, .laporan-table th, .laporan-table td {
            border: 1px solid #000;
        }
        .laporan-table th, .laporan-table td {
            padding: 4px;
            vertical-align: middle;
        }
        .laporan-table th {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
            text-align: center;
        }
        
        /* Pengaturan khusus saat di-print */
        @media print {
            body {
                background: #ffffff;
                margin: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .print-page {
                width: auto;
                min-height: auto;
                margin: 0;
                padding: 0;
                box-shadow: none;
            }
            @page {
                size: A4 portrait;
                margin: 10mm;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- Toolbar Aksi -->
    <div class="print-toolbar no-print">
        <a href="<?= base_url('report') ?>" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Laporan
        </a>
        <button onclick="window.print()" class="btn btn-primary shadow-sm">
            <i class="bi bi-printer me-1"></i> Cetak Sekarang
        </button>
    </div>

    <div class="print-page">
        
        <!-- Kop Surat -->
        <table style="width: 100%; border: none; margin-bottom: 5px;">
            <tr style="border: none;">
                <td style="width: 110px; text-align: left; border: none; padding: 0;">
                    <img src="<?= base_url('assets/img/logockdutransparan.png') ?>" alt="Logo CKDU" style="height: auto; width: 100px;">
                </td>
                <td style="text-align: center; border: none; padding: 0 110px 0 0;">
                    <h2 class="company-name">P.T. CIPTA KARYA DHARMA UTAMA</h2>
                    <h3 style="margin: 0 0 3px 0; font-weight: 700; font-size: 14px;">Employment Management Services</h3>
                    <p style="margin: 0; font-size: 10px; font-weight: 700;">Head Office : Jl. Gatot Subroto Km. 5,4 Ruko Sastra Plaza Blok A No. 25 Jati Uwung - Tangerang Banten 15134</p>
                    <p style="margin: 0; font-size: 10px; font-weight: 700;">Telp. 021-55657005, 021-55656968, Fax. 021-55650129</p>
                    <p style="margin: 0; font-size: 10px; font-weight: 700;">Email : pt_ckdu@cbn.net.id / pt_ckdu@yahoo.com</p>
                </td>
            </tr>
        </table>
        <div style="border-top: 3px solid #000; margin-top: 10px; margin-bottom: 20px; padding: 1px 0;"></div>

        <!-- Judul Laporan -->
        <div class="judul-laporan">
            LAPORAN ARSIP DOKUMEN
        </div>

        <!-- Nomor Surat dan Info Tambahan -->
        <div class="report-meta-top">
            <div class="report-meta-left">
                <table style="border: none; width: auto; font-size: 12px;">
                    <tr>
                        <td style="width: 80px; padding: 2px 0; border: none;">Nomor</td>
                        <td style="width: 15px; padding: 2px 0; border: none;">:</td>
                        <td style="padding: 2px 0; border: none;"><?= esc($nomorLaporan ?: '-') ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 0; border: none;">Lampiran</td>
                        <td style="padding: 2px 0; border: none;">:</td>
                        <td style="padding: 2px 0; border: none;">-</td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 0; border: none;">Perihal</td>
                        <td style="padding: 2px 0; border: none;">:</td>
                        <td style="padding: 2px 0; border: none;">Laporan Arsip Dokumen</td>
                    </tr>
                </table>
            </div>
            <div class="report-meta-right">
                <table style="border: none; width: auto; margin-left: auto; font-size: 12px; text-align: left;">
                    <tr>
                        <td style="width: 100px; padding: 2px 0; border: none;">Tanggal Cetak</td>
                        <td style="width: 15px; padding: 2px 0; border: none;">:</td>
                        <td style="padding: 2px 0; border: none;"><?= date('d/m/Y') ?></td>
                    </tr>
                    <tr>
                        <td style="padding: 2px 0; border: none;">Total Dokumen</td>
                        <td style="padding: 2px 0; border: none;">:</td>
                        <td style="padding: 2px 0; border: none;"><?= count($documents) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Info Parameter Filter -->
        <div class="info-laporan">
            <div class="row">
                <div class="col-12">
                    <table style="border: none; width: auto;">
                        <tr><td style="border: none; padding: 2px 10px 2px 0;"><strong>Keyword</strong></td><td style="border: none; padding: 2px;">: <?= esc($keywordLabel) ?></td></tr>
                        <tr><td style="border: none; padding: 2px 10px 2px 0;"><strong>Kategori</strong></td><td style="border: none; padding: 2px;">: <?= esc($kategoriNama) ?></td></tr>
                        <tr><td style="border: none; padding: 2px 10px 2px 0;"><strong>Instansi/Mitra</strong></td><td style="border: none; padding: 2px;">: <?= esc($instansiNama) ?></td></tr>
                        <tr><td style="border: none; padding: 2px 10px 2px 0;"><strong>Status</strong></td><td style="border: none; padding: 2px;">: <?= esc($statusNama) ?></td></tr>
                        <tr><td style="border: none; padding: 2px 10px 2px 0;"><strong>Uploader</strong></td><td style="border: none; padding: 2px;">: <?= esc($uploaderNama) ?></td></tr>
                        <tr><td style="border: none; padding: 2px 10px 2px 0;"><strong>Periode</strong></td><td style="border: none; padding: 2px;">: 
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
                        </td></tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabel Data -->
        <table class="laporan-table">
            <thead>
                <tr>
                    <th style="width: 25px;">No</th>
                    <th style="width: 15%;">Nomor Dokumen</th>
                    <th style="width: 25%;">Judul Dokumen</th>
                    <th style="width: 12%;">Kategori</th>
                    <th style="width: 15%;">Instansi/Mitra</th>
                    <th style="width: 8%;">Status</th>
                    <th style="width: 10%;">Tgl. Upload</th>
                    <th style="width: 15%;">Uploader</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($documents)) : ?>
                    <tr>
                        <td colspan="8" class="text-center py-4">Tidak ada data dokumen yang sesuai dengan filter.</td>
                    </tr>
                <?php else : ?>
                    <?php $no = 1; foreach ($documents as $doc) : ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= esc($doc['nomor_dokumen'] ?: '-') ?></td>
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
