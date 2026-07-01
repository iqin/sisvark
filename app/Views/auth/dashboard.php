<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-shadow p-4">
            <h2>Halo, <?= session()->get('name') ?> 👋</h2>
            <p>Ini adalah dashboard sementara. Selanjutnya kita akan buat:</p>
            <ul>
                <li>Halaman Pilih Modul (Storyboard Page 2)</li>
                <li>Test VARK (Pages 3-5)</li>
                <li>Test ZPD (Pages 6-8)</li>
                <li>Materi Adaptif (Page 9)</li>
            </ul>
            <a href="<?= base_url('logout') ?>" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>