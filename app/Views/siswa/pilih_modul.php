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
                    <!-- BELUM VARK -->
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
                    <!-- SUDAH VARK, TAMPILKAN 3 MODUL -->
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

                        // =============================================================
                        // AMBIL LEVEL ZPD DARI MODUL 1 (BERLAKU UNTUK SEMUA MODUL)
                        // =============================================================
                        $levelModul1 = $zpd_status[1]['level'] ?? 'novice';
                        $skorModul1 = $zpd_status[1]['score'] ?? 0;
                        $modul1Done = $zpd_status[1]['done'] ?? false;
                        $modul2Done = $zpd_status[2]['done'] ?? false;
                        $modul3Done = $zpd_status[3]['done'] ?? false;

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

                        // =============================================================
                        // AMBIL STATUS POST TEST UNTUK 3 MODUL
                        // =============================================================
                        $post1Done = $post_test_status[1]['done'] ?? false;
                        $post2Done = $post_test_status[2]['done'] ?? false;
                        $post3Done = $post_test_status[3]['done'] ?? false;

                        for ($i = 1; $i <= 3; $i++): 
                            $color = $modulColors[$i];
                            $bgColor = $modulBgColors[$i];
                            $imgPath = base_url('assets/images/modul_' . $i . '.png');
                            
                            // =============================================================
                            // AMBIL DATA ZPD
                            // =============================================================
                            $zpdDone = $zpd_status[$i]['done'] ?? false;
                            $zpdLevel = $zpd_status[$i]['level'] ?? 'novice';
                            $zpdScore = $zpd_status[$i]['score'] ?? 0;
                            
                            // =============================================================
                            // AMBIL DATA POST TEST
                            // =============================================================
                            $postDone = $post_test_status[$i]['done'] ?? false;
                            $postLulus = $post_test_status[$i]['lulus'] ?? false;
                            $postScore = $post_test_status[$i]['score'] ?? 0;
                            $postLevelAkhir = $post_test_status[$i]['level_akhir'] ?? null;
                            
                            // =============================================================
                            // AMBIL LEVEL DARI MODUL SEBELUMNYA (UNTUK MODUL 2 & 3)
                            // =============================================================
                            if ($i > 1) {
                                $prevData = $post_test_status[$i-1];
                                $prevLevel = $prevData['level_akhir'] ?? $zpd_status[$i-1]['level'] ?? 'novice';
                            } else {
                                $prevLevel = 'novice';
                            }
                            
                            // =============================================================
                            // TENTUKAN STATUS MODUL
                            // =============================================================
                            $isCompleted = $postLulus; // Selesai hanya jika lulus
                            $isRemedial = $postDone && !$postLulus; // Remedial jika sudah post test tapi tidak lulus
                            
                            // LOGIKA TERKUNCI
                            if ($i == 1) {
                                $isLocked = !$zpdDone;
                            } else {
                                $prevIndex = $i - 1;
                                $prevLulus = $post_test_status[$prevIndex]['lulus'] ?? false;
                                $isLocked = !$prevLulus;
                            }
                            
                            $canAccess = !$isLocked && !$isCompleted;
                            
                            // =============================================================
                            // TENTUKAN LEVEL DAN SKOR YANG DITAMPILKAN
                            // =============================================================
                            if ($isCompleted || $isRemedial) {
                                // Modul sudah ada post test: gunakan level_akhir dari post test
                                $displayLevel = $postLevelAkhir ?: $zpdLevel;
                                $displayScore = $postScore;
                            } else {
                                // Modul belum dikerjakan
                                if ($i == 1) {
                                    // Modul 1: gunakan data ZPD
                                    $displayLevel = $zpdLevel;
                                    $displayScore = $zpdScore;
                                } else {
                                    // Modul 2 & 3: gunakan level dari modul sebelumnya
                                    $displayLevel = $prevLevel;
                                    $displayScore = 0;
                                }
                            }
                        ?>
                        <div class="col-md-4 mb-3">
                            <div class="card h-100 border-<?= ($isLocked || $isCompleted || $isRemedial) ? 'secondary' : $color ?> shadow-sm hover-card">
                                <div class="card-body text-center">
                                    <!-- Gambar Modul -->
                                    <div class="modul-icon-container mb-3">
                                        <img src="<?= $imgPath ?>" 
                                            alt="Modul <?= $i ?>"
                                            class="modul-icon"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="modul-icon-fallback <?= ($isLocked || $isCompleted) ? 'bg-secondary' : ($isRemedial ? 'bg-danger' : $bgColor) ?>">
                                            <i class="fas <?= $isLocked ? 'fa-lock' : ($isCompleted ? 'fa-check' : ($isRemedial ? 'fa-exclamation-triangle' : 'fa-book-open')) ?> fa-2x text-white"></i>
                                        </div>
                                    </div>

                                    <h5 class="card-title <?= ($isLocked || $isCompleted || $isRemedial) ? 'text-muted' : 'text-' . $color ?>">
                                        Modul <?= $i ?>
                                    </h5>
                                    <p class="card-text small text-muted mb-2"><?= $modulNames[$i] ?></p>

                                    <?php if ($isRemedial): ?>
                                        <!-- ===================================================== -->
                                        <!-- MODUL REMEDIAL -->
                                        <!-- ===================================================== -->
                                        <span class="badge bg-danger mb-2">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Remedial
                                        </span>
                                        <div class="small text-muted mb-2">
                                            <strong>Level ZPD:</strong> 
                                            <span class="badge bg-<?= $levelColors[$displayLevel] ?? 'secondary' ?>">
                                                <?= $levelLabels[$displayLevel] ?? ucfirst($displayLevel) ?>
                                            </span>
                                            <br>
                                            <span class="text-danger">Skor: <strong><?= $displayScore ?></strong> (Tidak Lulus)</span>
                                        </div>
                                        <a href="<?= base_url('materi/' . $i) ?>" class="btn btn-danger btn-sm">
                                            <i class="fas fa-redo me-1"></i> Belajar Ulang
                                        </a>

                                    <?php elseif ($isLocked): ?>
                                        <!-- ===================================================== -->
                                        <!-- MODUL TERKUNCI -->
                                        <!-- ===================================================== -->
                                        <span class="badge bg-secondary mb-2">
                                            <i class="fas fa-lock me-1"></i> Terkunci
                                        </span>
                                        <p class="text-muted small mb-2">
                                            <i class="fas fa-info-circle me-1"></i> 
                                            <?php if ($i == 1): ?>
                                                Kerjakan tes ZPD untuk membuka modul ini.
                                            <?php else: ?>
                                                Selesaikan modul sebelumnya terlebih dahulu.
                                            <?php endif; ?>
                                        </p>
                                        <?php if ($i == 1): ?>
                                            <a href="<?= base_url('zpd/test/' . $i) ?>" class="btn btn-outline-warning btn-sm">
                                                <i class="fas fa-flask me-1"></i> Ikuti Tes ZPD
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="fas fa-lock me-1"></i> Terkunci
                                            </button>
                                        <?php endif; ?>

                                    <?php elseif ($isCompleted): ?>
                                        <!-- ===================================================== -->
                                        <!-- MODUL LULUS (SELESAI) -->
                                        <!-- ===================================================== -->
                                        <span class="badge bg-secondary mb-2">
                                            <i class="fas fa-check me-1"></i> Selesai
                                        </span>
                                        <div class="small text-muted mb-2">
                                            <strong>Level ZPD:</strong> 
                                            <span class="badge bg-<?= $levelColors[$displayLevel] ?? 'secondary' ?>">
                                                <?= $levelLabels[$displayLevel] ?? ucfirst($displayLevel) ?>
                                            </span>
                                            <br>
                                            <span class="text-muted">Skor: <strong><?= $displayScore ?></strong></span>
                                        </div>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-check me-1"></i> Selesai
                                        </button>

                                    <?php elseif ($canAccess): ?>
                                        <!-- ===================================================== -->
                                        <!-- MODUL SIAP DIPELAJARI -->
                                        <!-- ===================================================== -->
                                        <span class="badge bg-success mb-2">
                                            <i class="fas fa-check-circle me-1"></i> Siap Dipelajari
                                        </span>
                                        <div class="small text-muted mb-2">
                                            <strong>Level ZPD:</strong> 
                                            <span class="badge bg-<?= $levelColors[$displayLevel] ?? 'secondary' ?>">
                                                <?= $levelLabels[$displayLevel] ?? ucfirst($displayLevel) ?>
                                            </span>
                                            <br>
                                            <span class="text-muted">Skor: <strong><?= $displayScore ?></strong></span>
                                        </div>
                                        <a href="<?= base_url('materi/' . $i) ?>" class="btn btn-<?= $color ?> btn-sm text-white">
                                            <i class="fas fa-book me-1"></i> Mulai Belajar
                                        </a>

                                    <?php else: ?>
                                        <!-- FALLBACK -->
                                        <span class="badge bg-secondary mb-2">
                                            <i class="fas fa-question me-1"></i> Unknown
                                        </span>
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-question me-1"></i> Unknown
                                        </button>
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

<style>
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.15);
}
.modul-icon-container {
    position: relative;
    width: 100%;
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.modul-icon {
    max-height: 90px;
    max-width: 90px;
    object-fit: contain;
    border-radius: 8px;
}
.modul-icon-fallback {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: none;
    align-items: center;
    justify-content: center;
}
</style>
<?= $this->endSection() ?>