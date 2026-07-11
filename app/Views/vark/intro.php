<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row align-items-center" style="min-height: 60vh;">
    <!-- Bagian Kiri: Informasi & Kontrol -->
    <div class="col-lg-6">
        <div class="p-4">
            <h5 class="text-muted mb-1">Test VARK</h5>
            <h1 class="display-5 fw-bold mt-3 mb-4">Untuk mendeteksi gaya<br>belajar kamu silakan<br>ikuti test ini</h1>
            <p class="lead text-muted mb-4">
                Tes ini akan mengidentifikasi preferensi gaya belajar Anda: Visual, Aural, Read/Write, atau Kinestetik.
            </p>
            <ul class="text-muted mb-4" style="list-style: none; padding-left: 0;">
                <li><i class="fas fa-check-circle text-primary me-2"></i> 10 soal pilihan ganda</li>
                <li><i class="fas fa-check-circle text-primary me-2"></i> Waktu pengerjaan 10 menit</li>
                <li><i class="fas fa-check-circle text-primary me-2"></i> Tidak ada jawaban benar/salah</li>
            </ul>
            <a href="<?= base_url('vark/start') ?>" class="btn btn-primary btn-lg text-white px-5">
                <i class="fas fa-play me-2"></i> Start
            </a>
        </div>
    </div>
    <!-- Bagian Kanan: Ikon VARK -->
    <div class="col-lg-6 text-center">
        <div class="p-4">
            <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 150px; height: 150px;">
                <i class="fas fa-pencil-alt fa-5x"></i>
            </div>
            <p class="text-muted mt-3 small">Tes VARK</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>