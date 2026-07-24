<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - Sistem Manajemen Dokumen PT. Cipta Karya Dharma Utama">
    <title>Login DMS PT. CKDU</title>

    <!-- Bootstrap 5 CSS (offline) -->
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <!-- ============================================================
         LOGIN WRAPPER - Full-screen background dengan efek glassmorphism
         ============================================================ -->
    <div class="login-wrapper">
        <div class="login-card animate-in">
            <!-- Logo -->
             <div class="mb-3 text-center">
                <img src="<?= base_url('assets/img/logockdutransparan.png') ?>" alt="Logo PT CKDU" style="max-width: 130px; height: auto;">
            </div>

            <h3>Selamat Datang</h3>
            <p class="login-subtitle">Sistem Manajemen Dokumen <br> PT. Cipta Karya Dharma Utama</p>

            <!-- Flash messages -->
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show py-2 px-3" role="alert" style="font-size:.85rem; border-radius:10px;">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" style="font-size:.6rem;"></button>
                </div>
            <?php endif; ?>

            <!-- Form Login -->
            <!--
                form_open() = buat tag <form> dengan CSRF token otomatis
                Action mengarah ke 'auth/login' (akan diproses di iterasi Auth nanti)
                Untuk saat ini form ini hanya tampilan statis.
            -->
            <form action="<?= base_url('auth/login') ?>" method="POST">
                <?= csrf_field() ?>

                <!-- Input Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope-fill me-1"></i> Email
                    </label>
                    <input type="email" class="form-control" id="email" name="email"
                           placeholder="admin@ckdu.com" required autocomplete="email">
                </div>

                <!-- Input Password -->
                <div class="mb-4">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock-fill me-1"></i> Password
                    </label>
                    <div class="position-relative">
                        <input type="password" class="form-control" id="password" name="password"
                               placeholder="Masukkan password" required autocomplete="current-password">
                        <button type="button" class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                                onclick="togglePassword()" style="color:rgba(255,255,255,.5); text-decoration:none;">
                            <i class="bi bi-eye-fill" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Tombol Login -->
                <button type="submit" class="btn btn-login mb-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Masuk ke Sistem
                </button>

                <!-- Info default login -->
                <div class="text-center" style="margin-top:20px;">
                    <small style="color:rgba(255,255,255,.25); font-size:.75rem;">
                        <!-- Demo: admin@ckdu.com / password -->
                    </small>
                </div>
            </form>
        </div>

        <!-- Company branding di bawah -->
        <div class="position-absolute bottom-0 w-100 text-center pb-3" style="z-index:2;">
            <small style="color:rgba(255,255,255,.2); font-size:.7rem;">
                &copy; <?= date('Y') ?> PT. Cipta Karya Dharma Utama - All Rights Reserved
            </small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- Toggle show/hide password -->
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash-fill';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye-fill';
            }
        }
    </script>
</body>
</html>
