<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0"><i class="fas fa-flask text-warning me-2"></i>Kelola Soal ZPD</h4>
                    <a href="<?= base_url('guru/zpd/soal/tambah') ?>" class="btn btn-warning">
                        <i class="fas fa-plus-circle me-1"></i> Tambah Soal
                    </a>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-warning">
                            <tr>
                                <th>#</th>
                                <th>Modul</th>
                                <th>Level ZPD</th>
                                <th>Bobot</th>
                                <th>Teks Soal</th>
                                <th>Jawaban</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($soal)): ?>
                                <tr><td colspan="7" class="text-center text-muted">Belum ada soal ZPD.</td></tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($soal as $s): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= esc($s['modul_judul']) ?></td>
                                        <td>
                                            <?php
                                            $levelLabel = [
                                                'dasar' => 'Dasar',
                                                'menengah' => 'Menengah',
                                                'lanjut' => 'Lanjut'
                                            ];
                                            ?>
                                            <span class="badge bg-secondary"><?= $levelLabel[$s['level_zpd']] ?? $s['level_zpd'] ?></span>
                                        </td>
                                        <td><?= $s['bobot_nilai'] ?></td>
                                        <td><?= substr($s['teks_soal'], 0, 60) ?>...</td>
                                        <td><strong><?= $s['jawaban_benar'] ?></strong></td>
                                        <td>
                                            <a href="<?= base_url('guru/zpd/soal/edit/' . $s['id']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                            <a href="<?= base_url('guru/zpd/soal/hapus/' . $s['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus soal ini?')"><i class="fas fa-trash"></i></a>
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