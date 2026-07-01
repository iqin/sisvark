<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0"><i class="fas fa-list-ul text-primary me-2"></i>Kelola Soal VARK</h4>
                    <a href="<?= base_url('guru/vark/soal/tambah') ?>" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Soal
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

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th width="60">No</th>
                                <th>Teks Soal</th>
                                <th width="100">Opsi V</th>
                                <th width="100">Opsi A</th>
                                <th width="100">Opsi R</th>
                                <th width="100">Opsi K</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($soal)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x d-block mb-2"></i>
                                        Belum ada soal. Silakan tambah soal pertama!
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($soal as $s): ?>
                                    <tr>
                                        <td class="text-center fw-bold"><?= $s['nomor'] ?></td>
                                        <td><?= substr($s['teks_soal'], 0, 80) . (strlen($s['teks_soal']) > 80 ? '...' : '') ?></td>
                                        <td><span class="badge bg-primary">V</span> <?= substr($s['opsi_v'], 0, 25) . (strlen($s['opsi_v']) > 25 ? '...' : '') ?></td>
                                        <td><span class="badge bg-success">A</span> <?= substr($s['opsi_a'], 0, 25) . (strlen($s['opsi_a']) > 25 ? '...' : '') ?></td>
                                        <td><span class="badge bg-warning text-dark">R</span> <?= substr($s['opsi_r'], 0, 25) . (strlen($s['opsi_r']) > 25 ? '...' : '') ?></td>
                                        <td><span class="badge bg-danger">K</span> <?= substr($s['opsi_k'], 0, 25) . (strlen($s['opsi_k']) > 25 ? '...' : '') ?></td>
                                        <td>
                                            <a href="<?= base_url('guru/vark/soal/edit/' . $s['id']) ?>" 
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('guru/vark/soal/hapus/' . $s['id']) ?>" 
                                               class="btn btn-danger btn-sm" title="Hapus"
                                               onclick="return confirm('Yakin ingin menghapus soal ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>