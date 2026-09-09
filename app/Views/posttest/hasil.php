<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center mt-4">
    <div class="col-md-8 col-lg-7">
        <div class="card card-shadow">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <?php if ($status == 'lulus'): ?>
                        <div class="bg-success text-white rounded-circle d-inline-flex p-4 mb-3" style="width: 80px; height: 80px; align-items: center; justify-content: center;">
                            <i class="fas fa-check fa-3x"></i>
                        </div>
                        <h3 class="fw-bold text-success">🎉 Selamat!</h3>
                        <p class="text-muted">Anda telah menyelesaikan post test Modul <?= $module_id ?></p>
                    <?php else: ?>
                        <div class="bg-danger text-white rounded-circle d-inline-flex p-4 mb-3" style="width: 80px; height: 80px; align-items: center; justify-content: center;">
                            <i class="fas fa-times fa-3x"></i>
                        </div>
                        <h3 class="fw-bold text-danger">⚠️ Belum Lulus</h3>
                        <p class="text-muted">Anda perlu remedial untuk Modul <?= $module_id ?></p>
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
                    <div class="row">
                        <div class="col-6">
                            <h5 class="text-muted">Level Awal</h5>
                            <?php
                            $levelLabels = ['novice' => 'Novice', 'apprentice' => 'Apprentice', 'master' => 'Master'];
                            $levelColors = ['novice' => 'secondary', 'apprentice' => 'warning', 'master' => 'success'];
                            ?>
                            <span class="badge bg-<?= $levelColors[$level_awal] ?? 'secondary' ?> fs-5">
                                <?= $levelLabels[$level_awal] ?? ucfirst($level_awal) ?>
                            </span>
                        </div>
                        <div class="col-6">
                            <h5 class="text-muted">Level Akhir</h5>
                            <span class="badge bg-<?= $levelColors[$level_akhir] ?? 'secondary' ?> fs-5">
                                <?= $levelLabels[$level_akhir] ?? ucfirst($level_akhir) ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="my-4">
                    <?php if ($status == 'lulus'): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-arrow-up me-2"></i>
                            Level Anda <strong><?= ($level_order[$level_akhir] > $level_order[$level_awal]) ? 'naik' : 'tetap' ?></strong>.
                            Anda dapat melanjutkan ke modul berikutnya!
                        </div>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-arrow-down me-2"></i>
                            Level Anda menurun. Anda <strong>belum berhak</strong> melanjutkan ke modul berikutnya.
                            Silakan hubungi guru untuk intervensi remedial.
                        </div>
                    <?php endif; ?>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="<?= base_url('siswa/modul') ?>" class="btn btn-primary-custom">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Modul
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>