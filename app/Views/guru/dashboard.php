<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <!-- KARTU STATISTIK -->
        <div class="card card-shadow p-4">
            <h2>
                <?= is_admin() ? '👨‍🏫 Halo, Admin' : '👨‍🏫 Halo, Guru' ?>
                <?= session()->get('name') ?>
            </h2>
            <p class="text-muted">
                <?= is_admin() ? 'Dashboard monitoring seluruh sistem.' : 'Dashboard monitoring siswa di kelas Anda.' ?>
            </p>
            <hr>
            
            <div class="row mt-4 g-3">
                <!-- Total Siswa -->
                <div class="col-md-3">
                    <div class="card border-primary p-3 h-100 d-flex flex-column">
                        <h5><i class="fas fa-users text-primary"></i> Total Siswa</h5>
                        <h3><?= $totalSiswa ?? 0 ?></h3>
                        <small class="text-muted">
                            <?= is_admin() ? 'Seluruh siswa terdaftar' : 'Siswa di kelas Anda' ?>
                        </small>
                    </div>
                </div>
                <!-- Tuntas VARK -->
                <div class="col-md-3">
                    <div class="card border-success p-3 h-100 d-flex flex-column">
                        <h5><i class="fas fa-check-circle text-success"></i> Tuntas VARK</h5>
                        <h3><?= $tuntasVark ?? 0 ?></h3>
                        <small class="text-muted">Sudah mengerjakan tes VARK</small>
                    </div>
                </div>
                <!-- Perlu Intervensi -->
                <div class="col-md-3">
                    <div class="card border-danger p-3 h-100 d-flex flex-column">
                        <h5><i class="fas fa-exclamation-triangle text-danger"></i> Perlu Intervensi</h5>
                        <h3><?= $perluIntervensi ?? 0 ?></h3>
                        <small class="text-muted">Hasil Multimodal (gaya belajar campuran)</small>
                    </div>
                </div>
                <!-- Pesan Siswa -->
                <div class="col-md-3">
                    <div class="card border-warning p-3 h-100 d-flex flex-column">
                        <h5><i class="fas fa-envelope text-warning"></i> Pesan Siswa</h5>
                        <div class="d-flex align-items-center gap-2">
                            <h3 class="mb-0"><?= $pesanBaru ?? 0 ?></h3>
                            <?php if (($pesanBaru ?? 0) > 0): ?>
                                <span class="badge bg-danger rounded-pill">Baru</span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted">Pesan belum dibaca</small>
                        <a href="<?= base_url('guru/pesan') ?>" class="btn btn-sm btn-warning mt-2">
                            <i class="fas fa-eye me-1"></i> Lihat
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- MENU KELOLA -->
        <div class="card card-shadow p-4 mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="fw-bold mb-0">
                    <i class="fas fa-cogs text-primary me-2"></i>
                    <?= is_admin() ? 'Menu Kelola' : 'Menu' ?>
                </h4>
                <?php if (!is_admin()): ?>
                    <span class="badge bg-secondary">
                        <i class="fas fa-eye me-1"></i> Mode Lihat
                    </span>
                <?php endif; ?>
            </div>
            <hr>
            <div class="row mt-3 row-cols-1 row-cols-md-4 g-3">

                <!-- 1. Soal VARK -->
                <div class="col">
                    <div class="card border-primary h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-list-ul fa-3x text-primary mb-3"></i>
                            <h5><?= is_admin() ? 'Kelola Soal VARK' : 'Soal VARK' ?></h5>
                            <p class="text-muted small">
                                <?= is_admin() 
                                    ? 'Tambah, edit, atau hapus soal VARK' 
                                    : 'Lihat daftar soal VARK' ?>
                            </p>
                            <a href="<?= base_url('guru/vark/soal') ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-arrow-right"></i>
                                <?= is_admin() ? 'Kelola' : 'Lihat' ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 2. Hasil VARK Siswa -->
                <div class="col">
                    <div class="card border-success h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-bar fa-3x text-success mb-3"></i>
                            <h5>Hasil VARK Siswa</h5>
                            <p class="text-muted small">
                                <?= is_admin() 
                                    ? 'Lihat hasil VARK semua siswa' 
                                    : 'Lihat hasil VARK siswa kelas Anda' ?>
                            </p>
                            <a href="<?= base_url('guru/vark/hasil') ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-arrow-right"></i> Lihat
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 3. Soal ZPD -->
                <div class="col">
                    <div class="card border-warning h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-flask fa-3x text-warning mb-3"></i>
                            <h5><?= is_admin() ? 'Kelola Soal ZPD' : 'Soal ZPD' ?></h5>
                            <p class="text-muted small">
                                <?= is_admin() 
                                    ? 'Kelola soal ZPD per modul' 
                                    : 'Lihat daftar soal ZPD per modul' ?>
                            </p>
                            <a href="<?= base_url('guru/zpd/soal') ?>" class="btn btn-warning btn-sm text-white">
                                <i class="fas fa-arrow-right"></i>
                                <?= is_admin() ? 'Kelola' : 'Lihat' ?>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 4. Materi Adaptif -->
                <div class="col">
                    <div class="card border-secondary h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-cubes fa-3x text-secondary mb-3"></i>
                            <h5><?= is_admin() ? 'Kelola Materi Adaptif' : 'Materi Adaptif' ?></h5>
                            <p class="text-muted small">
                                <?= is_admin() 
                                    ? 'Edit 36 variasi konten adaptif' 
                                    : 'Lihat konten adaptif' ?>
                            </p>
                            <a href="<?= base_url('guru/materi') ?>" class="btn btn-secondary btn-sm text-white">
                                <i class="fas fa-arrow-right"></i>
                                <?= is_admin() ? 'Kelola' : 'Lihat' ?>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>