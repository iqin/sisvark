<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-shadow">
            <div class="card-body">
                <h4 class="fw-bold mb-4"><i class="fas fa-edit text-warning me-2"></i>Edit Soal ZPD</h4>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('guru/zpd/soal/update/' . $soal['id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="row">
                        <!-- MODUL -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Modul</label>
                                <select name="modul_id_dummy" class="form-select" disabled>
                                    <?php foreach ($modul as $m): ?>
                                        <option value="<?= $m['id'] ?>" <?= ($m['id'] == $soal['modul_id']) ? 'selected' : '' ?>><?= esc($m['judul']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="modul_id" value="<?= $soal['modul_id'] ?>">
                                <small class="text-muted">Modul tidak dapat diubah.</small>
                            </div>
                        </div>

                        <!-- LEVEL ZPD -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Level ZPD</label>
                                <select name="level_zpd_dummy" class="form-select" disabled>
                                    <option value="dasar" <?= ($soal['level_zpd'] == 'dasar') ? 'selected' : '' ?>>Dasar</option>
                                    <option value="menengah" <?= ($soal['level_zpd'] == 'menengah') ? 'selected' : '' ?>>Menengah</option>
                                    <option value="lanjut" <?= ($soal['level_zpd'] == 'lanjut') ? 'selected' : '' ?>>Lanjut</option>
                                </select>
                                <input type="hidden" name="level_zpd" value="<?= $soal['level_zpd'] ?>">
                                <small class="text-muted">Level ZPD tidak dapat diubah.</small>
                            </div>
                        </div>

                        <!-- BOBOT NILAI -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Bobot Nilai</label>
                                <select name="bobot_nilai_dummy" class="form-select" disabled>
                                    <option value="5" <?= ($soal['bobot_nilai'] == 5) ? 'selected' : '' ?>>5</option>
                                    <option value="10" <?= ($soal['bobot_nilai'] == 10) ? 'selected' : '' ?>>10</option>
                                    <option value="15" <?= ($soal['bobot_nilai'] == 15) ? 'selected' : '' ?>>15</option>
                                </select>
                                <input type="hidden" name="bobot_nilai" value="<?= $soal['bobot_nilai'] ?>">
                                <small class="text-muted">Bobot tidak dapat diubah.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Teks Soal</label>
                        <textarea name="teks_soal" class="form-control" rows="3" required><?= $soal['teks_soal'] ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Opsi A</label>
                                <input type="text" name="opsi_a" class="form-control" value="<?= $soal['opsi_a'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Opsi B</label>
                                <input type="text" name="opsi_b" class="form-control" value="<?= $soal['opsi_b'] ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Opsi C</label>
                                <input type="text" name="opsi_c" class="form-control" value="<?= $soal['opsi_c'] ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Opsi D</label>
                                <input type="text" name="opsi_d" class="form-control" value="<?= $soal['opsi_d'] ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jawaban Benar</label>
                        <select name="jawaban_benar" class="form-select" required>
                            <option value="A" <?= ($soal['jawaban_benar'] == 'A') ? 'selected' : '' ?>>A</option>
                            <option value="B" <?= ($soal['jawaban_benar'] == 'B') ? 'selected' : '' ?>>B</option>
                            <option value="C" <?= ($soal['jawaban_benar'] == 'C') ? 'selected' : '' ?>>C</option>
                            <option value="D" <?= ($soal['jawaban_benar'] == 'D') ? 'selected' : '' ?>>D</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i> Update</button>
                        <a href="<?= base_url('guru/zpd/soal') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>