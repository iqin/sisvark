<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-shadow p-4">
            <h2>👤 Profil Saya</h2>
            <p class="text-muted">Informasi akun dan status pembelajaran Anda.</p>
            <hr>

            <!-- Informasi Akun -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <h5>Informasi Akun</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Nama</strong></td>
                            <td>: <?= session()->get('name') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>: <?= session()->get('email') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Peran</strong></td>
                            <td>: <?= ucfirst(session()->get('role')) ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h5>Status Pembelajaran</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="150"><strong>Tes VARK</strong></td>
                            <td>
                                <?php if (isset($varkResult) && $varkResult !== null): ?>
                                    <span class="badge bg-success">Sudah</span> 
                                    (<?= esc($varkResult['kategori_hasil']) ?>)
                                <?php else: ?>
                                    <span class="badge bg-secondary">Belum</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>ZPD Modul 1</strong></td>
                            <td>
                                <?php if (isset($zpdMod1) && $zpdMod1 !== null): ?>
                                    <span class="badge bg-success">Sudah</span>
                                    (Level: <?= esc($zpdMod1['level_zpd']) ?>)
                                <?php else: ?>
                                    <span class="badge bg-secondary">Belum</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>ZPD Modul 2</strong></td>
                            <td>
                                <?php if (isset($zpdMod2) && $zpdMod2 !== null): ?>
                                    <span class="badge bg-success">Sudah</span>
                                    (Level: <?= esc($zpdMod2['level_zpd']) ?>)
                                <?php else: ?>
                                    <span class="badge bg-secondary">Belum</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>ZPD Modul 3</strong></td>
                            <td>
                                <?php if (isset($zpdMod3) && $zpdMod3 !== null): ?>
                                    <span class="badge bg-success">Sudah</span>
                                    (Level: <?= esc($zpdMod3['level_zpd']) ?>)
                                <?php else: ?>
                                    <span class="badge bg-secondary">Belum</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>