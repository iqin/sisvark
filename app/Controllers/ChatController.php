<?php

namespace App\Controllers;

use App\Models\PesanModel;
use App\Models\UserModel;

class ChatController extends BaseController
{
    // ======== SISWA KIRIM PESAN ========
    public function send()  
    {
        // Cek login dan role siswa
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Anda harus login sebagai siswa!'
            ]);
        }

        $pesan = $this->request->getPost('pesan');
        if (empty($pesan)) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Pesan tidak boleh kosong'
            ]);
        }

        // Cari guru (ambil guru pertama)
        $userModel = new UserModel();
        $guru = $userModel->where('peran', 'guru')->first();
        
        if (!$guru) {
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Guru belum terdaftar. Silakan hubungi admin.'
            ]);
        }

        // Simpan pesan dengan created_at manual
        $model = new PesanModel();
        $model->save([
            'siswa_id'      => session()->get('user_id'),
            'guru_id'       => $guru['id'],
            'pesan'         => $pesan,
            'is_read'       => 0,
            'is_from_guru'  => 0,
            'parent_id'     => null,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => 'Pesan terkirim!'
        ]);
    }


    
    // ======== GET CHAT HISTORY DENGAN SISWA TERTENTU (UNTUK GURU) ========
public function historySiswa($siswaId)
{
    if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
        return $this->response->setJSON([]);
    }

    $guruId = session()->get('user_id');
    $db = \Config\Database::connect();

    $query = $db->query("
        SELECT 
            pesan.*,
            CASE 
                WHEN pesan.is_from_guru = 1 THEN 'Guru'
                ELSE 'Siswa'
            END as pengirim
        FROM pesan
        WHERE (siswa_id = ? AND guru_id = ?) OR (siswa_id = ? AND guru_id = ? AND is_from_guru = 1)
        ORDER BY pesan.created_at ASC
    ", [$siswaId, $guruId, $guruId, $siswaId]);

    $result = $query->getResultArray();

    // Tandai semua pesan dari siswa sebagai sudah dibaca
    $db->query("UPDATE pesan SET is_read = 1 WHERE siswa_id = ? AND guru_id = ? AND is_from_guru = 0", [$siswaId, $guruId]);

    return $this->response->setJSON($result);
}

    // ======== GURU KIRIM BALASAN ========
    public function reply()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized']);
        }

        $pesan = $this->request->getPost('pesan');
        $siswaId = $this->request->getPost('siswa_id');

        if (empty($pesan) || empty($siswaId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Data tidak lengkap']);
        }

        $model = new PesanModel();
        $model->save([
            'siswa_id'    => $siswaId,
            'guru_id'     => session()->get('user_id'),
            'pesan'       => $pesan,
            'is_read'     => 1, // Guru membaca pesan saat membalas
            'is_from_guru'=> 1,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        // Tandai semua pesan dari siswa ini sebagai sudah dibaca
        $model->where(['siswa_id' => $siswaId, 'guru_id' => session()->get('user_id'), 'is_from_guru' => 0])
            ->set(['is_read' => 1])
            ->update();

        return $this->response->setJSON(['status' => 'success', 'message' => 'Balasan terkirim']);
    }

    // ======== SISWA LIHAT RIWAYAT PESAN ========
    public function history()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $model = new PesanModel();
        $data['pesan'] = $model->getPesanBySiswa(session()->get('user_id'));
        $data['title'] = 'Riwayat Tanya Guru';
        return view('chat/history', $data);
    }

    // ======== GURU LIHAT PESAN (Ringkasan per siswa) ========

    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $guruId = session()->get('user_id');
        $db = \Config\Database::connect();

        // Query untuk mengambil daftar siswa yang pernah mengirim pesan ke guru ini
        // Serta menghitung jumlah pesan yang belum dibaca per siswa
        $query = $db->query("
            SELECT 
                siswa.id AS siswa_id,
                siswa.nama AS siswa_nama,
                (SELECT pesan FROM pesan WHERE guru_id = ? AND siswa_id = siswa.id ORDER BY created_at DESC LIMIT 1) AS pesan_terakhir,
                (SELECT created_at FROM pesan WHERE guru_id = ? AND siswa_id = siswa.id ORDER BY created_at DESC LIMIT 1) AS waktu_terakhir,
                (SELECT COUNT(*) FROM pesan WHERE guru_id = ? AND siswa_id = siswa.id AND is_read = 0 AND is_from_guru = 0) AS belum_dibaca
            FROM pengguna siswa
            INNER JOIN pesan ON pesan.siswa_id = siswa.id
            WHERE pesan.guru_id = ?
            AND siswa.peran = 'siswa'
            GROUP BY siswa.id
            ORDER BY waktu_terakhir DESC
        ", [$guruId, $guruId, $guruId, $guruId]);

        $dataSiswa = $query->getResultArray();

        // TIDAK MENANDAI SEMUA PESAN SEBAGAI DIBACA DI SINI
        // Pesan akan ditandai dibaca saat guru membuka modal chat (via historySiswa)

        $data = [
            'title' => 'Pesan dari Siswa',
            'siswa' => $dataSiswa
        ];

        return view('guru/pesan', $data);
    }

    // ======== GET UNREAD COUNT (Untuk AJAX) ========
    public function unreadCount()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return $this->response->setJSON(['count' => 0]);
        }

        $model = new PesanModel();
        $count = $model->countUnread(session()->get('user_id'));
        return $this->response->setJSON(['count' => $count]);
    }


    // ======== HISTORY JSON UNTUK MODAL SISWA ========
    public function historyJson()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return $this->response->setJSON([]);
        }

        $userId = session()->get('user_id');
        $model = new PesanModel();
        
        // Ambil semua pesan yang melibatkan siswa ini (baik sebagai siswa_id maupun guru_id)
        // Karena struktur data: pesan dari guru memiliki siswa_id = id guru, kita perlu query khusus
        // Atau lebih baik gunakan query manual
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT 
                pesan.*,
                CASE 
                    WHEN pesan.is_from_guru = 1 THEN 'Guru'
                    ELSE 'Saya'
                END as pengirim
            FROM pesan 
            WHERE pesan.siswa_id = ? OR (pesan.is_from_guru = 1 AND pesan.guru_id = ?)
            ORDER BY pesan.created_at ASC
        ", [$userId, $userId]);

        $result = $query->getResultArray();

        $output = [];
        foreach ($result as $p) {
            $output[] = [
                'pesan'      => $p['pesan'],
                'created_at' => date('d-m-Y H:i', strtotime($p['created_at'])),
                'pengirim'   => $p['pengirim'],
            ];
        }

        return $this->response->setJSON($output);
    }

    // ======== HISTORY JSON UNTUK GURU (berdasarkan siswa_id) ========
    public function historyJsonGuru()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return $this->response->setJSON([]);
        }

        $siswaId = $this->request->getGet('siswa_id');
        $guruId = session()->get('user_id');

        if (!$siswaId) {
            return $this->response->setJSON([]);
        }

        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT 
                pesan.*,
                CASE 
                    WHEN pesan.is_from_guru = 1 THEN 'Guru'
                    ELSE 'Siswa'
                END as pengirim
            FROM pesan 
            WHERE siswa_id = ? AND guru_id = ?
            ORDER BY pesan.created_at ASC
        ", [$siswaId, $guruId]);

        $result = $query->getResultArray();

        $output = [];
        foreach ($result as $p) {
            $output[] = [
                'pesan'      => $p['pesan'],
                'created_at' => date('d-m-Y H:i', strtotime($p['created_at'])),
                'pengirim'   => $p['pengirim'],
            ];
        }

        return $this->response->setJSON($output);
    }
}