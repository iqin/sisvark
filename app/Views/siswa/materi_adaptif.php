<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <!-- KOLOM KIRI: KONTEN UTAMA + TOMBOL VARK -->
    <div class="col-lg-9">
        <div class="card card-shadow">
            <div class="card-body p-4 p-md-5">
                <!-- HEADER: Pelajaran + Modul | Ikon User + VARK/ZPD + Waktu -->
                <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                    <!-- Kiri: Ikon Buku + Pelajaran & Modul -->
                    <div class="d-flex align-items-center">
                        <i class="fas fa-book-open fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="text-muted mb-1">PELAJARAN : BIOLOGI</h5>
                            <?php
                            $modulNames = [
                                1 => 'STRUKTUR SEL',
                                2 => 'ORGANEL SEL',
                                3 => 'TRANSPOR MEMBRAN'
                            ];
                            ?>
                            <h4 class="fw-bold">MODUL : <?= $modulNames[$module_id] ?? 'MODUL ' . $module_id ?></h4>
                        </div>
                    </div>

                    <!-- Kanan: Ikon User + VARK + ZPD + Waktu -->
                    <div class="d-flex align-items-center gap-3 flex-wrap mt-2 mt-md-0">
                        <i class="fas fa-user-graduate fa-2x text-primary"></i>
                        <div class="d-flex flex-column align-items-start">
                            <span class="badge bg-primary">VARK: <?= $vark_label ?> (<?= strtoupper($vark_type) ?>)</span>
                            <span class="badge bg-secondary mt-1">ZPD: <?= ucfirst($zpd_level) ?></span>
                        </div>
                        <div>
                            <span class="badge bg-dark fs-6">30:50</span>
                        </div>
                    </div>
                </div>
                <hr>

                <!-- KONTEN UTAMA: TOMBOL VARK VERTIKAL (IKON + TOOLTIP) + MATERI -->
                <div class="row mt-3">
                    <!-- Tombol VARK Vertikal -->
                    <div class="col-2 col-md-1">
                        <div class="d-flex flex-column gap-3">
                            <?php
                            $varkIcons = [
                                'V' => 'fa-eye',
                                'A' => 'fa-headphones',
                                'R' => 'fa-pen',
                                'K' => 'fa-hand'
                            ];
                            $varkLabels = [
                                'V' => 'Visual',
                                'A' => 'Aural',
                                'R' => 'Read/Write',
                                'K' => 'Kinestetik'
                            ];
                            foreach ($varkIcons as $key => $icon):
                                $isActive = ($key === $vark_type);
                                $label = $varkLabels[$key];
                            ?>
                                <a href="<?= base_url('materi/' . $module_id . '?vark=' . $key) ?>" 
                                   class="btn btn-sm <?= $isActive ? 'btn-primary' : 'btn-outline-secondary' ?> d-flex align-items-center justify-content-center py-2 px-1"
                                   style="font-size: 0.7rem; text-decoration: none; border-radius: 0.4rem;"
                                   title="<?= $label ?>">
                                    <i class="fas <?= $icon ?> fa-lg"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Konten Materi -->
                    <div class="col-10 col-md-11">
                        <h5 class="fw-bold"><?= esc($konten['judul'] ?? 'Materi') ?></h5>
                        <div class="mt-2">
                            <?php
                            // Ambil data dari struktur baru
                            $tipe = $konten['tipe_tampilan'] ?? 'teks';
                            $gambar = $konten['gambar_url'] ?? '';
                            $teks = $konten['teks_konten'] ?? '';
                            $audio = $konten['audio_url'] ?? '';
                            $video = $konten['video_url'] ?? '';
                            $interaktif = $konten['interaktif_url'] ?? '';
                            $url_media = $konten['url_media'] ?? ''; // fallback

                            switch ($tipe) {
                                case 'hybrid':
                                    // Gambar + teks
                                    if (!empty($gambar)) {
                                        echo '<img src="' . base_url($gambar) . '" class="img-fluid rounded-3 mb-3" alt="' . esc($konten['judul']) . '" style="max-height: 400px; width: auto;">';
                                    }
                                    if (!empty($teks)) {
                                        echo '<p class="text-muted">' . nl2br(esc($teks)) . '</p>';
                                    }
                                    break;
                                case 'audio':
                                    // Audio player + transkrip
                                    if (!empty($audio)) {
                                        echo '<audio controls class="w-100 mb-3">
                                                <source src="' . base_url($audio) . '" type="audio/mpeg">
                                                Browser Anda tidak mendukung audio.
                                              </audio>';
                                    }
                                    if (!empty($teks)) {
                                        echo '<p class="text-muted">' . nl2br(esc($teks)) . '</p>';
                                    }
                                    break;
                                case 'video':
                                    // Video player + deskripsi
                                    if (!empty($video)) {
                                        echo '<video controls class="w-100 mb-3" style="max-height: 500px; width: auto;">
                                                <source src="' . base_url($video) . '" type="video/mp4">
                                                Browser Anda tidak mendukung video.
                                              </video>';
                                    }
                                    if (!empty($teks)) {
                                        echo '<p class="text-muted">' . nl2br(esc($teks)) . '</p>';
                                    }
                                    break;
                                case 'interaktif':
                                    // Interaktif (iframe atau link)
                                    if (!empty($interaktif)) {
                                        $ext = pathinfo($interaktif, PATHINFO_EXTENSION);
                                        if (in_array($ext, ['html', 'htm', 'php'])) {
                                            echo '<iframe src="' . base_url($interaktif) . '" class="w-100" style="height: 550px; border: 1px solid #dee2e6; border-radius: 0.5rem;"></iframe>';
                                        } else {
                                            echo '<a href="' . base_url($interaktif) . '" target="_blank" class="btn btn-outline-primary">Buka Media Interaktif</a>';
                                        }
                                    }
                                    if (!empty($teks)) {
                                        echo '<p class="text-muted mt-2">' . nl2br(esc($teks)) . '</p>';
                                    }
                                    break;
                                default: // teks
                                    if (!empty($teks)) {
                                        echo '<p class="text-muted">' . nl2br(esc($teks)) . '</p>';
                                    } else {
                                        echo '<p class="text-muted">Belum ada konten untuk kombinasi ini.</p>';
                                    }
                                    break;
                            }

                            // Fallback jika masih ada url_media dan tidak ada media lain yang tampil
                            if (!empty($url_media) && empty($gambar) && empty($audio) && empty($video) && empty($interaktif)) {
                                echo '<div class="mt-3">
                                        <a href="' . base_url($url_media) . '" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-external-link-alt me-1"></i> Lihat Media Pembelajaran
                                        </a>
                                      </div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- NAVIGASI BAWAH -->
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="<?= base_url('siswa/modul') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                    <a href="#" class="btn btn-primary btn-sm" onclick="alert('Fitur lanjut ke post-test atau modul berikutnya akan segera hadir.');">
                        Lanjut <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: PANEL INTERAKSI -->
    <div class="col-lg-3">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <!-- Header Sistem Pembelajaran Berdiferensiasi -->
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold" style="font-size: 0.9rem; letter-spacing: 0.5px;">
                            SISTEM PEMBELAJARAN<br>BERDIFERENSIASI
                        </span>
                    </div>
                    <div>
                        <img src="<?= base_url('assets/images/logo.png') ?>" 
                             alt="Logo" 
                             style="height: 40px; width: auto;"
                             onerror="this.style.display='none'">
                    </div>
                </div>

                <!-- Area CTA -->
                <div class="d-grid gap-2">
                    <?php if ($zpd_level == 'novice'): ?>
                        <button class="btn btn-outline-primary btn-sm text-start py-2" onclick="alert('Fitur Lihat Penilaian Detail untuk materi ini.')">
                            <i class="fas fa-file-alt me-2"></i> Detail Materi
                        </button>
                    <?php endif; ?>

                    <?php if ($zpd_level == 'apprentice'): ?>
                        <button class="btn btn-outline-warning btn-sm text-start py-2" onclick="alert('Fitur Bantuan aktif.')">
                            <i class="fas fa-question-circle me-2"></i> Butuh bantuan?
                        </button>
                    <?php endif; ?>

                    <button class="btn btn-outline-success btn-sm text-start py-2" onclick="alert('Arahkan ke halaman Post Test.')">
                        <i class="fas fa-check-circle me-2"></i> Post Test
                    </button>

                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-danger btn-sm text-start py-2 flex-grow-1" onclick="alert('Fitur Tanya Guru akan menghubungkan Anda dengan guru.')">
                            <i class="fas fa-chalkboard-teacher me-2"></i> Tanya Guru
                        </button>
                        <i class="fas fa-user-tie fa-2x text-secondary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tooltip Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
<?= $this->endSection() ?>