<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row align-items-center" style="min-height: 60vh;">
    <!-- Bagian Kiri: Informasi & Kontrol -->
    <div class="col-lg-6">
        <div class="p-4">
            <!-- Identitas Pengguna -->
            <h5 class="text-muted mb-1"><?= session()->get('name') ?></h5>
            
            <!-- Instruksi Post Test -->
            <h1 class="display-5 fw-bold mt-3 mb-4">Silakan Ikut<br>Post Test</h1>
            <?php if (isset($is_remedial) && $is_remedial): ?>
                <div class="alert alert-warning p-3 mb-3 rounded-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Remedial!</strong> Anda belum lulus post test modul ini. 
                    Silakan pelajari ulang materi dan kerjakan post test kembali.
                </div>
            <?php endif; ?>
            <p class="lead text-muted mb-4">
                Tes ini akan mengukur pemahaman Anda setelah mempelajari modul <strong><?= $modul_name ?? 'ini' ?></strong>.
            </p>
            
            <!-- Petunjuk Singkat -->
            <ul class="text-muted mb-4" style="list-style: none; padding-left: 0;">
                <li><i class="fas fa-check-circle text-success me-2"></i> 10 soal pilihan ganda</li>
                <li><i class="fas fa-check-circle text-success me-2"></i> Waktu pengerjaan 10 menit</li>
                <li><i class="fas fa-check-circle text-success me-2"></i> Hanya dapat dikerjakan <strong>1 kali</strong></li>
                <li><i class="fas fa-check-circle text-success me-2"></i> Hasil menentukan kelanjutan modul berikutnya</li>
            </ul>

            <!-- Tombol Mulai -->
            <a href="<?= base_url('posttest/start/' . $module_id) ?>" class="btn btn-success btn-lg text-white px-5">
                <i class="fas fa-play me-2"></i> Mulai
            </a>
        </div>
    </div>

    <!-- Bagian Kanan: Visual Modul -->
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