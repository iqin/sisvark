<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <!-- KARTU STATISTIK -->
        <div class="card card-shadow p-4">
            <h2>👨‍🏫 Halo, Guru <?= session()->get('name') ?></h2>
            <p class="text-muted">Dashboard monitoring siswa.</p>
            <hr>
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card border-primary p-3">
                        <h5><i class="fas fa-users text-primary"></i> Total Siswa</h5>
                        <h3><?= $totalSiswa ?? 0 ?></h3>
                        <small class="text-muted">Seluruh siswa terdaftar</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-success p-3">
                        <h5><i class="fas fa-check-circle text-success"></i> Tuntas VARK</h5>
                        <h3><?= $tuntasVark ?? 0 ?></h3>
                        <small class="text-muted">Sudah mengerjakan tes VARK</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-danger p-3">
                        <h5><i class="fas fa-exclamation-triangle text-danger"></i> Perlu Intervensi</h5>
                        <h3><?= $perluIntervensi ?? 0 ?></h3>
                        <small class="text-muted">Hasil Multimodal (gaya belajar campuran)</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- MENU KELOLA (4 KOLOM) -->
        <div class="card card-shadow p-4 mt-4">
            <h4 class="fw-bold mb-3"><i class="fas fa-cogs text-primary me-2"></i>Menu Kelola</h4>
            <hr>
            <div class="row mt-3 row-cols-1 row-cols-md-4 g-3">
                <!-- 1. Kelola Soal VARK -->
                <div class="col">
                    <div class="card border-primary h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-list-ul fa-3x text-primary mb-3"></i>
                            <h5>Kelola Soal VARK</h5>
                            <p class="text-muted small">Tambah, edit, atau hapus soal VARK</p>
                            <a href="<?= base_url('guru/vark/soal') ?>" class="btn btn-primary btn-sm">
                                <i class="fas fa-arrow-right"></i> Kelola
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
                            <p class="text-muted small">Lihat gaya belajar siswa</p>
                            <a href="<?= base_url('guru/vark/hasil') ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-arrow-right"></i> Lihat
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 3. Kelola Soal ZPD -->
                <div class="col">
                    <div class="card border-warning h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-flask fa-3x text-warning mb-3"></i>
                            <h5>Kelola Soal ZPD</h5>
                            <p class="text-muted small">Kelola soal ZPD per modul</p>
                            <a href="<?= base_url('guru/zpd/soal') ?>" class="btn btn-warning btn-sm text-white">
                                <i class="fas fa-arrow-right"></i> Kelola
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 4. Kelola Materi Adaptif -->
                <div class="col">
                    <div class="card border-secondary h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-cubes fa-3x text-secondary mb-3"></i>
                            <h5>Kelola Materi Adaptif</h5>
                            <p class="text-muted small">Edit 36 variasi konten adaptif</p>
                            <a href="<?= base_url('guru/materi') ?>" class="btn btn-secondary btn-sm text-white">
                                <i class="fas fa-arrow-right"></i> Kelola
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>