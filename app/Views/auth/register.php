<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center mt-4">
    <div class="col-md-8 col-lg-6">
        <div class="card card-shadow">
            <div class="card-body p-5">
                <h3 class="text-center fw-bold mb-4">📝 Daftar Akun</h3>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('register/process') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password (min 6 karakter)</label>
                        <input type="password" name="kata_sandi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Peran</label>
                        <select name="peran" class="form-select" required>
                            <option value="siswa">Siswa</option>
                            <option value="guru">Guru</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sekolah</label>
                        <input type="text" name="sekolah" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <input type="text" name="kelas" class="form-control" placeholder="Contoh: XI MIPA 1">
                    </div>
                    <button type="submit" class="btn btn-primary-custom w-100">Daftar</button>
                </form>
                <p class="text-center mt-3">Sudah punya akun? <a href="<?= base_url('login') ?>" class="fw-bold text-primary">Login</a></p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>