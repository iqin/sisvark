<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?php if (isset($timeout) && $timeout): ?>
    <div class="alert alert-warning text-center">
        <i class="fas fa-clock me-2"></i>
        <strong>⚠️ Waktu habis!</strong> Jawaban yang sudah diisi tetap tersimpan.
    </div>
<?php endif; ?>
<div class="row justify-content-center mt-4">
    <div class="col-md-8 col-lg-7">
        <div class="card card-shadow">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <div class="bg-success text-white rounded-circle d-inline-flex p-4 mb-3" style="width: 80px; height: 80px; align-items: center; justify-content: center;">
                        <i class="fas fa-check fa-3x"></i>
                    </div>
                    <h3 class="fw-bold">Selamat Datang, <?= esc($nama) ?></h3>
                    <p class="text-muted">Terima kasih telah menyelesaikan test VARK</p>
                </div>

                <hr>

                <div class="my-4">
                    <h5 class="text-muted">Berdasarkan hasil tes Anda cocok dengan gaya belajar:</h5>
                    <h2 class="display-5 fw-bold text-primary"><?= $result['label'] ?></h2>
                    <span class="badge bg-secondary mt-2">Tipe: <?= $result['type'] ?></span>
                </div>

                <div class="row g-2 mb-4">
                    <div class="col-3">
                        <div class="border p-2 rounded">
                            <strong>Visual</strong>
                            <div class="fs-5"><?= $result['scores']['V'] ?></div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="border p-2 rounded">
                            <strong>Aural</strong>
                            <div class="fs-5"><?= $result['scores']['A'] ?></div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="border p-2 rounded">
                            <strong>Read</strong>
                            <div class="fs-5"><?= $result['scores']['R'] ?></div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="border p-2 rounded">
                            <strong>Kinestetik</strong>
                            <div class="fs-5"><?= $result['scores']['K'] ?></div>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                    <a href="<?= base_url('siswa/modul') ?>" class="btn btn-primary-custom">
                        <i class="fas fa-arrow-left me-2"></i> Kembali ke Modul
                    </a>
                    <a href="<?= base_url('zpd/test/1') ?>" class="btn btn-outline-success">
                        <i class="fas fa-flask me-2"></i> Ikut Pretest ZPD
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>