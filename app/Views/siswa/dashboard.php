<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-shadow p-4">
            <h2>👋 Halo, <?= session()->get('name') ?></h2>
            <p class="text-muted">Selamat datang di dashboard siswa.</p>
            <hr>
            <div class="row mt-4">
                <!-- KARTU TES VARK -->
                <div class="col-md-4">
                    <div class="card bg-primary text-white p-3 h-100">
                        <h5><i class="fas fa-pencil-alt"></i> Tes VARK</h5>
                        <?php if (isset($varkResult) && $varkResult !== null): ?>
                            <p class="mb-1">✅ Anda sudah mengerjakan tes VARK.</p>
                            <p class="mb-0"><strong>Gaya Belajar:</strong> <?= esc($varkResult['kategori_hasil']) ?></p>
                            <!-- Tombol Mulai tidak ditampilkan -->
                        <?php else: ?>
                            <p>Cari tahu gaya belajarmu!</p>
                            <a href="<?= base_url('vark/start') ?>" class="btn btn-light btn-sm">Mulai</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- KARTU TES ZPD -->
                <div class="col-md-4">
                    <div class="card bg-success text-white p-3 h-100">
                        <h5><i class="fas fa-flask"></i> Tes ZPD</h5>
                        <?php if (isset($zpdMod1) && $zpdMod1 !== null): ?>
                            <p class="mb-1">✅ Anda sudah mengerjakan ZPD Modul 1.</p>
                            <p class="mb-0"><strong>Level:</strong> <?= esc($zpdMod1['level_zpd']) ?></p>
                            <!-- Tombol Mulai tidak ditampilkan -->
                        <?php else: ?>
                            <p>Ukur level kemampuanmu.</p>
                            <a href="<?= base_url('zpd/test/1') ?>" class="btn btn-light btn-sm">Mulai</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- KARTU MODUL BELAJAR -->
                <div class="col-md-4">
                    <div class="card bg-warning text-dark p-3 h-100">
                        <h5><i class="fas fa-book"></i> Modul Belajar</h5>
                        <p>Mulai pembelajaran adaptif.</p>
                        <a href="<?= base_url('siswa/modul') ?>" class="btn btn-light btn-sm">
                            <i class="fas fa-book me-1"></i> Lihat Modul
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>