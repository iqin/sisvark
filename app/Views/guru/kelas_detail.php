<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-11">

        <!-- Tombol kembali -->
        <div class="mb-3">
            <a href="<?= base_url('guru/kelas') ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Kelas
            </a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Info Kelas -->
        <div class="card card-shadow mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap">
                    <div>
                        <h3 class="fw-bold mb-2">🏫 <?= esc($kelas['nama']) ?></h3>
                        <p class="text-muted mb-1">
                            <i class="fas fa-book me-1"></i>
                            <?= esc($kelas['mata_pelajaran']) ?>
                        </p>
                        <?php if (!empty($kelas['tahun_ajaran'])): ?>
                            <p class="text-muted mb-1">
                                <i class="fas fa-calendar me-1"></i>
                                Tahun Ajaran: <?= esc($kelas['tahun_ajaran']) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-secondary mb-2">
                            Kode: <strong><?= esc($kelas['kode_kelas']) ?></strong>
                        </span>
                        <br>
                        <?php if ($kelas['is_active']): ?>
                            <span class="badge bg-success">Aktif</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Arsip</span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (is_admin()): ?>
                    <hr>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= base_url('guru/kelas/toggle/' . $kelas['id']) ?>" 
                           class="btn btn-warning btn-sm"
                           onclick="return confirm('Ubah status aktif kelas ini?')">
                            <i class="fas fa-power-off me-1"></i>
                            <?= $kelas['is_active'] ? 'Arsipkan' : 'Aktifkan' ?>
                        </a>
                        <a href="<?= base_url('guru/kelas/regenerate/' . $kelas['id']) ?>" 
                           class="btn btn-outline-secondary btn-sm"
                           onclick="return confirm('Generate ulang kode kelas?')">
                            <i class="fas fa-sync me-1"></i> Regenerate Kode
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Daftar Siswa -->
        <div class="card card-shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                    <h5 class="fw-bold mb-0">
                        👥 Daftar Siswa 
                        <span class="badge bg-primary"><?= count($siswa) ?></span>
                    </h5>
                    <?php 
                    // Hitung siswa yang butuh aktivasi
                    $butuhAktivasi = 0;
                    foreach ($siswa as $s) {
                        if (isset($s['is_active']) && (int) $s['is_active'] === 0) {
                            $butuhAktivasi++;
                        }
                    }
                    ?>
                    <?php if ($butuhAktivasi > 0): ?>
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-clock me-1"></i>
                            <?= $butuhAktivasi ?> siswa menunggu aktivasi
                        </span>
                    <?php endif; ?>
                </div>

                <?php if (empty($siswa)): ?>
                    <div class="alert alert-info text-center mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada siswa yang tergabung di kelas ini.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Bergabung</th>
                                    <th width="12%">Status</th>
                                    <th width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($siswa as $i => $s): ?>
                                    <?php $aktif = isset($s['is_active']) ? (int) $s['is_active'] === 1 : true; ?>
                                    <tr>
                                        <td><?= $i + 1 ?></td>
                                        <td class="fw-semibold"><?= esc($s['nama']) ?></td>
                                        <td class="text-muted small"><?= esc($s['email']) ?></td>
                                        <td class="text-muted small">
                                            <?= $s['joined_at'] 
                                                ? date('d M Y H:i', strtotime($s['joined_at'])) 
                                                : '-' ?>
                                        </td>
                                        <td>
                                            <?php if ($aktif): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i> Aktif
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock me-1"></i> Nonaktif
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!$aktif): ?>
                                                <!-- Siswa nonaktif: tombol Aktivasi & Tolak -->
                                                <a href="<?= base_url('guru/kelas/aktivasi/' . $kelas['id'] . '/' . $s['id']) ?>"
                                                   class="btn btn-success btn-sm"
                                                   onclick="return confirm('Aktifkan akun siswa ini?')">
                                                    <i class="fas fa-check"></i> Aktivasi
                                                </a>
                                                <a href="<?= base_url('guru/kelas/tolak/' . $kelas['id'] . '/' . $s['id']) ?>"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Tolak dan hapus siswa ini? Data akan hilang permanen.')">
                                                    <i class="fas fa-times"></i> Tolak
                                                </a>
                                            <?php else: ?>
                                                <!-- Siswa aktif: tombol Kick (guru kelas & admin) -->
                                                <a href="<?= base_url('guru/kelas/kick/' . $kelas['id'] . '/' . $s['id']) ?>"
                                                   class="btn btn-outline-danger btn-sm"
                                                   onclick="return confirm('Keluarkan siswa ini dari kelas? Akun siswa tetap ada tapi tidak lagi terhubung ke kelas ini.')">
                                                    <i class="fas fa-user-minus"></i> Kick
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>