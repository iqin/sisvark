<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center mt-4">
    <div class="col-md-8 col-lg-7">
        <div class="card card-shadow">
            <div class="card-body p-5 text-center">
                <div class="mb-4">
                    <!-- Ikon Sukses -->
                    <div class="bg-success text-white rounded-circle d-inline-flex p-4 mb-3" 
                         style="width: 80px; height: 80px; align-items: center; justify-content: center;">
                        <i class="fas fa-check fa-3x"></i>
                    </div>
                    
                    <h3 class="fw-bold">Selamat Datang, <?= esc($nama) ?></h3>
                    <p class="text-muted">Terima kasih telah menyelesaikan test VARK</p>
                </div>

                <hr>

                <div class="my-4">
                    <h5 class="text-muted">Berdasarkan hasil tes Anda cocok dengan gaya belajar:</h5>
                    <h2 class="display-5 fw-bold text-primary mt-2"><?= $result['label'] ?></h2>
                    <span class="badge bg-secondary mt-2">Tipe: <?= $result['type'] ?></span>
                </div>

                <!-- Tombol Navigasi -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                    <a href="<?= base_url('siswa/modul') ?>" class="btn btn-secondary px-4 py-2">
                        <i class="fas fa-arrow-left me-2"></i> Keluar
                    </a>
                    <a href="<?= base_url('zpd/test/1') ?>" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-flask me-2"></i> Ikut Pretest
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>