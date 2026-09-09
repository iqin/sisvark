<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-shadow">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0"><i class="fas fa-envelope text-primary me-2"></i>Pesan dari Siswa</h4>
                    <span class="badge bg-primary">Total: <?= count($siswa) ?> siswa</span>
                </div>

                <?php if (empty($siswa)): ?>
                    <div class="alert alert-info text-center py-4">
                        <i class="fas fa-inbox fa-3x d-block mb-3"></i>
                        Belum ada pesan dari siswa.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>Siswa</th>
                                    <th>Pesan Terakhir</th>
                                    <th>Waktu</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($siswa as $s): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= esc($s['siswa_nama']) ?></strong></td>
                                    <td><?= esc(substr($s['pesan_terakhir'], 0, 50)) ?>...</td>
                                    <td><?= date('d-m-Y H:i', strtotime($s['waktu_terakhir'])) ?></td>
                                    <td>
                                        <?php if ($s['belum_dibaca'] > 0): ?>
                                            <span class="badge bg-danger">
                                                <?= $s['belum_dibaca'] ?> belum terbaca
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Sudah Dibaca</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm btn-balas" 
                                                data-siswa-id="<?= $s['siswa_id'] ?>"
                                                data-siswa-nama="<?= esc($s['siswa_nama']) ?>"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#chatModal">
                                            <i class="fas fa-reply me-1"></i> Balas
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Chat -->
<div class="modal fade" id="chatModal" tabindex="-1" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="chatModalLabel"><i class="fas fa-comment-dots me-2"></i>Chat dengan <span id="chatSiswaNama">Siswa</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="chatHistory" style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 5px; margin-bottom: 10px; background: #f9f9f9;">
                    <div class="text-muted text-center">Memuat percakapan...</div>
                </div>
                <div class="input-group">
                    <input type="text" id="chatInput" class="form-control" placeholder="Tulis balasan...">
                    <button class="btn btn-primary" id="sendChatBtn">Kirim</button>
                </div>
                <div id="chatFeedback" class="mt-2 d-none"></div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatInput = document.getElementById('chatInput');
    const sendBtn = document.getElementById('sendChatBtn');
    const chatHistory = document.getElementById('chatHistory');
    const feedback = document.getElementById('chatFeedback');
    const chatSiswaNama = document.getElementById('chatSiswaNama');
    let currentSiswaId = null;

    // ---- Load Chat History untuk siswa tertentu ----
    function loadChatHistory(siswaId) {
        chatHistory.innerHTML = '<div class="text-muted text-center">Memuat...</div>';
        
        // Gunakan endpoint yang benar: chat/history/siswa/{id}
        fetch('<?= base_url('chat/history/siswa/') ?>' + siswaId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            chatHistory.innerHTML = '';
            if (!data || data.length === 0) {
                chatHistory.innerHTML = '<div class="text-muted text-center">Belum ada pesan</div>';
                return;
            }
            data.forEach(item => {
                const div = document.createElement('div');
                // Gunakan is_from_guru untuk menentukan pengirim
                const isGuru = item.is_from_guru == 1;
                div.className = `chat-item mb-2 p-2 rounded shadow-sm ${isGuru ? 'bg-primary text-white' : 'bg-white'}`;
                div.style.textAlign = isGuru ? 'right' : 'left';
                div.innerHTML = `
                    <div class="d-flex justify-content-between">
                        <strong>${isGuru ? 'Saya (Guru)' : 'Siswa'}</strong>
                        <small class="${isGuru ? 'text-light' : 'text-muted'}">${item.created_at}</small>
                    </div>
                    <p class="mb-0 ${isGuru ? 'text-white' : ''}">${item.pesan}</p>
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

    // ---- Kirim balasan dari guru ----
    function sendMessage() {
        const pesan = chatInput.value.trim();
        if (!pesan) {
            feedback.textContent = 'Silakan tulis balasan.';
            feedback.className = 'alert alert-danger d-block';
            return;
        }

        if (!currentSiswaId) {
            feedback.textContent = 'Siswa tidak teridentifikasi.';
            feedback.className = 'alert alert-danger d-block';
            return;
        }

        const formData = new FormData();
        formData.append('pesan', pesan);
        formData.append('siswa_id', currentSiswaId);

        fetch('<?= base_url('chat/reply') ?>', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                feedback.textContent = 'Balasan terkirim!';
                feedback.className = 'alert alert-success d-block';
                chatInput.value = '';
                loadChatHistory(currentSiswaId); // Refresh riwayat
                setTimeout(() => {
                    feedback.className = 'd-none';
                }, 3000);
                // Reload halaman setelah 2 detik untuk update tabel
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                feedback.textContent = data.message || 'Gagal mengirim.';
                feedback.className = 'alert alert-danger d-block';
            }
        })
        .catch(err => {
            console.error('Error:', err);
            feedback.textContent = 'Terjadi kesalahan: ' + err.message;
            feedback.className = 'alert alert-danger d-block';
        });
    }

    // ---- Event listener tombol Balas (selector BENAR: .btn-balas) ----
    document.querySelectorAll('.btn-balas').forEach(btn => {
        btn.addEventListener('click', function() {
            currentSiswaId = this.dataset.siswaId;
            chatSiswaNama.textContent = this.dataset.siswaNama;
            loadChatHistory(currentSiswaId);
        });
    });

    // ---- Kirim pesan ----
    sendBtn.addEventListener('click', sendMessage);
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });

    // ---- Reset saat modal ditutup ----
    document.getElementById('chatModal').addEventListener('hidden.bs.modal', function() {
        chatInput.value = '';
        feedback.className = 'd-none';
        currentSiswaId = null;
    });
});
</script>
<?= $this->endSection() ?>