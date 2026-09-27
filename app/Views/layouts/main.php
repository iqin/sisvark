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
        .vark-header { background: white; padding: 12px 0; border-bottom: 1px solid #e3e6f0; }

        /* Logo */
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
            flex-shrink: 0;
            white-space: nowrap;
        }
        .navbar-brand:hover { color: #224abe; }
        .brand-text { white-space: nowrap !important; }

        /* === MENU NAVBAR === */
        .navbar-nav {
            gap: 2px;                    /* rapatkan antar item */
            align-items: center;
        }
        .navbar-nav .nav-link {
            color: #555;
            font-weight: 500;
            font-size: 0.9rem;            /* font sedang */
            padding: 6px 10px !important;  /* padding hemat */
            border-radius: 8px;
            transition: 0.2s;
            white-space: nowrap;           /* ANTI WRAP */
            display: inline-flex;
            align-items: center;
        }
        .navbar-nav .nav-link:hover {
            color: #4e73df;
            background-color: rgba(78, 115, 223, 0.08);
        }
        .navbar-nav .nav-link i {
            font-size: 0.85rem;
            margin-right: 5px;
        }

        /* User dropdown button */
        #userDropdown {
            white-space: nowrap;
            padding: 6px 12px;
            font-size: 0.9rem;
        }

        /* === MOBILE === */
        @media (max-width: 991px) {
            .navbar-nav {
                border-top: 1px solid #e3e6f0;
                padding-top: 12px;
                margin-top: 12px;
                align-items: stretch;      /* full width di mobile */
            }
            .navbar-nav .nav-link {
                padding: 10px 12px !important;
                justify-content: flex-start;
            }
        }

        /* HP kecil */
        @media (max-width: 576px) {
            .navbar-brand { font-size: 0.75rem !important; }
            .navbar-brand img { height: 25px !important; margin-right: 5px !important; }
        }
        @media (max-width: 400px) {
            .navbar-brand { font-size: 0.65rem !important; }
            .navbar-brand img { height: 20px !important; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg vark-header">
        <div class="container">

            <?php
            // Link logo dinamis
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

            <!-- Logo -->
            <a class="navbar-brand" href="<?= $dashboardLink ?>">
                <img src="<?= base_url('assets/images/logo.png') ?>"
                     alt="Logo Adaptive Learning System">
                <span class="brand-text">ADAPTIVE LEARNING SYSTEM</span>
            </a>

            <!-- Hamburger -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu & User Dropdown -->
            <div class="collapse navbar-collapse" id="navbarNav">

                <!-- ============================================= -->
                <!-- MENU BERDASARKAN ROLE                          -->
                <!-- ============================================= -->
                <?php if (session()->get('isLoggedIn')): ?>
                    <ul class="navbar-nav ms-lg-4 me-auto mb-2 mb-lg-0">
                        <?php if (is_admin()): ?>
                            <!-- ========== MENU ADMIN ========== -->
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('guru/dashboard') ?>">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('guru/kelas') ?>">
                                    <i class="fas fa-school me-1"></i> Kelas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('guru/vark/soal') ?>">
                                    <i class="fas fa-list-alt me-1"></i> Soal VARK
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('guru/zpd/soal') ?>">
                                    <i class="fas fa-flask me-1"></i> Soal ZPD
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('guru/materi') ?>">
                                    <i class="fas fa-book-open me-1"></i> Materi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('guru/vark/hasil') ?>">
                                    <i class="fas fa-chart-bar me-1"></i> Hasil VARK
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('guru/pesan') ?>">
                                    <i class="fas fa-envelope me-1"></i> Pesan
                                </a>
                            </li>

                        <?php elseif (is_guru()): ?>
                            <!-- ========== MENU GURU BIASA ========== -->
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('guru/dashboard') ?>">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('guru/kelas') ?>">
                                    <i class="fas fa-school me-1"></i> Kelas Saya
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('guru/vark/hasil') ?>">
                                    <i class="fas fa-chart-bar me-1"></i> Hasil VARK
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('guru/pesan') ?>">
                                    <i class="fas fa-envelope me-1"></i> Pesan
                                </a>
                            </li>

                        <?php elseif (is_siswa()): ?>
                            <!-- ========== MENU SISWA ========== -->
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('siswa/modul') ?>">
                                    <i class="fas fa-book me-1"></i> Modul
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('siswa/profil') ?>">
                                    <i class="fas fa-id-card me-1"></i> Profil
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>

                <!-- ============================================= -->
                <!-- USER DROPDOWN                                 -->
                <!-- ============================================= -->
                <div class="ms-auto mt-3 mt-lg-0">
                    <?php if (session()->get('isLoggedIn')): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle d-flex align-items-center w-100 justify-content-center"
                                    type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle me-1"></i>
                                <span class="badge <?= role_badge_class() ?>">
                                    <?= role_label() ?>
                                </span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end w-100" aria-labelledby="userDropdown">
                                <li>
                                    <span class="dropdown-item-text small">
                                        <strong><?= esc(session()->get('name')) ?></strong><br>
                                        <span class="text-muted"> <?= role_label() ?></span>
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>

                                <?php if (is_siswa()): ?>
                                    <li>
                                        <a class="dropdown-item" href="<?= base_url('siswa/profil') ?>">
                                            <i class="fas fa-id-card me-2"></i> Profil Saya
                                        </a>
                                    </li>
                                <?php elseif (is_guru()): ?>
                                    <li>
                                        <a class="dropdown-item" href="<?= base_url('guru/dashboard') ?>">
                                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= base_url('guru/kelas') ?>">
                                            <i class="fas fa-school me-2"></i> Kelas Saya
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <li>
                                    <a class="dropdown-item" href="<?= base_url('ganti-password') ?>">
                                        <i class="fas fa-key me-2"></i> Ganti Password
                                    </a>
                                </li>

                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= base_url('login') ?>" class="btn btn-primary-custom w-100">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                    <?php endif; ?>
                </div>

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