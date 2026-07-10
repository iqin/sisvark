<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row align-items-center" style="min-height: 60vh;">
    <!-- Bagian Kiri: Informasi Hasil -->
    <div class="col-lg-6">
        <div class="p-4">
            <!-- Judul Halaman -->
            <h4 class="text-muted mb-3">Skor Pretest Kamu</h4>

            <!-- Status (success / timeout) -->
            <?php if (isset($is_timeout) && $is_timeout): ?>
                <div class="alert alert-warning d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-clock fa-lg"></i>
                    <span>Waktu habis! Jawaban yang sudah diisi tetap tersimpan.</span>
                </div>
            <?php endif; ?>

            <!-- Informasi Nilai -->
            <div class="mb-4">
                <h5 class="text-muted">Nilai</h5>
                <h2 class="display-3 fw-bold text-primary"><?= $score ?></h2>
                <p class="text-muted">Dari maksimal 100</p>
                <p class="text-muted">Jumlah soal dijawab: <strong><?= $answered ?? 0 ?></strong> dari 10</p>
            </div>

            <!-- Informasi Level ZPD -->
            <div class="mb-4">
                <h5 class="text-muted">ZPD</h5>
                <?php
                $levelColors = ['novice' => 'secondary', 'apprentice' => 'warning', 'master' => 'success'];
                $levelLabels = ['novice' => 'Novice (Pemula)', 'apprentice' => 'Apprentice (Menengah)', 'master' => 'Master (Ahli)'];
                $level = $level ?? 'novice';
                ?>
                <h3 class="fw-bold text-<?= $levelColors[$level] ?? 'secondary' ?>">
                    <?= $levelLabels[$level] ?? ucfirst($level) ?>
                </h3>
            </div>

            <!-- Tombol Navigasi -->
            <div class="d-flex flex-wrap gap-3 mt-4">
                <a href="<?= base_url('siswa/modul') ?>" class="btn btn-secondary px-4 py-2">
                    <i class="fas fa-arrow-left me-2"></i> Back
                </a>
                <a href="<?= base_url('materi/' . $module_id) ?>" class="btn btn-primary px-4 py-2">
                    Materi <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Bagian Kanan: Gambar Modul -->
    <div class="col-lg-6 text-center">
        <div class="p-4">
            <img src="<?= base_url('assets/images/modul_' . $module_id . '.png') ?>" 
                 alt="Gambar Modul <?= $module_id ?>"
                 class="img-fluid rounded-4 shadow-sm"
                 style="max-height: 350px; object-fit: contain;"
                 onerror="this.src='<?= base_url('assets/images/modul-placeholder.png') ?>'">
            <p class="text-muted mt-3 small">Gambar representatif Modul <?= $module_id ?></p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>