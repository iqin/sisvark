<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-shadow">
            <div class="card-body p-5 text-center">
                <h3 class="fw-bold mb-4">📚 Pilih Modul</h3>
                <p class="text-muted">Pilih modul pembelajaran yang ingin Anda pelajari.</p>

                <?php if (!$vark_done): ?>
                    <!-- Belum VARK -->
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Anda belum mengikuti tes VARK. 
                        Tes ini penting untuk mengetahui gaya belajar Anda.
                    </div>
                    <a href="<?= base_url('vark/start') ?>" class="btn btn-primary-custom">
                        <i class="fas fa-pencil-alt me-2"></i> Ikuti Tes VARK
                    </a>
                <?php elseif (!$zpd_mod1_done): ?>
                    <!-- Sudah VARK, tapi belum ZPD Modul 1 -->
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Anda belum mengikuti tes ZPD untuk Modul 1.
                        Tes ini akan menentukan level kemampuan awal Anda.
                    </div>
                    <a href="<?= base_url('zpd/test/1') ?>" class="btn btn-primary-custom">
                        <i class="fas fa-flask me-2"></i> Ikuti Tes ZPD Modul 1
                    </a>
                <?php else: ?>
                    <!-- Sudah VARK dan ZPD Modul 1 → tampilkan 3 modul -->
                    <div class="row mt-4">
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-primary">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Modul 1</h5>
                                    <p class="card-text">Struktur & Fungsi Sel</p>
                                    <a href="<?= base_url('materi/1') ?>" class="btn btn-outline-primary btn-sm">
                                        Mulai <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-success">
                                <div class="card-body">
                                    <h5 class="card-title text-success">Modul 2</h5>
                                    <p class="card-text">Organel Sel</p>
                                    <a href="<?= base_url('materi/2') ?>" class="btn btn-outline-success btn-sm">
                                        Mulai <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-warning">
                                <div class="card-body">
                                    <h5 class="card-title text-warning">Modul 3</h5>
                                    <p class="card-text">Transpor Membran</p>
                                    <a href="<?= base_url('materi/3') ?>" class="btn btn-outline-warning btn-sm">
                                        Mulai <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>