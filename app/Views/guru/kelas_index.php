<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-11">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">🏫 Daftar Kelas</h3>
                <p class="text-muted mb-0">
                    <?= is_admin() ? 'Kelola semua kelas sekolah.' : 'Kelas yang Anda ampu.' ?>
                </p>
            </div>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (empty($kelas)): ?>
            <div class="alert alert-info text-center">
                <i class="fas fa-info-circle me-2"></i>
                Belum ada kelas. Hubungi admin untuk informasi lebih lanjut.
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($kelas as $k): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card card-shadow h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold mb-0 text-primary">
                                        <?= esc($k['nama']) ?>
                                    </h5>
                                    <?php if ($k['is_active']): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Arsip</span>
                                    <?php endif; ?>
                                </div>

                                <p class="text-muted small mb-3">
                                    <i class="fas fa-book me-1"></i>
                                    <?= esc($k['mata_pelajaran']) ?>
                                    <?php if (!empty($k['tahun_ajaran'])): ?>
                                        &nbsp;•&nbsp;
                                        <i class="fas fa-calendar me-1"></i>
                                        <?= esc($k['tahun_ajaran']) ?>
                                    <?php endif; ?>
                                </p>

                                <div class="row text-center mb-3">
                                    <div class="col-4">
                                        <div class="border-end">
                                            <h4 class="fw-bold text-primary mb-0">
                                                <?= (int) $k['total_siswa'] ?>
                                            </h4>
                                            <small class="text-muted">Siswa</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="border-end">
                                            <h4 class="fw-bold text-success mb-0">
                                                <?= (int) $k['total_vark'] ?>
                                            </h4>
                                            <small class="text-muted">VARK</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <h4 class="fw-bold text-warning mb-0">
                                            <?= (int) $k['total_zpd'] ?>
                                        </h4>
                                        <small class="text-muted">ZPD</small>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="<?= base_url('guru/kelas/detail/' . $k['id']) ?>" 
                                       class="btn btn-primary-custom btn-sm flex-fill">
                                        <i class="fas fa-users me-1"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>