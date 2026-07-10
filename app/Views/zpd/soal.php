<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-10 col-xl-8">
        <div class="card card-shadow">
            <div class="card-body p-3 p-md-4 p-lg-5">
                <!-- Header -->
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <h4 class="fw-bold mb-0 fs-5 fs-md-4">
                        <i class="fas fa-flask text-warning me-2"></i>Soal Pretest Modul <?= $module_id ?>
                    </h4>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <span class="badge bg-primary fs-6" id="progress-indicator">1 / <?= count($questions) ?></span>
                        <span class="badge bg-danger fs-6" id="timer">10:00</span>
                    </div>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('zpd/submit/' . $module_id) ?>" method="post" id="zpdForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="total_questions" value="<?= count($questions) ?>">

                    <?php 
                    $no = 1; 
                    foreach ($questions as $q): 
                        $levelClass = ($q['level_zpd'] == 'dasar') ? 'bg-success' : (($q['level_zpd'] == 'menengah') ? 'bg-warning' : 'bg-danger');
                        $levelLabel = ucfirst($q['level_zpd']);
                        $opsi = ['A' => $q['opsi_a'], 'B' => $q['opsi_b'], 'C' => $q['opsi_c'], 'D' => $q['opsi_d']];
                    ?>
                        <div class="soal-item" id="soal-<?= $no ?>" style="<?= ($no > 1) ? 'display:none;' : '' ?>">
                            <!-- Soal -->
                            <div class="card border-0 bg-light mb-3 shadow-sm">
                                <div class="card-body p-3 p-md-4">
                                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                                        <h5 class="fw-bold mb-0 fs-6 fs-md-5">Soal <?= $no ?></h5>
                                        <span class="badge <?= $levelClass ?> fs-6"><?= $levelLabel ?> (Bobot <?= $q['bobot_nilai'] ?>)</span>
                                    </div>
                                    <p class="fs-6 fs-md-5 mb-4"><?= $q['teks_soal'] ?></p>
                                    <div class="row g-3">
                                        <?php foreach ($opsi as $key => $text): ?>
                                            <div class="col-12 col-md-6">
                                                <div class="option-item p-3 border rounded-3 bg-white h-100">
                                                    <div class="form-check d-flex align-items-start m-0">
                                                        <input class="form-check-input flex-shrink-0 mt-1 me-3" 
                                                               type="radio"
                                                               name="answers[<?= $q['id'] ?>]"
                                                               value="<?= $key ?>"
                                                               id="q<?= $q['id'] ?>_<?= $key ?>"
                                                               <?= ($no > 1) ? '' : 'required' ?>>
                                                        <label class="form-check-label fs-6 fs-md-5" 
                                                               for="q<?= $q['id'] ?>_<?= $key ?>">
                                                            <span class="badge bg-secondary me-2"><?= $key ?></span>
                                                            <?= $text ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigasi -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">
                                <button type="button" class="btn btn-secondary btn-prev px-4 py-2" <?= ($no == 1) ? 'disabled' : '' ?>>
                                    <i class="fas fa-arrow-left me-2"></i>Kembali
                                </button>
                                <?php if ($no < count($questions)): ?>
                                    <button type="button" class="btn btn-primary btn-next px-4 py-2" data-next="<?= $no+1 ?>">
                                        Next <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-warning text-white px-4 py-2">
                                        <i class="fas fa-check-circle me-2"></i> Submit
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php $no++; endforeach; ?>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.fade-transition {
    animation: fadeIn 0.25s ease-in-out;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(8px); }
    to { opacity: 1; transform: translateY(0); }
}
.option-item {
    transition: all 0.2s ease;
    cursor: pointer;
    border-color: #dee2e6 !important;
}
.option-item:hover {
    background-color: #f8fbff !important;
    border-color: #4e73df !important;
    box-shadow: 0 2px 8px rgba(78, 115, 223, 0.12);
    transform: translateY(-2px);
}
.option-item:has(input:checked) {
    background-color: #e9f0ff !important;
    border-color: #4e73df !important;
    box-shadow: 0 2px 12px rgba(78, 115, 223, 0.2);
}
.option-item .form-check-input:checked {
    background-color: #4e73df;
    border-color: #4e73df;
}
.option-item .form-check-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
}
@media (max-width: 576px) {
    .card-body { padding: 1rem !important; }
    .fs-6 { font-size: 0.9rem !important; }
    .fs-5 { font-size: 1rem !important; }
    .badge.fs-6 { font-size: 0.75rem !important; padding: 0.3rem 0.6rem; }
    .btn { font-size: 0.85rem !important; padding: 0.4rem 0.8rem !important; }
    .option-item { padding: 0.75rem !important; }
    .option-item .form-check-input { width: 1.1rem; height: 1.1rem; margin-top: 0.1rem; }
}
</style>

<script>
(function() {
    'use strict';

    // --- TIMER ---
    let remaining = <?= $remaining ?? 600 ?>;
    const timerEl = document.getElementById('timer');
    const form = document.getElementById('zpdForm');

    function formatTime(sec) {
        const m = Math.floor(sec / 60);
        const s = sec % 60;
        return String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
    }

    function updateTimer() {
        if (remaining <= 0) {
            timerEl.textContent = '00:00';
            timerEl.classList.remove('bg-danger');
            timerEl.classList.add('bg-dark');
            alert('⏰ Waktu habis! Jawaban akan dikirim otomatis.');
            form.submit();
            return;
        }
        timerEl.textContent = formatTime(remaining);
        remaining--;
    }
    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);

    // --- NAVIGASI ---
    const totalSoal = <?= count($questions) ?>;
    let currentSoal = 1;
    const progressIndicator = document.getElementById('progress-indicator');
    const soalItems = document.querySelectorAll('.soal-item');

    function showSoal(index) {
        // Sembunyikan semua
        soalItems.forEach(el => el.style.display = 'none');
        // Tampilkan yang dipilih
        const target = document.getElementById('soal-' + index);
        if (!target) return;
        target.style.display = 'block';
        target.classList.remove('fade-transition');
        void target.offsetWidth; // trigger reflow
        target.classList.add('fade-transition');

        // Update progress
        progressIndicator.textContent = index + ' / ' + totalSoal;

        // Update required radio
        document.querySelectorAll('.soal-item input[type="radio"]').forEach(inp => inp.removeAttribute('required'));
        const activeRadios = target.querySelectorAll('input[type="radio"]');
        activeRadios.forEach(inp => inp.setAttribute('required', 'required'));
    }

    // Event listener untuk tombol Next
    document.querySelectorAll('.btn-next').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const parentSoal = this.closest('.soal-item');
            if (!parentSoal) return;

            // Cek apakah jawaban sudah dipilih
            const radios = parentSoal.querySelectorAll('input[type="radio"]');
            let answered = false;
            radios.forEach(r => { if (r.checked) answered = true; });
            if (!answered) {
                alert('Silakan pilih jawaban terlebih dahulu.');
                return;
            }

            // Ambil nomor soal berikutnya dari data attribute
            const next = parseInt(this.dataset.next);
            if (next && next <= totalSoal) {
                currentSoal = next;
                showSoal(currentSoal);
            }
        });
    });

    // Event listener untuk tombol Prev
    document.querySelectorAll('.btn-prev').forEach(btn => {
        btn.addEventListener('click', function(e) {
            const parentSoal = this.closest('.soal-item');
            if (!parentSoal) return;

            const id = parseInt(parentSoal.id.split('-')[1]);
            if (id > 1) {
                currentSoal = id - 1;
                showSoal(currentSoal);
            }
        });
    });

    // Hentikan timer saat form disubmit
    form.addEventListener('submit', function() {
        clearInterval(timerInterval);
    });

    // Inisialisasi tampilan awal
    showSoal(1);

})();
</script>
<?= $this->endSection() ?>