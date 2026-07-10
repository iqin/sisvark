<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-shadow">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold mb-2">📚 Pilih Modul</h3>
                    <p class="text-muted">Pilih modul pembelajaran yang ingin Anda pelajari.</p>
                </div>

                <?php if (!$vark_done): ?>
                    <!-- ========================================================= -->
                    <!-- BELUM VARK -->
                    <!-- ========================================================= -->
                    <div class="alert alert-warning text-center">
                        <i class="fas fa-exclamation-triangle fa-2x d-block mb-2"></i>
                        <h5>Anda belum mengikuti tes VARK!</h5>
                        <p class="mb-0">Tes ini penting untuk mengetahui gaya belajar Anda agar materi yang disajikan sesuai dengan preferensi belajar Anda.</p>
                    </div>
                    <div class="text-center mt-3">
                        <a href="<?= base_url('vark/start') ?>" class="btn btn-primary-custom btn-lg">
                            <i class="fas fa-pencil-alt me-2"></i> Ikuti Tes VARK
                        </a>
                    </div>
                <?php else: ?>
                    <!-- ========================================================= -->
                    <!-- SUDAH VARK, TAMPILKAN 3 MODUL -->
                    <!-- ========================================================= -->
                    <div class="alert alert-success text-center">
                        <i class="fas fa-check-circle me-2"></i>
                        Gaya belajar Anda: 
                        <strong>
                            <?= isset($vark_result['kategori_hasil']) ? esc($vark_result['kategori_hasil']) : 'Teridentifikasi' ?>
                        </strong>
                        <?php if (isset($vark_result['tipe_hasil']) && !empty($vark_result['tipe_hasil'])): ?>
                            <span class="badge bg-primary ms-2">Tipe: <?= esc($vark_result['tipe_hasil']) ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="row mt-4">
                        <?php 
                        $modulNames = [
                            1 => 'Struktur & Fungsi Sel',
                            2 => 'Organel Sel',
                            3 => 'Transpor Membran'
                        ];
                        $modulIcons = [
                            1 => 'fa-microscope',
                            2 => 'fa-dna',
                            3 => 'fa-exchange-alt'
                        ];
                        $modulColors = [
                            1 => 'primary',
                            2 => 'success',
                            3 => 'warning'
                        ];
                        $modulBgColors = [
                            1 => 'bg-primary',
                            2 => 'bg-success',
                            3 => 'bg-warning'
                        ];

                        for ($i = 1; $i <= 3; $i++): 
                            $status = $zpd_status[$i] ?? ['done' => false, 'level' => null, 'score' => null];
                            $isLocked = !$status['done'];
                            $color = $modulColors[$i];
                            $bgColor = $modulBgColors[$i];
                        ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-<?= $isLocked ? 'secondary' : $color ?> shadow-sm">
                                <div class="card-body text-center">
                                    <!-- Ikon -->
                                    <div class="rounded-circle <?= $isLocked ? 'bg-secondary' : $bgColor ?> text-white d-inline-flex align-items-center justify-content-center mb-3" 
                                         style="width: 70px; height: 70px;">
                                        <i class="fas <?= $modulIcons[$i] ?> fa-2x"></i>
                                    </div>

                                    <h5 class="card-title <?= $isLocked ? 'text-muted' : 'text-' . $color ?>">
                                        Modul <?= $i ?>
                                    </h5>
                                    <p class="card-text small text-muted"><?= $modulNames[$i] ?></p>

                                    <?php if ($isLocked): ?>
                                        <!-- ===================================================== -->
                                        <!-- MODUL TERKUNCI (ZPD BELUM DIKERJAKAN) -->
                                        <!-- ===================================================== -->
                                        <div class="mt-2">
                                            <span class="badge bg-secondary mb-2">
                                                <i class="fas fa-lock me-1"></i> Terkunci
                                            </span>
                                        </div>
                                        <p class="text-muted small">
                                            <i class="fas fa-info-circle me-1"></i> 
                                            Kerjakan tes ZPD untuk membuka modul ini.
                                        </p>
                                        <a href="<?= base_url('zpd/test/' . $i) ?>" class="btn btn-outline-warning btn-sm mt-2">
                                            <i class="fas fa-flask me-1"></i> Ikuti Tes ZPD
                                        </a>
                                    <?php else: ?>
                                        <!-- ===================================================== -->
                                        <!-- MODUL TERBUKA (ZPD SUDAH DIKERJAKAN) -->
                                        <!-- ===================================================== -->
                                        <div class="mt-2">
                                            <span class="badge bg-success mb-2">
                                                <i class="fas fa-check-circle me-1"></i> Siap Dipelajari
                                            </span>
                                        </div>
                                        <div class="small text-muted">
                                            <strong>Level ZPD:</strong> 
                                            <?php
                                            $levelLabels = [
                                                'novice' => 'Novice (Pemula)',
                                                'apprentice' => 'Apprentice (Menengah)',
                                                'master' => 'Master (Ahli)'
                                            ];
                                            $levelColors = [
                                                'novice' => 'secondary',
                                                'apprentice' => 'warning',
                                                'master' => 'success'
                                            ];
                                            $level = $status['level'] ?? 'novice';
                                            ?>
                                            <span class="badge bg-<?= $levelColors[$level] ?? 'secondary' ?>">
                                                <?= $levelLabels[$level] ?? ucfirst($level) ?>
                                            </span>
                                            <br>
                                            <span class="text-muted">Skor: <strong><?= $status['score'] ?? 0 ?></strong></span>
                                        </div>
                                        <br>
                                        <a href="<?= base_url('materi/' . $i) ?>" class="btn btn-<?= $color ?> btn-sm text-white">
                                            <i class="fas fa-book me-1"></i> Mulai Belajar
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>