<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-shadow">
            <div class="card-body">
                <h4 class="fw-bold mb-4"><i class="fas fa-edit text-warning me-2"></i>Edit Materi Adaptif</h4>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('guru/materi/update/' . $konten['id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kode Konten</label>
                                <input type="text" name="kode_konten" class="form-control" value="<?= old('kode_konten', $konten['kode_konten']) ?>" readonly disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Modul</label>
                                <select name="modul_id" class="form-select" required disabled>
                                    <?php foreach ($modul as $m): ?>
                                        <option value="<?= $m['id'] ?>" <?= ($m['id'] == $konten['modul_id']) ? 'selected' : '' ?>><?= esc($m['judul']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <!-- VARK -->
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label fw-bold">VARK</label>
                                <div class="form-control-plaintext fw-bold">
                                    <?php 
                                        $varkLabels = ['V'=>'Visual', 'A'=>'Aural', 'R'=>'Read/Write', 'K'=>'Kinestetik', 'M'=>'Multimodal'];
                                        echo $varkLabels[$konten['tipe_vark']] ?? $konten['tipe_vark'];
                                    ?>
                                </div>
                                <input type="hidden" name="tipe_vark" value="<?= $konten['tipe_vark'] ?>">
                                <small class="text-muted">Tidak dapat diubah.</small>
                            </div>
                        </div>

                        <!-- ZPD Level -->
                        <div class="col-md-2">
                            <div class="mb-3">
                                <label class="form-label fw-bold">ZPD Level</label>
                                <div class="form-control-plaintext fw-bold">
                                    <?php 
                                        $zpdLabels = ['novice'=>'Novice', 'apprentice'=>'Apprentice', 'master'=>'Master'];
                                        echo $zpdLabels[$konten['level_zpd']] ?? $konten['level_zpd'];
                                    ?>
                                </div>
                                <input type="hidden" name="level_zpd" value="<?= $konten['level_zpd'] ?>">
                                <small class="text-muted">Tidak dapat diubah.</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul</label>
                        <input type="text" name="judul" class="form-control" value="<?= old('judul', $konten['judul']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">URL Media (opsional)</label>
                        <input type="text" name="url_media" class="form-control" value="<?= old('url_media', $konten['url_media']) ?>" placeholder="https://example.com/video.mp4 atau path gambar">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Isi Konten (HTML / Teks)</label>
                        <textarea name="isi_konten" class="form-control" rows="8"><?= old('isi_konten', $konten['isi_konten']) ?></textarea>
                        <small class="text-muted">Anda bisa menggunakan HTML untuk format teks, gambar, atau embed video.</small>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i> Update</button>
                        <a href="<?= base_url('guru/materi') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>