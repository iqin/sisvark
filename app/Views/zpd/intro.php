<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center mt-5">
    <div class="col-md-8 col-lg-7">
        <div class="card card-shadow">
            <div class="card-body p-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-warning text-white rounded-circle p-3 me-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-flask fa-2x"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">Tes ZPD</h3>
                        <p class="text-muted mb-0"><?= $modul_name ?></p>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Tujuan:</strong> Tes ini akan mengukur tingkat kemampuan awal Anda pada modul ini.
                </div>

                <div class="mb-4">
                    <h5>📋 Petunjuk:</h5>
                    <ul class="text-muted">
                        <li>Tes ini terdiri dari <strong>10 soal</strong> pilihan ganda.</li>
                        <li>Soal memiliki tingkat kesulitan berbeda (Dasar, Menengah, Lanjut).</li>
                        <li>Waktu pengerjaan <strong>10 menit</strong>.</li>
                        <li>Hasil tes akan menentukan level ZPD Anda: <strong>Novice, Apprentice, atau Master</strong>.</li>
                    </ul>
                </div>

                <div class="text-center">
                    <a href="<?= base_url('zpd/start/' . $modul_id) ?>" class="btn btn-warning btn-lg text-white">
                        <i class="fas fa-play me-2"></i> Mulai Tes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>