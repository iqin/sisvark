<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <h3 class="fw-bold mb-2">📝 Daftar Akun Siswa</h3>
                    <p class="text-muted">Isi data berikut untuk membuat akun baru.</p>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
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

                <form action="<?= base_url('register/process') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" 
                               value="<?= old('nama') ?>" required minlength="3" maxlength="100">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?= old('email') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="kata_sandi" class="form-control" 
                               required minlength="6">
                        <small class="text-muted">Minimal 6 karakter.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih Sekolah</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Sekolah --</option>
                            <?php foreach ($kelas_list as $k): ?>
                                <option value="<?= $k['id'] ?>" 
                                    <?= old('kelas_id') == $k['id'] ? 'selected' : '' ?>>
                                    <?= esc($k['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Pilih sekolah tempat Anda belajar.</small>
                    </div>

                    <button type="submit" class="btn btn-primary-custom w-100 mb-2">
                        <i class="fas fa-user-plus me-2"></i> Daftar
                    </button>
                </form>

                <hr class="my-3">
                <p class="text-center mb-0">
                    Sudah punya akun? 
                    <a href="<?= base_url('login') ?>" class="fw-semibold">Login di sini</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>