<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row align-items-center" style="min-height: 60vh;">
    <!-- Bagian Kiri: Informasi & Kontrol -->
    <div class="col-lg-6">
        <div class="p-4">
            <!-- Identitas Pengguna -->
            <h5 class="text-muted mb-1"><?= session()->get('name') ?></h5>
            
            <!-- Instruksi Pretest -->
            <h1 class="display-5 fw-bold mt-3 mb-4">Silakan Ikut<br>Pretest</h1>
            <p class="lead text-muted mb-4">
                Tes ini akan mengukur tingkat kemampuan awal Anda pada modul <strong><?= $modul_name ?></strong>.
            </p>
            
            <!-- Petunjuk Singkat -->
            <ul class="text-muted mb-4" style="list-style: none; padding-left: 0;">
                <li><i class="fas fa-check-circle text-warning me-2"></i> 10 soal pilihan ganda</li>
                <li><i class="fas fa-check-circle text-warning me-2"></i> Waktu pengerjaan 10 menit</li>
                <li><i class="fas fa-check-circle text-warning me-2"></i> Level: Novice, Apprentice, atau Master</li>
            </ul>

            <!-- Tombol Mulai -->
            <a href="<?= base_url('zpd/start/' . $modul_id) ?>" class="btn btn-warning btn-lg text-white px-5">
                <i class="fas fa-play me-2"></i> Mulai
            </a>
        </div>
    </div>

    <!-- Bagian Kanan: Visual Modul -->
    <div class="col-lg-6 text-center">
        <div class="p-4">
            <img src="<?= base_url('assets/images/modul_' . $modul_id . '.png') ?>" 
                 alt="Gambar Modul <?= $modul_id ?>"
                 class="img-fluid rounded-4 shadow-sm"
                 style="max-height: 350px; object-fit: contain;"
                 onerror="this.src='<?= base_url('assets/images/modul-placeholder.png') ?>'">
            
        </div>
    </div>
</div>
<?= $this->endSection() ?>