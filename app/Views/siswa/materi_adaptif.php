<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-shadow">
            <div class="card-body p-5">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="fw-bold mb-0">
                            <i class="fas fa-book text-primary me-2"></i>Materi Adaptif
                        </h4>
                        <p class="text-muted small mb-0">
                            Modul <?= $module_id ?> | 
                            <span class="badge bg-info"><?= $vark_label ?></span> + 
                            <span class="badge bg-secondary"><?= ucfirst($zpd_level) ?></span>
                        </p>
                    </div>
                    <div>
                        <span class="badge bg-primary">Kode: <?= $konten['kode_konten'] ?></span>
                    </div>
                </div>

                <hr>

                <!-- Profil Siswa -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="fw-bold"><i class="fas fa-user-graduate me-2"></i>Profil Anda</h6>
                                <p class="mb-1"><strong>Gaya Belajar:</strong> <?= $vark_label ?></p>
                                <p class="mb-0"><strong>Level ZPD:</strong> <?= ucfirst($zpd_level) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-<?= ($scaffolding['level'] == 'high') ? 'danger' : (($scaffolding['level'] == 'fading') ? 'warning' : 'success') ?> text-white">
                            <div class="card-body">
                                <h6 class="fw-bold"><i class="fas fa-life-ring me-2"></i><?= $scaffolding['label'] ?></h6>
                                <p class="mb-0"><?= $scaffolding['description'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Judul Materi -->
                <h5 class="fw-bold text-primary mb-3"><?= esc($konten['judul']) ?></h5>

                <!-- Isi Konten -->
                <div class="card border-0 bg-light p-4 mb-4">
                    <?= $konten['isi_konten'] ?? '<p class="text-muted">Konten sedang dalam pengembangan.</p>' ?>
                </div>

                <!-- URL Media (jika ada) -->
                <?php if (!empty($konten['url_media'])): ?>
                    <div class="mb-4">
                        <h6 class="fw-bold"><i class="fas fa-link me-2"></i>Media Pendukung</h6>
                        <a href="<?= esc($konten['url_media']) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-external-link-alt me-1"></i> Buka Media
                        </a>
                    </div>
                <?php endif; ?>

                <hr>

                <!-- Tombol Navigasi -->
                <div class="d-flex justify-content-between mt-3">
                    <a href="<?= base_url('siswa/modul') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali ke Modul
                    </a>
                    <div>
                        <?php if ($module_id < 3): ?>
                            <a href="<?= base_url('materi/' . ($module_id + 1)) ?>" class="btn btn-success">
                                Modul Selanjutnya <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('siswa/modul') ?>" class="btn btn-primary-custom">
                                <i class="fas fa-check me-1"></i> Selesai Semua Modul
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>