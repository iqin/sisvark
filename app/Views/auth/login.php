<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-5">
        <div class="card card-shadow">
            <div class="card-body p-5">
                <h3 class="text-center fw-bold mb-4">🔐 Login</h3>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('login/process') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="******" required>
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100">Login</button>
                </form>
                <p class="text-center mt-3">Belum punya akun? <a href="<?= base_url('register') ?>" class="fw-bold text-primary">Daftar</a></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>