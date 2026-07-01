<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <!-- Header dengan Timer -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">
                        <i class="fas fa-pencil-alt text-primary me-2"></i>Soal Test V.A.R.K
                    </h4>
                    <div>
                        <span class="badge bg-primary me-2">10 Soal</span>
                        <span class="badge bg-danger" id="timer">10:00</span>
                    </div>
                </div>

                <!-- Pesan Error -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <!-- Form Soal -->
                <form action="<?= base_url('vark/submit') ?>" method="post" id="varkForm">
                    <?= csrf_field() ?>

                    <?php $no = 1; foreach ($questions as $q): ?>
                        <div class="card mb-3 border-0 bg-light">
                            <div class="card-body">
                                <h6 class="fw-bold">Soal <?= $no++ ?></h6>
                                <p class="mb-3"><?= $q['teks_soal'] ?></p>
                                <div class="row">
                                    <?php
                                    $opsi = [
                                        'V' => $q['opsi_v'],
                                        'A' => $q['opsi_a'],
                                        'R' => $q['opsi_r'],
                                        'K' => $q['opsi_k']
                                    ];
                                    foreach ($opsi as $key => $text): ?>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       name="answers[<?= $q['nomor'] ?>]"
                                                       value="<?= $key ?>"
                                                       id="q<?= $q['nomor'] ?>_<?= $key ?>" required>
                                                <label class="form-check-label" for="q<?= $q['nomor'] ?>_<?= $key ?>">
                                                    <span class="badge bg-secondary me-1"><?= $key ?></span>
                                                    <?= $text ?>
                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                            <i class="fas fa-check-circle me-2"></i> Lihat Hasil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================
     TIMER COUNTDOWN (10 MENIT) - BERBASIS SERVER TIME
     ============================================================ -->
<script>
    (function() {
        // Sisa waktu dari server (dalam detik)
        let remaining = <?= $remaining ?? 600 ?>;

        // Element timer
        const timerEl = document.getElementById('timer');
        const form = document.getElementById('varkForm');
        const submitBtn = document.getElementById('submitBtn');

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
                // Submit otomatis
                if (form) {
                    alert('⏰ Waktu habis! Jawaban akan dikirim otomatis.');
                    form.submit();
                }
                return;
            }
            timerEl.textContent = formatTime(remaining);
            remaining--;
        }

        // Update timer setiap 1 detik
        updateTimer();
        const interval = setInterval(updateTimer, 1000);

        // Hentikan interval jika form disubmit manual
        form.addEventListener('submit', function() {
            clearInterval(interval);
        });

        // Hentikan interval jika user navigasi keluar (opsional)
        window.addEventListener('beforeunload', function() {
            clearInterval(interval);
        });
    })();
</script>

<style>
    #timer {
        font-size: 1.2rem;
        padding: 6px 14px;
        font-weight: 700;
        min-width: 70px;
        display: inline-block;
        text-align: center;
    }
    .card.bg-light {
        background-color: #f8f9fa !important;
    }
    .form-check-input:checked {
        background-color: #4e73df;
        border-color: #4e73df;
    }
</style>
<?= $this->endSection() ?>