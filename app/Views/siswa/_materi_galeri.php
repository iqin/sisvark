<?php
/**
 * Partial: Galeri Gambar Interaktif (Swiper.js)
 * 
 * Variabel yang diterima:
 * - $gambar_list : array baris dari tabel materi_adaptif_gambar
 * - $teks        : teks_konten induk (opsional, tampil sebagai intro collapsible)
 * - $judul       : judul materi (fallback alt text)
 */

$gambar_list = $gambar_list ?? [];
$teks        = $teks        ?? '';
$judul       = $judul       ?? '';
$multi       = count($gambar_list) > 1;

// Helper: bangun URL dari path (support leading slash / full URL)
$gpath = function ($p) {
    if (preg_match('#^https?://#i', $p)) return $p;
    return base_url(ltrim($p, '/'));
};
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

<?php if (!empty($teks)): ?>
<div class="mb-3">
    <button class="btn btn-sm btn-outline-secondary" type="button"
            data-bs-toggle="collapse" data-bs-target="#introMateri" aria-expanded="false">
        <i class="fas fa-info-circle me-1"></i> Penjelasan Materi
    </button>
    <div class="collapse mt-2" id="introMateri">
        <div class="alert alert-light border mb-0">
            <?= nl2br(esc($teks)) ?>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="materi-galeri" id="materiGaleri">
    <div class="swiper swiperMateri" style="border-radius:0.5rem; background:#f8f9fa; overflow:hidden;">
        <div class="swiper-wrapper">
            <?php foreach ($gambar_list as $g): ?>
            <div class="swiper-slide">
                <div class="d-flex align-items-center justify-content-center" style="min-height:300px;">
                    <img src="<?= esc($gpath($g['gambar_url'])) ?>"
                         alt="<?= esc($g['judul'] ?? $judul) ?>"
                         class="swiper-zoom-target"
                         style="width:100%; max-height:500px; object-fit:contain; cursor:zoom-in;">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if ($multi): ?>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
        <?php endif; ?>
    </div>

    <div class="card mt-2" id="materiCaption">
        <div class="card-body py-2">
            <div class="d-flex justify-content-between align-items-center">
                <strong id="captionJudul"><?= esc($gambar_list[0]['judul'] ?? '') ?></strong>
                <?php if ($multi): ?>
                <small class="text-muted" id="captionCounter">1 / <?= count($gambar_list) ?></small>
                <?php endif; ?>
            </div>
            <div class="text-muted small mt-1" id="captionPenjelasan">
                <?= nl2br(esc($gambar_list[0]['penjelasan'] ?? '')) ?>
            </div>
        </div>
    </div>

    <?php if ($multi): ?>
    <div class="swiper swiperThumb mt-2" style="padding:4px 0;">
        <div class="swiper-wrapper">
            <?php foreach ($gambar_list as $i => $g): ?>
            <div class="swiper-slide" style="width:80px; height:60px; opacity:0.6; transition:opacity .2s, border-color .2s; border:2px solid transparent; border-radius:0.25rem; overflow:hidden; cursor:pointer;">
                <img src="<?= esc($gpath($g['gambar_url'])) ?>"
                     alt="thumb <?= $i + 1 ?>"
                     style="width:100%; height:100%; object-fit:cover;">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
(function () {
    const gambarData = <?= json_encode(array_map(function ($g) {
        return [
            'judul'      => $g['judul']      ?? '',
            'penjelasan' => $g['penjelasan'] ?? '',
        ];
    }, $gambar_list)) ?>;

    const total = gambarData.length;
    if (!total) return;

    const thumbsEl = document.querySelector('.swiperThumb');
    let thumbSwiper = null;
    if (thumbsEl) {
        thumbSwiper = new Swiper('.swiperThumb', {
            spaceBetween: 8,
            slidesPerView: 'auto',
            watchSlidesProgress: true,
        });
    }

    const mainSwiper = new Swiper('.swiperMateri', {
        loop: false,
        zoom: true,
        keyboard: { enabled: true },
        navigation: total > 1 ? {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        } : false,
        pagination: total > 1 ? {
            el: '.swiper-pagination',
            clickable: true,
        } : false,
        thumbs: thumbSwiper ? { swiper: thumbSwiper } : undefined,
        on: {
            slideChange: function () {
                const i  = this.activeIndex;
                const d  = gambarData[i] || {};
                const elJ = document.getElementById('captionJudul');
                const elP = document.getElementById('captionPenjelasan');
                const elC = document.getElementById('captionCounter');
                if (elJ) elJ.textContent = d.judul || '';
                if (elP) elP.innerHTML  = (d.penjelasan || '').replace(/\n/g, '<br>');
                if (elC) elC.textContent = (i + 1) + ' / ' + total;

                document.querySelectorAll('.swiperThumb .swiper-slide').forEach(function (s, idx) {
                    s.style.opacity    = (idx === i) ? '1' : '0.6';
                    s.style.borderColor = (idx === i) ? '#0d6efd' : 'transparent';
                });
            }
        }
    });

    // Initial highlight untuk thumbnail
    const initThumbs = document.querySelectorAll('.swiperThumb .swiper-slide');
    if (initThumbs.length) {
        initThumbs[0].style.opacity = '1';
        initThumbs[0].style.borderColor = '#0d6efd';
    }
})();
</script>