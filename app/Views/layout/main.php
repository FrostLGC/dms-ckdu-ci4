<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Manajemen Dokumen - PT. Cipta Karya Dharma Utama">
    <title><?= esc($title ?? 'Dashboard') ?> - DMS PT. CKDU</title>

    <!-- ============================================================
         CSS: Bootstrap 5 (lokal/offline) + Custom CSS
         Cara CI4 memuat file statis: base_url() mengarah ke folder public/
         Jadi base_url('assets/...') = public/assets/...
         ============================================================ -->
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">

    <!-- Bootstrap Icons (CDN) - ikon gratis dari Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <!-- ============================================================
         SIDEBAR - Navigasi utama di sebelah kiri
         ============================================================ -->
    <aside class="dms-sidebar" id="sidebar">
        <!-- Brand / Logo -->
        <div class="sidebar-brand">
            <img src="<?= base_url('assets/img/logockdutransparan.png') ?>" alt="Logo CKDU" style="max-width: 80px; height: auto; margin-bottom: 10px;">
            <h5>DMS</h5>
            <small>PT. Cipta Karya Dharma Utama</small>
        </div>

        <!-- Wrapper untuk scroll -->
        <div class="sidebar-scroll-area">
            <!-- Label Section -->
            <div class="nav-section">Menu Utama</div>

        <!-- Menu Navigasi -->
        <?php $role = session()->get('user_role'); ?>
        <ul class="sidebar-nav">
            <!-- Dashboard: SEMUA PERAN -->
            <li>
                <a href="<?= base_url('dashboard') ?>"
                   class="<?= (uri_string() == '' || uri_string() == '/' || str_contains(uri_string(), 'dashboard')) ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="bi bi-grid-1x2-fill"></i></span>
                    Dashboard
                </a>
            </li>

            <!-- Dokumen (lihat): SEMUA PERAN -->
            <li>
                <a href="<?= base_url('document') ?>"
                   class="<?= (uri_string() == 'document' || str_contains(uri_string(), 'document/detail') || str_contains(uri_string(), 'document/edit')) ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="bi bi-file-earmark-text-fill"></i></span>
                    Dokumen
                </a>
            </li>

            <!-- Upload Dokumen: admin + hrd -->
            <?php if (in_array($role, ['admin', 'hrd'])) : ?>
            <li>
                <a href="<?= base_url('document/create') ?>"
                   class="<?= uri_string() == 'document/create' ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="bi bi-cloud-arrow-up-fill"></i></span>
                    Upload Dokumen
                </a>
            </li>
            <?php endif; ?>

            <!-- Kategori: SEMUA PERAN (pimpinan read-only) -->
            <li>
                <a href="<?= base_url('category') ?>"
                   class="<?= str_contains(uri_string(), 'category') ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="bi bi-folder2-open"></i></span>
                    Kategori
                </a>
            </li>

            <!-- Instansi: admin + hrd + pimpinan (pimpinan read-only sejak Iterasi 12) -->
            <li>
                <a href="<?= base_url('instansi') ?>"
                   class="<?= str_contains(uri_string(), 'instansi') ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="bi bi-building"></i></span>
                    Instansi
                </a>
            </li>

            <!-- Cetak Laporan: SEMUA PERAN -->
            <li>
                <a href="<?= base_url('report') ?>"
                   class="<?= str_contains(uri_string(), 'report') ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="bi bi-printer-fill"></i></span>
                    Cetak Laporan
                </a>
            </li>
        </ul>

        <!-- Separator + Section -->
        <div class="nav-section">Lainnya</div>
        <ul class="sidebar-nav">
            <!-- Audit Log: SEMUA PERAN -->
            <li>
                <a href="<?= base_url('auditlog') ?>"
                   class="<?= str_contains(uri_string(), 'auditlog') ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="bi bi-clock-history"></i></span>
                    Audit Log
                </a>
            </li>

            <!-- Pengguna: admin SAJA -->
            <?php if ($role === 'admin') : ?>
            <li>
                <a href="<?= base_url('user') ?>"
                   class="<?= str_contains(uri_string(), 'user') ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="bi bi-people-fill"></i></span>
                    Pengguna
                </a>
            </li>
            <?php endif; ?>

            <!-- Profil Saya: SEMUA PERAN -->
            <li>
                <a href="<?= base_url('profile') ?>"
                   class="<?= str_contains(uri_string(), 'profile') ? 'active' : '' ?>">
                    <span class="nav-icon"><i class="bi bi-person-circle"></i></span>
                    Profil Saya
                </a>
            </li>
        </ul>
        </div>

        <!-- Footer sidebar -->
        <div class="sidebar-footer">
            &copy; <?= date('Y') ?> DMS CKDU v1.0
        </div>
    </aside>

    <!-- Overlay untuk mobile (menutup sidebar saat klik di luar) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- ============================================================
         MAIN CONTENT - Area konten utama di sebelah kanan
         ============================================================ -->
    <div class="dms-main">
        <!-- Top Bar -->
        <div class="dms-topbar">
            <div class="d-flex align-items-center gap-3">
                <!-- Tombol toggle sidebar (hanya muncul di mobile) -->
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h1 class="page-title"><?= esc($title ?? 'Dashboard') ?></h1>
                </div>
            </div>
            <div class="topbar-right">
                <!-- Info user dari session (dinamis setelah login) -->
                <?php
                    // Ambil nama user dari session, default 'Guest' jika belum login
                    $namaUser = session()->get('user_nama') ?? 'Guest';
                    // Ambil huruf pertama untuk avatar
                    $inisial = strtoupper(substr($namaUser, 0, 1));
                    
                    // Tentukan tampilan badge role
                    $userRole = session()->get('user_role');
                    $roleBadge = '';
                    if ($userRole === 'admin') {
                        $roleBadge = '<span class="badge bg-primary bg-opacity-10 text-primary" style="font-size:0.7rem; font-weight:600; padding:4px 10px; border-radius:20px;">Admin</span>';
                    } elseif ($userRole === 'hrd') {
                        $roleBadge = '<span class="badge bg-success bg-opacity-10 text-success" style="font-size:0.7rem; font-weight:600; padding:4px 10px; border-radius:20px;">HRD</span>';
                    } elseif ($userRole === 'pimpinan') {
                        $roleBadge = '<span class="badge" style="background-color: #e2e8f0; color: #1e293b; font-size:0.7rem; font-weight:600; padding:4px 10px; border-radius:20px;">Pimpinan</span>';
                    }
                ?>
                <!-- Dropdown user menu -->
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none d-flex align-items-center gap-2 p-0"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false"
                            style="color: var(--dms-text-muted);">
                        <div class="d-none d-md-block text-end pe-2">
                            <div style="font-size:.85rem; font-weight:600; color: var(--dms-dark); line-height:1.2;">
                                <?= esc($namaUser) ?>
                            </div>
                            <div class="mt-1">
                                <?= $roleBadge ?>
                            </div>
                        </div>
                        <div class="user-avatar"><?= $inisial ?></div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="border-radius:10px; box-shadow:0 4px 16px rgba(0,0,0,.1);">
                        <li>
                            <span class="dropdown-item-text">
                                <div style="font-weight:600; color: var(--dms-dark);"><?= esc($namaUser) ?></div>
                                <div class="text-muted mb-2" style="font-size:.85rem;"><?= esc(session()->get('user_email') ?? '') ?></div>
                                <div><?= $roleBadge ?></div>
                            </span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="<?= base_url('auth/logout') ?>">
                                <i class="bi bi-box-arrow-left me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Area Konten - Di sinilah halaman child ditampilkan -->
        <div class="dms-content">
            <!-- ============================================================
                 FLASH MESSAGES (Pesan sukses/error)
                 renderSection('content') di bawah akan diganti oleh konten
                 dari setiap halaman child yang meng-extend layout ini.
                 ============================================================ -->
            <?php if (session()->getFlashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- ============================================================
                 RENDER KONTEN HALAMAN CHILD
                 ============================================================
                 renderSection('content') adalah cara CI4 menampilkan konten
                 dari halaman yang meng-extend layout ini.

                 Analoginya di PHP Native:
                   Layout = header.php + footer.php
                   Content = isi halaman yang di-include di antaranya

                 Di CI4:
                   Layout ini adalah "bingkai" utama
                   Setiap halaman (index, detail, create) hanya perlu menulis
                   bagian kontennya saja di dalam section('content')
                 ============================================================ -->
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- ============================================================
         JavaScript: Bootstrap 5 Bundle (lokal/offline)
         Bundle sudah termasuk Popper.js untuk dropdown/tooltip
         ============================================================ -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- Script toggle sidebar untuk mobile -->
    <script>
        // Toggle sidebar di layar kecil
        const sidebar      = document.getElementById('sidebar');
        const overlay       = document.getElementById('sidebarOverlay');
        const toggleBtn     = document.getElementById('sidebarToggle');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            });
        }
        if (overlay) {
            overlay.addEventListener('click', function () {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
            });
        }
    </script>

    <!-- Section untuk JS tambahan dari halaman child (opsional) -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>
