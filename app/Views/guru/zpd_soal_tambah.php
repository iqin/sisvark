<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-shadow">
            <div class="card-body">
                <h4 class="fw-bold mb-4"><i class="fas fa-plus-circle text-success me-2"></i>Tambah Soal ZPD</h4>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('guru/zpd/soal/simpan') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Modul</label>
                                <select name="modul_id" class="form-select" required>
                                    <option value="">-- Pilih Modul --</option>
                                    <?php foreach ($modul as $m): ?>
                                        <option value="<?= $m['id'] ?>"><?= esc($m['judul']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Level ZPD</label>
                                <select name="level_zpd" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="dasar">Dasar</option>
                                    <option value="menengah">Menengah</option>
                                    <option value="lanjut">Lanjut</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Bobot Nilai</label>
                                <select name="bobot_nilai" class="form-select" required>
                                    <option value="">-- Pilih --</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Teks Soal</label>
                        <textarea name="teks_soal" class="form-control" rows="3" required><?= old('teks_soal') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Opsi A</label>
                                <input type="text" name="opsi_a" class="form-control" value="<?= old('opsi_a') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Opsi B</label>
                                <input type="text" name="opsi_b" class="form-control" value="<?= old('opsi_b') ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Opsi C</label>
                                <input type="text" name="opsi_c" class="form-control" value="<?= old('opsi_c') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Opsi D</label>
                                <input type="text" name="opsi_d" class="form-control" value="<?= old('opsi_d') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Jawaban Benar</label>
                        <select name="jawaban_benar" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Simpan</button>
                        <a href="<?= base_url('guru/zpd/soal') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>