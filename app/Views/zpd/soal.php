<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="card card-shadow">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">
                        <i class="fas fa-flask text-warning me-2"></i>Soal ZPD - Modul <?= $module_id ?>
                    </h4>
                    <div>
                        <span class="badge bg-primary me-2">10 Soal</span>
                        <span class="badge bg-danger" id="timer">10:00</span>
                    </div>
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('zpd/submit/' . $module_id) ?>" method="post" id="zpdForm">
                    <?= csrf_field() ?>

                    <?php $no = 1; foreach ($questions as $q): ?>
                        <div class="card mb-3 border-0 bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-bold">Soal <?= $no++ ?></h6>
                                    <span class="badge <?= ($q['level_zpd'] == 'dasar') ? 'bg-success' : (($q['level_zpd'] == 'menengah') ? 'bg-warning' : 'bg-danger') ?>">
                                        <?= ucfirst($q['level_zpd']) ?> (Bobot <?= $q['bobot_nilai'] ?>)
                                    </span>
                                </div>
                                <p class="mb-3 mt-2"><?= $q['teks_soal'] ?></p>
                                <div class="row">
                                    <?php
                                    $opsi = [
                                        'A' => $q['opsi_a'],
                                        'B' => $q['opsi_b'],
                                        'C' => $q['opsi_c'],
                                        'D' => $q['opsi_d']
                                    ];
                                    foreach ($opsi as $key => $text): ?>
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio"
                                                       name="answers[<?= $q['id'] ?>]"
                                                       value="<?= $key ?>"
                                                       id="q<?= $q['id'] ?>_<?= $key ?>" required>
                                                <label class="form-check-label" for="q<?= $q['id'] ?>_<?= $key ?>">
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
                        <button type="submit" class="btn btn-warning btn-lg text-white">
                            <i class="fas fa-check-circle me-2"></i> Lihat Hasil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
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
        const interval = setInterval(updateTimer, 1000);

        form.addEventListener('submit', function() {
            clearInterval(interval);
        });
    })();
</script>
<?= $this->endSection() ?>