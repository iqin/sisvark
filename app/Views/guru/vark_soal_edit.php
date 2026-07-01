<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-shadow">
            <div class="card-body">
                <h4 class="fw-bold mb-4"><i class="fas fa-edit text-warning me-2"></i>Edit Soal VARK #<?= $soal['nomor'] ?></h4>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $err): ?>
                                <li><?= esc($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('guru/vark/soal/update/' . $soal['id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nomor</label>
                                <input type="number" name="nomor" class="form-control" 
                                       value="<?= $soal['nomor'] ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Teks Soal</label>
                                <textarea name="teks_soal" class="form-control" rows="2" required><?= $soal['teks_soal'] ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-primary">Opsi V (Visual)</label>
                                <input type="text" name="opsi_v" class="form-control" 
                                       value="<?= $soal['opsi_v'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-success">Opsi A (Aural)</label>
                                <input type="text" name="opsi_a" class="form-control" 
                                       value="<?= $soal['opsi_a'] ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-warning">Opsi R (Read/Write)</label>
                                <input type="text" name="opsi_r" class="form-control" 
                                       value="<?= $soal['opsi_r'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-danger">Opsi K (Kinestetik)</label>
                                <input type="text" name="opsi_k" class="form-control" 
                                       value="<?= $soal['opsi_k'] ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                        <a href="<?= base_url('guru/vark/soal') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>