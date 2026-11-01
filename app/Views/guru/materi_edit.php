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

                <?php if (session()->getFlashdata('upload_error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('upload_error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('guru/materi/update/' . $konten['id']) ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="form_version" value="v2">

                    <!-- =========================================================== -->
                    <!-- FIELD READONLY (Kode Konten, Modul, VARK, ZPD) -->
                    <!-- =========================================================== -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kode Konten</label>
                                <input type="text" name="kode_konten" class="form-control" 
                                       value="<?= old('kode_konten', $konten['kode_konten']) ?>" 
                                       readonly disabled>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Modul</label>
                                <select name="modul_id" class="form-select" required disabled>
                                    <?php foreach ($modul as $m): ?>
                                        <option value="<?= $m['id'] ?>" <?= ($m['id'] == $konten['modul_id']) ? 'selected' : '' ?>>
                                            <?= esc($m['judul']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
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

                    <!-- =========================================================== -->
                    <!-- JUDUL -->
                    <!-- =========================================================== -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Judul</label>
                        <input type="text" name="judul" class="form-control" 
                               value="<?= old('judul', $konten['judul']) ?>" required>
                    </div>

                    <!-- =========================================================== -->
                    <!-- TIPE TAMPILAN -->
                    <!-- =========================================================== -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipe Tampilan</label>
                        <select name="tipe_tampilan" id="tipe_tampilan" class="form-select" required>
                            <option value="teks" <?= ($konten['tipe_tampilan'] ?? 'teks') == 'teks' ? 'selected' : '' ?>>Teks</option>
                            <option value="gambar" <?= ($konten['tipe_tampilan'] ?? '') == 'gambar' ? 'selected' : '' ?>>Gambar</option>
                            <option value="audio" <?= ($konten['tipe_tampilan'] ?? '') == 'audio' ? 'selected' : '' ?>>Audio</option>
                            <option value="video" <?= ($konten['tipe_tampilan'] ?? '') == 'video' ? 'selected' : '' ?>>Video</option>
                            <option value="interaktif" <?= ($konten['tipe_tampilan'] ?? '') == 'interaktif' ? 'selected' : '' ?>>Interaktif</option>
                            <option value="hybrid" <?= ($konten['tipe_tampilan'] ?? '') == 'hybrid' ? 'selected' : '' ?>>Hybrid (Gambar + Teks)</option>
                        </select>
                        <small class="text-muted">Pilih jenis konten utama. Field yang tidak relevan akan disembunyikan secara otomatis.</small>
                    </div>

                    <!-- =========================================================== -->
                    <!-- FIELD-FIELD KONTEN (dengan display:none default) -->
                    <!-- =========================================================== -->
                    
                    <!-- GAMBAR: Multi-gambar repeater -->
                    <div class="mb-3" id="gambar_group" style="display:none;">

                        <?php if (!empty($gambar_list)): ?>
                        <label class="form-label fw-bold">
                            <i class="fas fa-images me-1"></i> Gambar Tersimpan (<?= count($gambar_list) ?>)
                        </label>
                        <div class="mb-3">
                            <?php foreach ($gambar_list as $g): ?>
                            <div class="card mb-2 border">
                                <div class="card-body py-2">
                                    <div class="row g-2 align-items-start">
                                        <div class="col-md-2">
                                            <img src="<?= base_url(ltrim($g['gambar_url'], '/')) ?>" class="img-thumbnail" style="width:100%; max-height:90px; object-fit:cover;">
                                        </div>
                                        <div class="col-md-10">
                                            <input type="hidden" name="gambar_id[]" value="<?= $g['id'] ?>">
                                            <input type="text" name="judul_lama[]" class="form-control form-control-sm mb-1" placeholder="Judul gambar (opsional)" value="<?= esc($g['judul'] ?? '') ?>">
                                            <textarea name="penjelasan_lama[]" class="form-control form-control-sm mb-1" rows="2" placeholder="Penjelasan gambar (opsional)"><?= esc($g['penjelasan'] ?? '') ?></textarea>
                                            <div class="form-check">
                                                <input type="checkbox" name="hapus_gambar[]" value="<?= $g['id'] ?>" class="form-check-input" id="hapus_<?= $g['id'] ?>">
                                                <label class="form-check-label small text-danger" for="hapus_<?= $g['id'] ?>">Hapus gambar ini</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php elseif (!empty($konten['gambar_url'])): ?>
                        <div class="alert alert-info py-2 mb-3">
                            <i class="fas fa-info-circle me-1"></i> <strong>Materi ini masih menggunakan gambar tunggal lama.</strong>
                            <img src="<?= base_url(ltrim($konten['gambar_url'], '/')) ?>" class="d-block mt-2 rounded border" style="max-height:100px;">
                            <small class="text-muted d-block mt-1">Tambahkan gambar baru di bawah untuk mengaktifkan galeri. Gambar lama akan otomatis disimpan sebagai gambar pertama jika Anda tidak menambahkan gambar baru.</small>
                        </div>
                        <?php endif; ?>

                        <hr class="my-3">
                        <label class="form-label fw-bold">
                            <i class="fas fa-plus-circle me-1"></i> Tambah Gambar Baru
                        </label>
                        <small class="text-muted d-block mb-2">Format: jpg, png, gif, webp, svg. Maks 2MB per file. Total maksimal 10 gambar.</small>
                        <div id="newImagesContainer"></div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addNewImageRow()">
                            <i class="fas fa-plus me-1"></i> Tambah Baris Gambar
                        </button>
                    </div>

                    <!-- TEKS KONTEN (selalu tampil) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Teks Konten</label>
                        <textarea name="teks_konten" class="form-control" rows="6"><?= old('teks_konten', $konten['teks_konten'] ?? '') ?></textarea>
                        <small class="text-muted">Teks deskriptif, transkrip, atau penjelasan. Bisa berisi HTML.</small>
                    </div>

                    <!-- AUDIO: Upload + URL -->
                    <div class="mb-3" id="audio_group" style="display:none;">
                        <label class="form-label fw-bold">Upload Audio (MP3)</label>
                        <input type="file" name="audio_file" class="form-control" accept="audio/*">
                        <small class="text-muted">Upload file audio (mp3, wav, ogg). Maks 5MB.</small>

                        <?php if (!empty($konten['audio_url'])): ?>
                            <div class="mt-2">
                                <audio controls style="max-width: 100%;">
                                    <source src="<?= base_url($konten['audio_url']) ?>" type="audio/mpeg">
                                    Browser Anda tidak mendukung audio.
                                </audio>
                                <br>
                                <span class="text-muted small">Audio saat ini: <?= esc($konten['audio_url']) ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="mt-2">
                            <label class="form-label fw-bold">URL Audio (alternatif / embed)</label>
                            <input type="text" name="audio_url" class="form-control" 
                                   value="<?= old('audio_url', $konten['audio_url'] ?? '') ?>" 
                                   placeholder="https://example.com/audio.mp3 atau embed YouTube/SoundCloud">
                            <small class="text-muted">Jika upload file, URL ini akan diabaikan. Bisa juga untuk link embed (YouTube, SoundCloud).</small>
                        </div>
                    </div>

                    <!-- VIDEO: Hanya URL (tidak ada upload) -->
                    <div class="mb-3" id="video_group" style="display:none;">
                        <label class="form-label fw-bold">URL Video</label>
                        <input type="text" name="video_url" class="form-control" 
                               value="<?= old('video_url', $konten['video_url'] ?? '') ?>" 
                               placeholder="https://www.youtube.com/embed/... atau /assets/video/...">
                        <small class="text-muted">Masukkan link video (YouTube embed, Vimeo, atau file video lokal).</small>

                        <?php if (!empty($konten['video_url'])): ?>
                            <div class="mt-2">
                                <?php 
                                    $video_url = $konten['video_url'];
                                    // Deteksi jika link YouTube
                                    if (strpos($video_url, 'youtube.com/embed') !== false || strpos($video_url, 'youtu.be') !== false) {
                                        // Tampilkan embed jika memang embed
                                        echo '<div class="ratio ratio-16x9">';
                                        echo '<iframe src="' . esc($video_url) . '" allowfullscreen></iframe>';
                                        echo '</div>';
                                    } else {
                                        echo '<video controls style="max-width: 100%; max-height: 300px;">';
                                        echo '<source src="' . base_url($video_url) . '" type="video/mp4">';
                                        echo 'Browser Anda tidak mendukung video.';
                                        echo '</video>';
                                    }
                                ?>
                                <br>
                                <span class="text-muted small">Video saat ini: <?= esc($video_url) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- INTERAKTIF: Hanya URL -->
                    <div class="mb-3" id="interaktif_group" style="display:none;">
                        <label class="form-label fw-bold">URL Interaktif</label>
                        <input type="text" name="interaktif_url" class="form-control" 
                               value="<?= old('interaktif_url', $konten['interaktif_url'] ?? '') ?>" 
                               placeholder="/assets/interactive/modulX/nama_file.html">
                        <small class="text-muted">Path ke file interaktif (HTML, PHP, dll).</small>
                    </div>

                    <!-- =========================================================== -->
                    <!-- TOMBOL -->
                    <!-- =========================================================== -->
                    <div class="mt-3">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-1"></i> Update
                        </button>
                        <a href="<?= base_url('guru/materi') ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- =========================================================== -->
<!-- JAVASCRIPT UNTUK TOGGLE FIELD BERDASARKAN TIPE TAMPILAN -->
<!-- =========================================================== -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function toggleFields() {
            var tipe = document.getElementById('tipe_tampilan').value;
            
            var gambarGroup = document.getElementById('gambar_group');
            var audioGroup = document.getElementById('audio_group');
            var videoGroup = document.getElementById('video_group');
            var interaktifGroup = document.getElementById('interaktif_group');

            // Sembunyikan semua
            if (gambarGroup) gambarGroup.style.display = 'none';
            if (audioGroup) audioGroup.style.display = 'none';
            if (videoGroup) videoGroup.style.display = 'none';
            if (interaktifGroup) interaktifGroup.style.display = 'none';

            // Tampilkan sesuai tipe
            switch(tipe) {
                case 'gambar':
                    if (gambarGroup) gambarGroup.style.display = 'block';
                    break;
                case 'audio':
                    if (audioGroup) audioGroup.style.display = 'block';
                    break;
                case 'video':
                    if (videoGroup) videoGroup.style.display = 'block';
                    break;
                case 'interaktif':
                    if (interaktifGroup) interaktifGroup.style.display = 'block';
                    break;
                case 'hybrid':
                    if (gambarGroup) gambarGroup.style.display = 'block';
                    break;
                case 'teks':
                default:
                    break;
            }
            
        }

        toggleFields();
        document.getElementById('tipe_tampilan').addEventListener('change', toggleFields);

                // ============ MULTI-IMAGE REPEATER ============
        window.addNewImageRow = function() {
            const container = document.getElementById('newImagesContainer');
            const currentCount = container.querySelectorAll('.new-img-item').length;
            if (currentCount >= 10) {
                alert('Maksimal 10 gambar baru per materi.');
                return;
            }
            const html = `
            <div class="card mb-2 new-img-item border-primary">
                <div class="card-body py-2">
                    <div class="row g-2 align-items-start">
                        <div class="col-md-3">
                            <input type="file" name="gambar_baru[]" class="form-control form-control-sm" accept="image/*" onchange="previewImg(this)">
                            <img class="img-thumbnail mt-1 preview-img" style="display:none; width:100%; max-height:80px; object-fit:cover;">
                        </div>
                        <div class="col-md-8">
                            <input type="text" name="judul_baru[]" class="form-control form-control-sm mb-1" placeholder="Judul gambar (opsional)">
                            <textarea name="penjelasan_baru[]" class="form-control form-control-sm" rows="2" placeholder="Penjelasan (opsional)"></textarea>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.new-img-item').remove()" title="Batal">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
        };

        window.previewImg = function(input) {
            const file = input.files[0];
            const preview = input.parentElement.querySelector('.preview-img');
            if (file && preview) {
                preview.src = URL.createObjectURL(file);
                preview.style.display = 'block';
            }
        };
    });
</script>
<?= $this->endSection() ?>