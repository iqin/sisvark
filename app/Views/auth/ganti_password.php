<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-body p-4 p-md-5">

                <!-- HEADER: Judul di kiri, Tombol Kembali di kanan -->
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="fw-bold mb-1">
                            <i class="fas fa-key text-primary me-2"></i>Ganti Password
                        </h4>
                        <p class="text-muted mb-0">Ubah password akun Anda secara berkala untuk keamanan.</p>
                    </div>
                    <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php $errors = session()->getFlashdata('errors'); ?>
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $err): ?>
                                <li><?= esc($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- FORM: Terpusat dengan Border -->
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="form-password-wrapper">
                            <form action="<?= base_url('ganti-password/proses') ?>" method="post" autocomplete="off">
                                <?= csrf_field() ?>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-lock text-muted me-1"></i> Password Lama
                                    </label>
                                    <input type="password" name="kata_sandi_lama"
                                           class="form-control form-control-lg" required
                                           placeholder="Masukkan password saat ini">
                                </div>

                                <hr class="my-4">

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-key text-primary me-1"></i> Password Baru
                                    </label>
                                    <input type="password" name="kata_sandi_baru"
                                           class="form-control form-control-lg" required minlength="6"
                                           placeholder="Minimal 6 karakter">
                                    <small class="text-muted">Gunakan kombinasi huruf dan angka.</small>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-key text-primary me-1"></i> Konfirmasi Password Baru
                                    </label>
                                    <input type="password" name="konfirmasi"
                                           class="form-control form-control-lg" required minlength="6"
                                           placeholder="Ulangi password baru">
                                </div>

                                <!-- TOMBOL SIMPAN: Pendek dan di Tengah -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary-custom btn-simpan-password">
                                        <i class="fas fa-save me-2"></i> Simpan Password Baru
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Custom Style untuk Border Form -->
<style>
.form-password-wrapper {
    background: #fbfcfe;
    border: 2px solid #e3e6f0;
    border-radius: 15px;
    padding: 30px 28px;
    box-shadow: 0 4px 12px rgba(78, 115, 223, 0.06);
    transition: 0.3s;
}
.form-password-wrapper:hover {
    border-color: #4e73df;
    box-shadow: 0 6px 20px rgba(78, 115, 223, 0.12);
}

/* Tombol Simpan Password Baru: pendek + di tengah */
.btn-simpan-password {
    padding: 12px 40px;
    min-width: 220px;
    max-width: 100%;
}

@media (max-width: 576px) {
    .form-password-wrapper {
        padding: 20px 18px;
    }
    .btn-simpan-password {
        width: 100%;
        min-width: unset;
    }
}
</style>

<?= $this->endSection() ?>