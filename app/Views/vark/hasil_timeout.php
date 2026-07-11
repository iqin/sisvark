<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card card-shadow text-center p-5">
            <div class="mb-4">
                <div class="bg-danger text-white rounded-circle d-inline-flex p-4 mb-3" style="width: 80px; height: 80px; align-items: center; justify-content: center;">
                    <i class="fas fa-clock fa-3x"></i>
                </div>
                <h3 class="fw-bold text-danger">⏰ Waktu Habis!</h3>
                <p class="text-muted">Maaf, waktu 10 menit telah berakhir. Anda belum menyelesaikan tes VARK.</p>
                <p class="text-muted">Silakan mengerjakan ulang tes.</p>
            </div>
            <a href="<?= base_url('siswa/modul') ?>" class="btn btn-primary-custom">
                <i class="fas fa-arrow-left me-2"></i> Kembali ke Modul
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>