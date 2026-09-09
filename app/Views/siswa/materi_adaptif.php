<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <!-- KOLOM KIRI -->
    <div class="col-lg-9">
        <div class="card card-shadow">
            <div class="card-body p-4 p-md-5">
                <!-- HEADER -->
                <div class="d-flex align-items-start justify-content-between mb-3 flex-wrap">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-book-open fa-2x text-primary me-3"></i>
                        <div>
                            <h5 class="text-muted mb-1">PELAJARAN : BIOLOGI</h5>
                            <?php
                            $modulNames = [1 => 'STRUKTUR SEL', 2 => 'ORGANEL SEL', 3 => 'TRANSPOR MEMBRAN'];
                            ?>
                            <h4 class="fw-bold">MODUL : <?= $modulNames[$module_id] ?? 'MODUL ' . $module_id ?></h4>
                        </div>
                    </div>
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

                <!-- KONTEN UTAMA -->
                <div class="row mt-3">
                    <!-- Tombol VARK Vertikal -->
                    <div class="col-2 col-md-1">
                        <div class="d-flex flex-column gap-3" id="varkButtonContainer">
                            <?php
                            $varkIcons = ['V' => 'fa-eye', 'A' => 'fa-headphones', 'R' => 'fa-pen', 'K' => 'fa-hand'];
                            $varkLabels = ['V' => 'Visual', 'A' => 'Aural', 'R' => 'Read/Write', 'K' => 'Kinestetik'];
                            
                            // Tentukan apakah semua tombol harus tampil
                            // 1. Jika siswa Multimodal (M) -> semua tampil
                            // 2. Jika Novice + show_all = true -> semua tampil
                            $showAllVark = ($vark_type == 'M') || ($zpd_level == 'novice' && $show_all);
                            
                            foreach ($varkIcons as $key => $icon):
                                $isActive = ($key === $vark_type);
                                $label = $varkLabels[$key];
                                
                                // Tombol tampil jika: aktif ATAU (showAllVark = true)
                                $isVisible = $isActive || $showAllVark;
                                $hideClass = $isVisible ? 'd-flex' : 'd-none';
                            ?>
                                <a href="<?= base_url('materi/' . $module_id . '?vark=' . $key . ($show_all ? '&show=all' : '')) ?>" 
                                   class="btn btn-sm <?= $isActive ? 'btn-primary' : 'btn-outline-secondary' ?> <?= $hideClass ?> align-items-center justify-content-center py-2 px-1 vark-btn"
                                   title="<?= $label ?>"
                                   data-vark="<?= $key ?>"
                                   data-active="<?= $isActive ? '1' : '0' ?>"
                                   style="font-size: 0.7rem; text-decoration: none; border-radius: 0.4rem;">
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
                            $tipe = $konten['tipe_tampilan'] ?? 'teks';
                            $gambar = $konten['gambar_url'] ?? '';
                            $teks = $konten['teks_konten'] ?? '';
                            $audio = $konten['audio_url'] ?? '';
                            $video = $konten['video_url'] ?? '';
                            $interaktif = $konten['interaktif_url'] ?? '';

                            function isYoutubeEmbed($url) {
                                return (strpos($url, 'youtube.com/embed') !== false || 
                                        strpos($url, 'youtu.be') !== false || 
                                        strpos($url, 'youtube.com/watch') !== false);
                            }
                            function isAudioEmbed($url) {
                                return (strpos($url, 'soundcloud.com') !== false || 
                                        strpos($url, 'spotify.com') !== false);
                            }

                            switch ($tipe) {
                                case 'hybrid':
                                    if (!empty($gambar)) echo '<img src="' . base_url($gambar) . '" class="img-fluid rounded-3 mb-3" alt="' . esc($konten['judul']) . '" style="max-height: 400px; width: auto;">';
                                    if (!empty($teks)) echo '<div class="text-muted">' . nl2br(esc($teks)) . '</div>';
                                    break;
                                case 'gambar':
                                    if (!empty($gambar)) echo '<img src="' . base_url($gambar) . '" class="img-fluid rounded-3 mb-3" alt="' . esc($konten['judul']) . '" style="max-height: 400px; width: auto;">';
                                    if (!empty($teks)) echo '<div class="text-muted">' . nl2br(esc($teks)) . '</div>';
                                    break;
                                case 'audio':
                                    if (!empty($audio)) {
                                        if (isAudioEmbed($audio)) {
                                            echo '<div class="ratio ratio-16x9 mb-3"><iframe src="' . esc($audio) . '" allow="autoplay; encrypted-media" allowfullscreen></iframe></div>';
                                        } else {
                                            echo '<audio controls class="w-100 mb-3"><source src="' . base_url($audio) . '" type="audio/mpeg">Browser Anda tidak mendukung audio.</audio>';
                                        }
                                    }
                                    if (!empty($teks)) echo '<div class="text-muted">' . nl2br(esc($teks)) . '</div>';
                                    break;
                                case 'video':
                                    if (!empty($video)) {
                                        if (isYoutubeEmbed($video)) {
                                            if (strpos($video, 'watch?v=') !== false) {
                                                $videoId = explode('v=', $video);
                                                $videoId = explode('&', $videoId[1]);
                                                $videoId = $videoId[0];
                                                $video = 'https://www.youtube.com/embed/' . $videoId;
                                            } elseif (strpos($video, 'youtu.be/') !== false) {
                                                $videoId = explode('youtu.be/', $video);
                                                $videoId = explode('?', $videoId[1]);
                                                $videoId = $videoId[0];
                                                $video = 'https://www.youtube.com/embed/' . $videoId;
                                            }
                                            echo '<div class="ratio ratio-16x9 mb-3"><iframe src="' . esc($video) . '" allowfullscreen></iframe></div>';
                                        } else {
                                            echo '<video controls class="w-100 mb-3" style="max-height: 500px; width: auto;"><source src="' . base_url($video) . '" type="video/mp4">Browser Anda tidak mendukung video.</video>';
                                        }
                                    }
                                    if (!empty($teks)) echo '<div class="text-muted">' . nl2br(esc($teks)) . '</div>';
                                    break;
                                case 'interaktif':
                                    if (!empty($interaktif)) {
                                        $ext = pathinfo($interaktif, PATHINFO_EXTENSION);
                                        if (in_array($ext, ['html', 'htm', 'php'])) {
                                            echo '<iframe src="' . base_url($interaktif) . '" class="w-100" style="height: 550px; border: 1px solid #dee2e6; border-radius: 0.5rem;"></iframe>';
                                        } else {
                                            echo '<a href="' . base_url($interaktif) . '" target="_blank" class="btn btn-outline-primary">Buka Media Interaktif</a>';
                                        }
                                    }
                                    if (!empty($teks)) echo '<div class="text-muted mt-2">' . nl2br(esc($teks)) . '</div>';
                                    break;
                                default:
                                    if (!empty($teks)) echo '<div class="text-muted">' . nl2br(esc($teks)) . '</div>';
                                    else echo '<p class="text-muted">Belum ada konten untuk kombinasi ini.</p>';
                                    break;
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- NAVIGASI BAWAH -->
                <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                    <a href="<?= base_url('siswa/modul') ?>" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left me-2"></i> Kembali</a>
                    <a href="#" class="btn btn-primary btn-sm" onclick="alert('Fitur lanjut ke post-test atau modul berikutnya akan segera hadir.');">Lanjut <i class="fas fa-arrow-right ms-2"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN -->
    <div class="col-lg-3">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="fw-bold" style="font-size: 0.9rem; letter-spacing: 0.5px;">SISTEM PEMBELAJARAN<br>BERDIFERENSIASI</span>
                    </div>
                    <div>
                        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" style="height: 40px; width: auto;" onerror="this.style.display='none'">
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <?php if ($zpd_level == 'novice' && $vark_type != 'M'): ?>
                        <!-- NOVICE + NON-MULTIMODAL: Detail Materi, Tanya Guru, Post Test -->
                        <?php if ($show_all): ?>
                            <button class="btn btn-secondary btn-sm text-start py-2" disabled>
                                <i class="fas fa-check me-2"></i> Semua VARK tampil
                            </button>
                        <?php else: ?>
                            <a href="<?= base_url('materi/' . $module_id . '?vark=' . $vark_type . '&show=all') ?>" class="btn btn-outline-primary btn-sm text-start py-2">
                                <i class="fas fa-file-alt me-2"></i> Detail Materi
                            </a>
                        <?php endif; ?>
                        
                        <!-- Tanya Guru untuk Novice non-Multimodal -->
                        <button class="btn btn-danger btn-sm text-start py-2 flex-grow-1" data-bs-toggle="modal" data-bs-target="#chatModal">
                            <i class="fas fa-chalkboard-teacher me-2"></i> Tanya Guru
                        </button>

                    <?php elseif ($zpd_level == 'novice' && $vark_type == 'M'): ?>
                        <!-- NOVICE + MULTIMODAL: Tanya Guru, Post Test (tanpa Detail Materi) -->
                        <button class="btn btn-danger btn-sm text-start py-2 flex-grow-1" data-bs-toggle="modal" data-bs-target="#chatModal">
                            <i class="fas fa-chalkboard-teacher me-2"></i> Tanya Guru
                        </button>

                    <?php elseif ($zpd_level == 'apprentice'): ?>
                        <!-- APPRENTICE: Tanya Guru, Post Test -->
                        <button class="btn btn-danger btn-sm text-start py-2 flex-grow-1" data-bs-toggle="modal" data-bs-target="#chatModal">
                            <i class="fas fa-chalkboard-teacher me-2"></i> Tanya Guru
                        </button>

                    <?php elseif ($zpd_level == 'master'): ?>
                        <!-- MASTER: HANYA Post Test -->
                    <?php endif; ?>

                    <!-- Post Test selalu muncul untuk semua level -->
                        <a href="<?= base_url('posttest/intro/' . $module_id) ?>" class="btn btn-outline-success btn-sm text-start py-2">
                            <i class="fas fa-check-circle me-2"></i> Post Test
                        </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================== -->
<!-- MODAL TANYA GURU -->
<!-- =========================================================== -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="chatModalLabel"><i class="fas fa-chalkboard-teacher me-2"></i>Tanya Guru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Riwayat chat -->
                <div id="chatHistory" style="max-height: 250px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px; margin-bottom: 10px; background: #f9f9f9;">
                    <div class="text-muted text-center" id="emptyChat">Belum ada pesan</div>
                </div>
                <!-- Input pesan -->
                <div class="input-group">
                    <input type="text" id="chatInput" class="form-control" placeholder="Tulis pertanyaan...">
                    <button class="btn btn-danger" id="sendChatBtn">Kirim</button>
                </div>
                <div id="chatFeedback" class="mt-2 d-none"></div>
            </div>
        </div>
    </div>
</div>

<!-- Tooltip & JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function (el) { return new bootstrap.Tooltip(el); });

    // Elemen
    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendChatBtn');
    const chatHistory = document.getElementById('chatHistory');
    const feedback = document.getElementById('chatFeedback');

    // ---- Ambil riwayat chat dari server ----
    function loadChatHistory() {
        fetch('<?= base_url('chat/history/json') ?>', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            chatHistory.innerHTML = '';
            if (data.length === 0) {
                chatHistory.innerHTML = '<div class="text-muted text-center">Belum ada pesan</div>';
                return;
            }
            data.forEach(item => {
                const isSaya = (item.pengirim === 'Saya');
                const div = document.createElement('div');
                div.className = `chat-item mb-2 p-2 rounded shadow-sm ${isSaya ? 'bg-primary text-white' : 'bg-white'}`;
                div.style.textAlign = isSaya ? 'right' : 'left';
                div.innerHTML = `
                    <div class="d-flex justify-content-between">
                        <strong>${item.pengirim}</strong>
                        <small class="${isSaya ? 'text-light' : 'text-muted'}">${item.created_at}</small>
                    </div>
                    <p class="mb-0 ${isSaya ? 'text-white' : ''}">${item.pesan}</p>
                `;
                chatHistory.appendChild(div);
            });
            chatHistory.scrollTop = chatHistory.scrollHeight;
        })
        .catch(err => {
            console.error('Gagal load history:', err);
            chatHistory.innerHTML = '<div class="text-danger text-center">Gagal memuat riwayat chat.</div>';
        });
    }

    // ---- Kirim pesan ----
    function sendMessage() {
        const pesan = chatInput.value.trim();
        if (!pesan) {
            feedback.textContent = 'Silakan tulis pesan.';
            feedback.className = 'alert alert-danger d-block';
            return;
        }

        const formData = new FormData();
        formData.append('pesan', pesan);

        fetch('<?= base_url('chat/send') ?>', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                feedback.textContent = 'Pesan terkirim!';
                feedback.className = 'alert alert-success d-block';
                chatInput.value = '';
                loadChatHistory();
                setTimeout(() => {
                    feedback.className = 'd-none';
                }, 3000);
            } else {
                feedback.textContent = data.message || 'Gagal mengirim.';
                feedback.className = 'alert alert-danger d-block';
            }
        })
        .catch(err => {
            console.error('Error saat kirim:', err);
            feedback.textContent = 'Terjadi kesalahan: ' + err.message;
            feedback.className = 'alert alert-danger d-block';
        });
    }

    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });

    const chatModal = document.getElementById('chatModal');
    chatModal.addEventListener('shown.bs.modal', function() {
        loadChatHistory();
    });

    chatModal.addEventListener('hidden.bs.modal', function() {
        chatInput.value = '';
        feedback.className = 'd-none';
    });
});
</script>
<?= $this->endSection() ?>