<?php

namespace App\Controllers;

use App\Models\VarkSoalModel;
use App\Models\UserModel;
use App\Models\VarkResultModel;

class GuruController extends BaseController
{
    // ======== DASHBOARD GURU ========
    public function dashboard()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();

        // Query total siswa
        $query = $db->query("SELECT COUNT(*) as total FROM pengguna WHERE peran = 'siswa'");
        $result = $query->getRow();
        $totalSiswa = $result ? $result->total : 0;

        // Query tuntas VARK
        $query2 = $db->query("SELECT COUNT(DISTINCT pengguna_id) as total FROM vark_hasil");
        $result2 = $query2->getRow();
        $tuntasVark = $result2 ? $result2->total : 0;

        // Query perlu intervensi
        $query3 = $db->query("SELECT COUNT(DISTINCT pengguna_id) as total FROM vark_hasil WHERE tipe_hasil = 'M'");
        $result3 = $query3->getRow();
        $perluIntervensi = $result3 ? $result3->total : 0;

        $data = [
            'title'          => 'Dashboard Guru',
            'totalSiswa'     => $totalSiswa,
            'tuntasVark'     => $tuntasVark,
            'perluIntervensi'=> $perluIntervensi,
        ];

        return view('guru/dashboard', $data);
    }

    // ======== HALAMAN KELOLA SOAL VARK (LIST) ========
    public function varkSoal()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new VarkSoalModel();
        $data['soal'] = $model->orderBy('nomor', 'ASC')->findAll();
        $data['title'] = 'Kelola Soal VARK';
        return view('guru/vark_soal', $data);
    }

    // ======== HALAMAN TAMBAH SOAL VARK ========
    public function varkSoalTambah()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new VarkSoalModel();
        $last = $model->orderBy('nomor', 'DESC')->first();
        $nextNomor = $last ? $last['nomor'] + 1 : 1;

        $data = [
            'title' => 'Tambah Soal VARK',
            'nomor' => $nextNomor,
        ];
        return view('guru/vark_soal_tambah', $data);
    }

    // ======== PROSES SIMPAN SOAL VARK ========
    public function varkSoalSimpan()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new VarkSoalModel();

        $nomor = $this->request->getPost('nomor');
        $teks_soal = $this->request->getPost('teks_soal');
        $opsi_v = $this->request->getPost('opsi_v');
        $opsi_a = $this->request->getPost('opsi_a');
        $opsi_r = $this->request->getPost('opsi_r');
        $opsi_k = $this->request->getPost('opsi_k');

        if (empty($teks_soal) || empty($opsi_v) || empty($opsi_a) || empty($opsi_r) || empty($opsi_k)) {
            return redirect()->to('/guru/vark/soal/tambah')
                             ->with('error', 'Semua field wajib diisi!');
        }

        $cek = $model->where('nomor', $nomor)->first();
        if ($cek) {
            return redirect()->to('/guru/vark/soal/tambah')
                             ->with('error', 'Nomor soal sudah digunakan!');
        }

        $model->save([
            'nomor' => $nomor,
            'teks_soal' => $teks_soal,
            'opsi_v' => $opsi_v,
            'opsi_a' => $opsi_a,
            'opsi_r' => $opsi_r,
            'opsi_k' => $opsi_k,
        ]);

        return redirect()->to('/guru/vark/soal')->with('success', 'Soal berhasil ditambahkan!');
    }

    // ======== HALAMAN EDIT SOAL VARK ========
    public function varkSoalEdit($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new VarkSoalModel();
        $data['soal'] = $model->find($id);

        if (!$data['soal']) {
            return redirect()->to('/guru/vark/soal')->with('error', 'Soal tidak ditemukan!');
        }

        $data['title'] = 'Edit Soal VARK';
        return view('guru/vark_soal_edit', $data);
    }

    // ======== PROSES UPDATE SOAL VARK ========
    public function varkSoalUpdate($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new VarkSoalModel();

        $teks_soal = $this->request->getPost('teks_soal');
        $opsi_v = $this->request->getPost('opsi_v');
        $opsi_a = $this->request->getPost('opsi_a');
        $opsi_r = $this->request->getPost('opsi_r');
        $opsi_k = $this->request->getPost('opsi_k');

        if (empty($teks_soal) || empty($opsi_v) || empty($opsi_a) || empty($opsi_r) || empty($opsi_k)) {
            return redirect()->to('/guru/vark/soal/edit/' . $id)
                             ->with('error', 'Semua field wajib diisi!');
        }

        $model->update($id, [
            'teks_soal' => $teks_soal,
            'opsi_v' => $opsi_v,
            'opsi_a' => $opsi_a,
            'opsi_r' => $opsi_r,
            'opsi_k' => $opsi_k,
        ]);

        return redirect()->to('/guru/vark/soal')->with('success', 'Soal berhasil diupdate!');
    }

    // ======== HAPUS SOAL VARK ========
    public function varkSoalHapus($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new VarkSoalModel();
        $model->delete($id);

        return redirect()->to('/guru/vark/soal')->with('success', 'Soal berhasil dihapus!');
    }

    // ======== HASIL VARK SISWA ========
    public function varkHasil()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $userModel = new UserModel();
        $varkModel = new VarkResultModel();

        $siswa = $userModel->where('peran', 'siswa')->findAll();

        $dataSiswa = [];
        foreach ($siswa as $s) {
            $vark = $varkModel->where('pengguna_id', $s['id'])
                            ->orderBy('id', 'DESC')
                            ->first();

            $dataSiswa[] = [
                'id' => $s['id'],
                'nama' => $s['nama'],
                'email' => $s['email'],
                'kelas' => $s['kelas'] ?? '-',
                'vark_hasil' => $vark ? $vark['kategori_hasil'] : 'Belum Tes',
                'tipe_hasil' => $vark ? $vark['tipe_hasil'] : '-',
                'skor_v' => $vark ? $vark['skor_v'] : '-',
                'skor_a' => $vark ? $vark['skor_a'] : '-',
                'skor_r' => $vark ? $vark['skor_r'] : '-',
                'skor_k' => $vark ? $vark['skor_k'] : '-',
                'tanggal' => $vark ? date('d-m-Y H:i', strtotime($vark['created_at'])) : '-'
            ];
        }

        $data = [
            'title' => 'Hasil VARK Siswa',
            'siswa' => $dataSiswa
        ];

        return view('guru/vark_hasil', $data);
    }

    // ======== KELOLA MATERI ADAPTIF ========
    public function materiIndex()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new \App\Models\MateriAdaptifModel();
        $data['konten'] = $model->getWithModul();
        $data['title'] = 'Kelola Materi Adaptif';
        return view('guru/materi_index', $data);
    }

    public function materiEdit($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new \App\Models\MateriAdaptifModel();
        $data['konten'] = $model->find($id);
        if (!$data['konten']) {
            return redirect()->to('/guru/materi')->with('error', 'Konten tidak ditemukan!');
        }

        $modulModel = new \App\Models\ModulModel();
        $data['modul'] = $modulModel->findAll();

        $data['title'] = 'Edit Materi Adaptif';
        return view('guru/materi_edit', $data);
    }

    public function materiUpdate($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new \App\Models\MateriAdaptifModel();
        $existing = $model->find($id);
        if (!$existing) {
            return redirect()->to('/guru/materi')->with('error', 'Konten tidak ditemukan!');
        }

        // Validasi field yang dapat diubah
        $rules = [
            'judul'          => 'required|max_length[255]',
            'tipe_tampilan'  => 'required|in_list[teks,gambar,audio,video,interaktif,hybrid]',
            'gambar_url'     => 'permit_empty|max_length[255]',
            'teks_konten'    => 'permit_empty',
            'audio_url'      => 'permit_empty|max_length[255]',
            'video_url'      => 'permit_empty|max_length[255]',
            'interaktif_url' => 'permit_empty|max_length[255]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/guru/materi/edit/' . $id)
                            ->withInput()
                            ->with('errors', $this->validator->getErrors());
        }

        // Siapkan data update (hanya field yang diizinkan)
        $updateData = [
            'judul'          => $this->request->getPost('judul'),
            'tipe_tampilan'  => $this->request->getPost('tipe_tampilan'),
            'gambar_url'     => $this->request->getPost('gambar_url'),
            'teks_konten'    => $this->request->getPost('teks_konten'),
            'audio_url'      => $this->request->getPost('audio_url'),
            'video_url'      => $this->request->getPost('video_url'),
            'interaktif_url' => $this->request->getPost('interaktif_url'),
        ];

        // Field kode_konten, modul_id, tipe_vark, level_zpd tidak diubah (diabaikan)

        $model->update($id, $updateData);

        return redirect()->to('/guru/materi')->with('success', 'Konten berhasil diupdate!');
    }

    // ======== KELOLA SOAL ZPD ========
    public function zpdSoal()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new \App\Models\ZpdSoalModel();
        $data['soal'] = $model->select('zpd_soal.*, modul.judul as modul_judul')
                            ->join('modul', 'modul.id = zpd_soal.modul_id')
                            ->orderBy('zpd_soal.modul_id', 'ASC')
                            ->orderBy('zpd_soal.level_zpd', 'ASC')
                            ->findAll();
        $data['title'] = 'Kelola Soal ZPD';
        return view('guru/zpd_soal', $data);
    }

    public function zpdSoalTambah()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $modulModel = new \App\Models\ModulModel();
        $data['modul'] = $modulModel->findAll();
        $data['title'] = 'Tambah Soal ZPD';
        return view('guru/zpd_soal_tambah', $data);
    }

    public function zpdSoalSimpan()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new \App\Models\ZpdSoalModel();

        $rules = [
            'modul_id'      => 'required|integer',
            'level_zpd'     => 'required|in_list[dasar,menengah,lanjut]',
            'bobot_nilai'   => 'required|integer|in_list[5,10,15]',
            'teks_soal'     => 'required',
            'opsi_a'        => 'required',
            'opsi_b'        => 'required',
            'opsi_c'        => 'required',
            'opsi_d'        => 'required',
            'jawaban_benar' => 'required|in_list[A,B,C,D]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/guru/zpd/soal/tambah')
                            ->withInput()
                            ->with('errors', $this->validator->getErrors());
        }

        $model->save([
            'modul_id'      => $this->request->getPost('modul_id'),
            'level_zpd'     => $this->request->getPost('level_zpd'),
            'bobot_nilai'   => $this->request->getPost('bobot_nilai'),
            'teks_soal'     => $this->request->getPost('teks_soal'),
            'opsi_a'        => $this->request->getPost('opsi_a'),
            'opsi_b'        => $this->request->getPost('opsi_b'),
            'opsi_c'        => $this->request->getPost('opsi_c'),
            'opsi_d'        => $this->request->getPost('opsi_d'),
            'jawaban_benar' => $this->request->getPost('jawaban_benar'),
        ]);

        return redirect()->to('/guru/zpd/soal')->with('success', 'Soal ZPD berhasil ditambahkan!');
    }

    public function zpdSoalEdit($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new \App\Models\ZpdSoalModel();
        $data['soal'] = $model->find($id);
        if (!$data['soal']) {
            return redirect()->to('/guru/zpd/soal')->with('error', 'Soal tidak ditemukan!');
        }

        $modulModel = new \App\Models\ModulModel();
        $data['modul'] = $modulModel->findAll();
        $data['title'] = 'Edit Soal ZPD';
        return view('guru/zpd_soal_edit', $data);
    }

    public function zpdSoalUpdate($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new \App\Models\ZpdSoalModel();

        $rules = [
            'modul_id'      => 'required|integer',
            'level_zpd'     => 'required|in_list[dasar,menengah,lanjut]',
            'bobot_nilai'   => 'required|integer|in_list[5,10,15]',
            'teks_soal'     => 'required',
            'opsi_a'        => 'required',
            'opsi_b'        => 'required',
            'opsi_c'        => 'required',
            'opsi_d'        => 'required',
            'jawaban_benar' => 'required|in_list[A,B,C,D]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to('/guru/zpd/soal/edit/' . $id)
                            ->withInput()
                            ->with('errors', $this->validator->getErrors());
        }

        $model->update($id, [
            'modul_id'      => $this->request->getPost('modul_id'),
            'level_zpd'     => $this->request->getPost('level_zpd'),
            'bobot_nilai'   => $this->request->getPost('bobot_nilai'),
            'teks_soal'     => $this->request->getPost('teks_soal'),
            'opsi_a'        => $this->request->getPost('opsi_a'),
            'opsi_b'        => $this->request->getPost('opsi_b'),
            'opsi_c'        => $this->request->getPost('opsi_c'),
            'opsi_d'        => $this->request->getPost('opsi_d'),
            'jawaban_benar' => $this->request->getPost('jawaban_benar'),
        ]);

        return redirect()->to('/guru/zpd/soal')->with('success', 'Soal ZPD berhasil diupdate!');
    }

    public function zpdSoalHapus($id)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new \App\Models\ZpdSoalModel();
        $model->delete($id);

        return redirect()->to('/guru/zpd/soal')->with('success', 'Soal ZPD berhasil dihapus!');
    }
}