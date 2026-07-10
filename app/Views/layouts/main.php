<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Adaptif VARK' ?></title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-shadow { box-shadow: 0 10px 30px rgba(0,0,0,0.1); border: none; border-radius: 15px; }
        .btn-primary-custom { background: linear-gradient(135deg, #4e73df, #224abe); border: none; color: white; padding: 12px 30px; border-radius: 30px; font-weight: 600; transition: 0.3s; }
        .btn-primary-custom:hover { transform: scale(1.03); color: white; }
        .vark-header { background: white; padding: 20px 0; border-bottom: 1px solid #e3e6f0; }
        /* Style untuk logo di navbar */
        .navbar-brand img {
            height: 35px;
            width: auto;
            object-fit: contain;
            margin-right: 10px;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            color: #4e73df;
            text-decoration: none;
        }
        .navbar-brand:hover {
            color: #224abe;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg vark-header">
        <div class="container">
            <!-- ======== LOGO DENGAN LINK DINAMIS ======== -->
            <?php
            if (session()->get('isLoggedIn')) {
                if (session()->get('role') === 'guru') {
                    $dashboardLink = base_url('guru/dashboard');
                } else {
                    $dashboardLink = base_url('siswa/dashboard');
                }
            } else {
                $dashboardLink = base_url('/');
            }
            ?>
            <a class="navbar-brand" href="<?= $dashboardLink ?>">
                <img src="<?= base_url('assets/images/logo.png') ?>" 
                     alt="Logo Adaptive Learning System">
                ADAPTIVE LEARNING SYSTEM
            </a>

            <!-- Bagian Kanan Navbar -->
            <div class="d-flex">
                <?php if (session()->get('isLoggedIn')): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i> <?= session()->get('name') ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <?php if (session()->get('role') === 'siswa'): ?>
                                <li><a class="dropdown-item" href="<?= base_url('siswa/profil') ?>">
                                    <i class="fas fa-id-card me-2"></i> Profil Saya
                                </a></li>
                            <?php endif; ?>
                            <?php if (session()->get('role') === 'guru'): ?>
                                <li><a class="dropdown-item" href="<?= base_url('guru/dashboard') ?>">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= base_url('login') ?>" class="btn btn-primary btn-sm">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- CONTENT UTAMA -->
    <main class="py-4">
        <div class="container">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>