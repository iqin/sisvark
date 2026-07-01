<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6">
        <div class="card card-shadow text-center p-5">
            <div class="mb-4">
                <!-- Ikon Clock -->
                <div class="bg-danger text-white rounded-circle d-inline-flex p-4 mb-3" 
                     style="width: 80px; height: 80px; align-items: center; justify-content: center;">
                    <i class="fas fa-clock fa-3x"></i>
                </div>
                
                <h3 class="fw-bold text-danger">⏰ Waktu Habis!</h3>
                <p class="text-muted">Maaf, waktu 10 menit telah berakhir. Anda belum menyelesaikan tes VARK.</p>
                <p class="text-muted">Silakan hubungi guru untuk mendapatkan kesempatan mengulang.</p>
            </div>
            
            <hr>
            
            <div class="mt-3">
                <a href="<?= base_url('siswa/modul') ?>" class="btn btn-primary-custom">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Modul
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .card-shadow {
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border: none;
        border-radius: 15px;
    }
    .btn-primary-custom {
        background: linear-gradient(135deg, #4e73df, #224abe);
        border: none;
        color: white;
        padding: 12px 30px;
        border-radius: 30px;
        font-weight: 600;
        transition: 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-primary-custom:hover {
        transform: scale(1.03);
        color: white;
        background: linear-gradient(135deg, #224abe, #4e73df);
    }
</style>
<?= $this->endSection() ?>