<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center mt-4">
    <div class="col-md-8 col-lg-7">
        <div class="card card-shadow">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <?php if (isset($is_timeout) && $is_timeout): ?>
                        <div class="bg-warning text-white rounded-circle d-inline-flex p-4 mb-3" style="width: 80px; height: 80px; align-items: center; justify-content: center;">
                            <i class="fas fa-clock fa-3x"></i>
                        </div>
                        <h3 class="fw-bold text-warning">⏰ Waktu Habis!</h3>
                        <p class="text-muted">Jawaban yang sudah diisi tetap tersimpan.</p>
                    <?php else: ?>
                        <div class="bg-success text-white rounded-circle d-inline-flex p-4 mb-3" style="width: 80px; height: 80px; align-items: center; justify-content: center;">
                            <i class="fas fa-check fa-3x"></i>
                        </div>
                        <h3 class="fw-bold">Selamat, <?= esc($nama) ?></h3>
                        <p class="text-muted">Anda telah menyelesaikan tes ZPD Modul <?= $module_id ?></p>
                    <?php endif; ?>
                </div>

                <hr>

                <div class="my-4">
                    <h5 class="text-muted">Skor Anda:</h5>
                    <h2 class="display-4 fw-bold text-primary"><?= $score ?></h2>
                    <p class="text-muted">Dari maksimal 100</p>
                    <p class="text-muted">Jumlah soal dijawab: <strong><?= $answered ?? 0 ?></strong> dari 10</p>
                </div>

                <div class="my-4">
                    <h5 class="text-muted">Level ZPD Anda:</h5>
                    <?php
                    $levelColors = ['novice' => 'secondary', 'apprentice' => 'warning', 'master' => 'success'];
                    $levelLabels = ['novice' => 'Novice (Pemula)', 'apprentice' => 'Apprentice (Menengah)', 'master' => 'Master (Ahli)'];
                    ?>
                    <h3 class="fw-bold text-<?= $levelColors[$level] ?? 'secondary' ?>">
                        <?= $levelLabels[$level] ?? ucfirst($level) ?>
                    </h3>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="<?= base_url('siswa/modul') ?>" class="btn btn-primary-custom">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Modul
                    </a>
                    <a href="<?= base_url('materi/' . $module_id) ?>" class="btn btn-success">
                        <i class="fas fa-book me-2"></i> Mulai Belajar Modul <?= $module_id ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>