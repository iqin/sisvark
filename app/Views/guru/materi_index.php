<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0"><i class="fas fa-cubes text-primary me-2"></i>Kelola Materi Adaptif</h4>
                    <span class="badge bg-primary">Total: <?= count($konten) ?> konten</span>
                </div>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th>Kode</th>
                                <th>Modul</th>
                                <th>VARK</th>
                                <th>ZPD</th>
                                <th>Judul</th>
                                <th>Tipe</th>
                                <th>Media</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($konten)): ?>
                                <tr><td colspan="8" class="text-center text-muted">Belum ada konten adaptif.</td></tr>
                            <?php else: ?>
                                <?php foreach ($konten as $k): ?>
                                    <tr>
                                        <td><strong><?= esc($k['kode_konten']) ?></strong></td>
                                        <td><?= esc($k['modul_judul']) ?></td>
                                        <td>
                                            <?php
                                            $varkLabels = ['V'=>'Visual', 'A'=>'Aural', 'R'=>'Read/Write', 'K'=>'Kinestetik', 'M'=>'Multimodal'];
                                            $label = $varkLabels[$k['tipe_vark']] ?? $k['tipe_vark'];
                                            ?>
                                            <span class="badge bg-info"><?= esc($label) ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $zpdLabels = ['novice'=>'Novice', 'apprentice'=>'Apprentice', 'master'=>'Master'];
                                            $zpdLabel = $zpdLabels[$k['level_zpd']] ?? $k['level_zpd'];
                                            ?>
                                            <span class="badge bg-secondary"><?= esc($zpdLabel) ?></span>
                                        </td>
                                        <td><?= esc($k['judul']) ?></td>
                                        <td>
                                            <?php
                                            $tipe = $k['tipe_tampilan'] ?? 'teks';
                                            $tipeLabels = [
                                                'teks' => ['label' => 'Teks', 'class' => 'secondary'],
                                                'gambar' => ['label' => 'Gambar', 'class' => 'primary'],
                                                'audio' => ['label' => 'Audio', 'class' => 'success'],
                                                'video' => ['label' => 'Video', 'class' => 'danger'],
                                                'interaktif' => ['label' => 'Interaktif', 'class' => 'warning'],
                                                'hybrid' => ['label' => 'Hybrid', 'class' => 'info']
                                            ];
                                            $tipeInfo = $tipeLabels[$tipe] ?? ['label' => ucfirst($tipe), 'class' => 'secondary'];
                                            ?>
                                            <span class="badge bg-<?= $tipeInfo['class'] ?>"><?= $tipeInfo['label'] ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            // Tampilkan ikon untuk setiap media yang tersedia
                                            $mediaIcons = [];
                                            if (!empty($k['gambar_url'])) {
                                                $mediaIcons[] = '<i class="fas fa-image text-primary" title="Gambar"></i>';
                                            }
                                            if (!empty($k['audio_url'])) {
                                                $mediaIcons[] = '<i class="fas fa-music text-success" title="Audio"></i>';
                                            }
                                            if (!empty($k['video_url'])) {
                                                $mediaIcons[] = '<i class="fas fa-video text-danger" title="Video"></i>';
                                            }
                                            if (!empty($k['interaktif_url'])) {
                                                $mediaIcons[] = '<i class="fas fa-cogs text-warning" title="Interaktif"></i>';
                                            }
                                            if (empty($mediaIcons)) {
                                                echo '<span class="text-muted">-</span>';
                                            } else {
                                                echo implode(' ', $mediaIcons);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url('guru/materi/edit/' . $k['id']) ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
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