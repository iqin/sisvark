<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-shadow">
            <div class="card-body">
                <h4 class="fw-bold mb-4"><i class="fas fa-plus-circle text-success me-2"></i>Tambah Modul</h4>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <?php foreach (session()->getFlashdata('errors') as $err): ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('guru/modul/simpan') ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul Modul</label>
                        <input type="text" name="judul" class="form-control" value="<?= old('judul') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4" required><?= old('deskripsi') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Urutan</label>
                        <input type="number" name="urutan" class="form-control" value="<?= old('urutan') ?>" required>
                        <small class="text-muted">Urutan tampilan modul (1, 2, 3, ...).</small>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Simpan</button>
                        <a href="<?= base_url('guru/modul') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>