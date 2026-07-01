<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0"><i class="fas fa-chart-bar text-success me-2"></i>Hasil VARK Siswa</h4>
                    <a href="<?= base_url('guru/dashboard') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <?php if (empty($siswa)): ?>
                    <div class="alert alert-info">Belum ada siswa terdaftar.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-success">
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Kelas</th>
                                    <th>Hasil VARK</th>
                                    <th>Tipe</th>
                                    <th>Skor V</th>
                                    <th>Skor A</th>
                                    <th>Skor R</th>
                                    <th>Skor K</th>
                                    <th>Tanggal Tes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($siswa as $s): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($s['nama']) ?></td>
                                        <td><?= esc($s['email']) ?></td>
                                        <td><?= esc($s['kelas']) ?></td>
                                        <td>
                                            <?php if ($s['vark_hasil'] == 'Belum Tes'): ?>
                                                <span class="badge bg-secondary">Belum Tes</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary"><?= esc($s['vark_hasil']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($s['tipe_hasil'] != '-'): ?>
                                                <span class="badge bg-info text-dark"><?= esc($s['tipe_hasil']) ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= $s['skor_v'] ?? '-' ?></td>
                                        <td><?= $s['skor_a'] ?? '-' ?></td>
                                        <td><?= $s['skor_r'] ?? '-' ?></td>
                                        <td><?= $s['skor_k'] ?? '-' ?></td>
                                        <td><?= esc($s['tanggal']) ?></td>
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