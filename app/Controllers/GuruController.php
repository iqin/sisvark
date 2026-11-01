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

        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        if (is_admin()) {
            // =========================================================
            // ADMIN: Statistik global (semua data)
            // =========================================================
            $totalSiswa      = $db->query("SELECT COUNT(*) AS total FROM pengguna WHERE peran = 'siswa'")->getRow()->total;
            $totalGuru       = $db->query("SELECT COUNT(*) AS total FROM pengguna WHERE peran = 'guru'")->getRow()->total;
            $totalKelas      = $db->query("SELECT COUNT(*) AS total FROM kelas WHERE is_active = 1")->getRow()->total;
            $tuntasVark      = $db->query("SELECT COUNT(DISTINCT pengguna_id) AS total FROM vark_hasil")->getRow()->total;
            $perluIntervensi = $db->query("SELECT COUNT(DISTINCT pengguna_id) AS total FROM vark_hasil WHERE tipe_hasil = 'M'")->getRow()->total;
        } else {
            // =========================================================
            // GURU BIASA: Statistik dari kelasnya saja
            // =========================================================
            $kelasModel = new \App\Models\KelasModel();
            $kelasIds = array_map('intval', array_column(
                $kelasModel->where('guru_id', $userId)->findAll(), 'id'
            ));

            if (!empty($kelasIds)) {
                $idsString = implode(',', $kelasIds);

                $totalSiswa = $db->query("
                    SELECT COUNT(DISTINCT siswa_id) AS total 
                    FROM kelas_anggota 
                    WHERE kelas_id IN ($idsString) AND status = 'aktif'
                ")->getRow()->total;

                $tuntasVark = $db->query("
                    SELECT COUNT(DISTINCT v.pengguna_id) AS total 
                    FROM vark_hasil v
                    INNER JOIN kelas_anggota ka ON ka.siswa_id = v.pengguna_id
                    WHERE ka.kelas_id IN ($idsString) AND ka.status = 'aktif'
                ")->getRow()->total;

                $perluIntervensi = $db->query("
                    SELECT COUNT(DISTINCT v.pengguna_id) AS total 
                    FROM vark_hasil v
                    INNER JOIN kelas_anggota ka ON ka.siswa_id = v.pengguna_id
                    WHERE ka.kelas_id IN ($idsString) 
                      AND ka.status = 'aktif'
                      AND v.tipe_hasil = 'M'
                ")->getRow()->total;
            } else {
                $totalSiswa = $tuntasVark = $perluIntervensi = 0;
            }

            $totalGuru  = 0;
            $totalKelas = count($kelasIds);
        }

        // Pesan baru
        $chatModel = new \App\Models\PesanModel();
        if (is_admin()) {
            // Admin: semua pesan yang belum dibaca ke guru manapun
            $pesanBaru = $db->query("
                SELECT COUNT(*) AS total FROM pesan 
                WHERE is_read = 0 AND is_from_guru = 0
            ")->getRow()->total;
        } else {
            $pesanBaru = $chatModel->countUnread($userId);
        }

        $data = [
            'title'           => 'Dashboard Guru',
            'totalSiswa'      => $totalSiswa,
            'totalGuru'       => $totalGuru,
            'totalKelas'      => $totalKelas,
            'tuntasVark'      => $tuntasVark,
            'perluIntervensi' => $perluIntervensi,
            'pesanBaru'       => $pesanBaru,
            'is_admin'        => is_admin(),
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
        $guard = require_admin();
        if ($guard !== true) return $guard;

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
        $guard = require_admin();
        if ($guard !== true) return $guard;

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
        $guard = require_admin();
        if ($guard !== true) return $guard;

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
        $guard = require_admin();
        if ($guard !== true) return $guard;

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

        $userModel    = new UserModel();
        $varkModel    = new VarkResultModel();
        $anggotaModel = new \App\Models\KelasAnggotaModel();
        $kelasModel   = new \App\Models\KelasModel();

        if (is_admin()) {
            // ADMIN: semua siswa
            $siswa = $userModel->where('peran', 'siswa')->orderBy('nama', 'ASC')->findAll();
        } else {
            // GURU: hanya siswa di kelasnya
            $kelasIds = array_column(
                $kelasModel->where('guru_id', session()->get('user_id'))->findAll(),
                'id'
            );

            $siswaIds = [];
            foreach ($kelasIds as $kid) {
                $siswaIds = array_merge($siswaIds, $anggotaModel->getSiswaIdsByKelas($kid));
            }
            $siswaIds = array_unique($siswaIds);

            if (!empty($siswaIds)) {
                $siswa = $userModel->where('peran', 'siswa')
                                   ->whereIn('id', $siswaIds)
                                   ->orderBy('nama', 'ASC')
                                   ->findAll();
            } else {
                $siswa = [];
            }
        }

        $dataSiswa = [];
        foreach ($siswa as $s) {
            $vark = $varkModel->where('pengguna_id', $s['id'])
                              ->orderBy('id', 'DESC')
                              ->first();

            // =============================================================
            // Tentukan nama sekolah:
            // 1. Coba ambil dari pengguna.sekolah
            // 2. Kalau kosong, fallback ke kelas_anggota -> kelas.nama
            // =============================================================
            $namaSekolah = trim($s['sekolah'] ?? '');
            if (empty($namaSekolah) || $namaSekolah === '-') {
                $anggota = $anggotaModel->where('siswa_id', $s['id'])
                                        ->where('status', 'aktif')
                                        ->first();
                if ($anggota) {
                    $kelasInfo   = $kelasModel->find($anggota['kelas_id']);
                    $namaSekolah = $kelasInfo['nama'] ?? '-';
                } else {
                    $namaSekolah = '-';
                }
            }

            $dataSiswa[] = [
                'id'         => $s['id'],
                'nama'       => $s['nama'],
                'email'      => $s['email'],
                'sekolah'    => $namaSekolah,  // <-- GANTI dari 'kelas'
                'vark_hasil' => $vark ? $vark['kategori_hasil'] : 'Belum Tes',
                'tipe_hasil' => $vark ? $vark['tipe_hasil'] : '-',
                'skor_v'     => $vark ? $vark['skor_v'] : '-',
                'skor_a'     => $vark ? $vark['skor_a'] : '-',
                'skor_r'     => $vark ? $vark['skor_r'] : '-',
                'skor_k'     => $vark ? $vark['skor_k'] : '-',
                'tanggal'    => $vark ? date('d-m-Y H:i', strtotime($vark['created_at'])) : '-',
            ];
        }

        $data = [
            'title'    => 'Hasil VARK Siswa',
            'siswa'    => $dataSiswa,
            'is_admin' => is_admin(),
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
        $guard = require_admin();
        if ($guard !== true) return $guard;

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

        // Load daftar gambar existing (multi-gambar)
        $gambarModel = new \App\Models\MateriAdaptifGambarModel();
        $data['gambar_list'] = $gambarModel->getByMateri((int) $id);

        $data['title'] = 'Edit Materi Adaptif';
        return view('guru/materi_edit', $data);
    }

    public function materiUpdate($id)
    {
        $guard = require_admin();
        if ($guard !== true) return $guard;

        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new \App\Models\MateriAdaptifModel();
        $existing = $model->find($id);
        if (!$existing) {
            return redirect()->to('/guru/materi')->with('error', 'Konten tidak ditemukan!');
        }

        // Deteksi form: lama (single) atau baru (multi-gambar)
        $isNewForm    = $this->request->getPost('form_version') === 'v2';
        $tipeTampilan = $this->request->getPost('tipe_tampilan');
        $isGambarMode = in_array($tipeTampilan, ['gambar', 'hybrid']);

        // Validasi dasar
        $rules = [
            'judul'          => 'required|max_length[255]',
            'tipe_tampilan'  => 'required|in_list[teks,gambar,audio,video,interaktif,hybrid]',
            'teks_konten'    => 'permit_empty',
            'audio_url'      => 'permit_empty|max_length[255]',
            'video_url'      => 'permit_empty|max_length[255]',
            'interaktif_url' => 'permit_empty|max_length[255]',
        ];

        if (!$isNewForm) {
            $rules['gambar_url'] = 'permit_empty|max_length[255]';
            $gambarFile = $this->request->getFile('gambar_file');
            if ($gambarFile && $gambarFile->isValid() && !$gambarFile->hasMoved()) {
                $rules['gambar_file'] = 'max_size[gambar_file,2048]|is_image[gambar_file]';
            }
        }

        $audioFile = $this->request->getFile('audio_file');
        if ($audioFile && $audioFile->isValid() && !$audioFile->hasMoved()) {
            $rules['audio_file'] = 'max_size[audio_file,5120]|ext_in[audio_file,mp3,wav,ogg]';
        }

        if (!$this->validate($rules)) {
            return redirect()->to('/guru/materi/edit/' . $id)
                            ->withInput()
                            ->with('errors', $this->validator->getErrors());
        }

        // Upload audio
        $audioPath = $this->request->getPost('audio_url') ?? ($existing['audio_url'] ?? '');
        if ($audioFile && $audioFile->isValid() && !$audioFile->hasMoved()) {
            $modulId    = $existing['modul_id'];
            $uploadPath = FCPATH . 'assets/audio/modul' . $modulId . '/';

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            $newName = $audioFile->getRandomName();
            if ($audioFile->move($uploadPath, $newName)) {
                if (!empty($existing['audio_url'])) {
                    $oldPath = FCPATH . ltrim($existing['audio_url'], '/');
                    if (is_file($oldPath)) @unlink($oldPath);
                }
                $audioPath = 'assets/audio/modul' . $modulId . '/' . $newName;
            } else {
                return redirect()->to('/guru/materi/edit/' . $id)
                                ->with('upload_error', 'Gagal upload audio!');
            }
        }

        // Proses gambar
        $gambarModel    = new \App\Models\MateriAdaptifGambarModel();
        $gambarUrlInduk = $existing['gambar_url'] ?? null;

        if ($isNewForm && $isGambarMode) {
            // ===== FORM BARU + MODE GAMBAR: multi-gambar =====
            $existingGambar = $gambarModel->getByMateri((int) $id);
            $existingById   = [];
            foreach ($existingGambar as $g) {
                $existingById[(int) $g['id']] = $g;
            }

            $existingIds    = $this->request->getPost('gambar_id')       ?? [];
            $judulLama      = $this->request->getPost('judul_lama')      ?? [];
            $penjelasanLama = $this->request->getPost('penjelasan_lama') ?? [];
            $hapusIds       = $this->request->getPost('hapus_gambar')    ?? [];
            $judulBaru      = $this->request->getPost('judul_baru')      ?? [];
            $penjelasanBaru = $this->request->getPost('penjelasan_baru') ?? [];
            $fileBaruArr    = $this->request->getFileMultiple('gambar_baru') ?? [];

            $rows           = [];
            $finalKeptPaths = [];

            // STEP 1: gambar existing yang dipertahankan
            foreach ($existingIds as $i => $gid) {
                $gid = (int) $gid;
                if ($gid <= 0) continue;
                if (in_array($gid, $hapusIds)) continue;
                if (!isset($existingById[$gid])) continue;

                $rows[] = [
                    'gambar_url' => $existingById[$gid]['gambar_url'],
                    'judul'      => trim($judulLama[$i] ?? '') ?: null,
                    'penjelasan' => trim($penjelasanLama[$i] ?? '') ?: null,
                    'urutan'     => count($rows),
                ];
                $finalKeptPaths[] = $existingById[$gid]['gambar_url'];
            }

            // STEP 2: file baru
            $uploadPath = FCPATH . 'assets/images/modul' . $existing['modul_id'] . '/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            foreach ($fileBaruArr as $i => $file) {
                if (!$file || !$file->isValid() || $file->hasMoved()) continue;

                if ($file->getSize() > 2 * 1024 * 1024) {
                    return redirect()->to('/guru/materi/edit/' . $id)
                                    ->withInput()
                                    ->with('upload_error',
                                        'Ukuran file melebihi 2MB: ' . $file->getClientName());
                }
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    return redirect()->to('/guru/materi/edit/' . $id)
                                    ->withInput()
                                    ->with('upload_error',
                                        'Format tidak didukung: ' . $file->getClientName());
                }

                $newName = $file->getRandomName();
                if ($file->move($uploadPath, $newName)) {
                    $rows[] = [
                        'gambar_url' => 'assets/images/modul' . $existing['modul_id'] . '/' . $newName,
                        'judul'      => trim($judulBaru[$i] ?? '') ?: null,
                        'penjelasan' => trim($penjelasanBaru[$i] ?? '') ?: null,
                        'urutan'     => count($rows),
                    ];
                } else {
                    return redirect()->to('/guru/materi/edit/' . $id)
                                    ->withInput()
                                    ->with('upload_error',
                                        'Gagal upload file: ' . $file->getClientName());
                }
            }

            // Auto-migrate gambar tunggal lama → jadi gambar pertama galeri
            if (empty($rows) && !empty($existing['gambar_url'])) {
                $rows[] = [
                    'gambar_url' => $existing['gambar_url'],
                    'judul'      => null,
                    'penjelasan' => null,
                    'urutan'     => 0,
                ];
            }

            // STEP 3: validasi hasil
            if (empty($rows)) {
                return redirect()->to('/guru/materi/edit/' . $id)
                                ->withInput()
                                ->with('upload_error',
                                    'Minimal satu gambar harus ada untuk tipe tampilan ini.');
            }
            if (count($rows) > 10) {
                return redirect()->to('/guru/materi/edit/' . $id)
                                ->withInput()
                                ->with('upload_error', 'Maksimal 10 gambar per materi.');
            }

            // STEP 4: hapus file fisik yang dibuang
            foreach ($existingGambar as $oldG) {
                if (!in_array($oldG['gambar_url'], $finalKeptPaths)) {
                    $oldPath = FCPATH . ltrim($oldG['gambar_url'], '/');
                    if (is_file($oldPath)) @unlink($oldPath);
                }
            }

            // STEP 5: simpan ke tabel child
            if (!$gambarModel->replaceAll((int) $id, $rows)) {
                return redirect()->to('/guru/materi/edit/' . $id)
                                ->withInput()
                                ->with('upload_error', 'Gagal menyimpan daftar gambar.');
            }

            $gambarUrlInduk = null;

        } elseif ($isNewForm) {
            // Form baru tapi tipe bukan gambar/hybrid: jangan sentuh gambar_url & children
            $gambarUrlInduk = $existing['gambar_url'] ?? null;

        } else {
            // ===== FORM LAMA: single image (backward compat) =====
            $gambarUrlInduk = $this->request->getPost('gambar_url')
                              ?? ($existing['gambar_url'] ?? null);

            $gambarFile = $this->request->getFile('gambar_file');
            if ($gambarFile && $gambarFile->isValid() && !$gambarFile->hasMoved()) {
                $modulId    = $existing['modul_id'];
                $uploadPath = FCPATH . 'assets/images/modul' . $modulId . '/';

                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                $newName = $gambarFile->getRandomName();
                if ($gambarFile->move($uploadPath, $newName)) {
                    if (!empty($existing['gambar_url'])) {
                        $oldPath = FCPATH . ltrim($existing['gambar_url'], '/');
                        if (is_file($oldPath)) @unlink($oldPath);
                    }
                    $gambarUrlInduk = 'assets/images/modul' . $modulId . '/' . $newName;
                } else {
                    return redirect()->to('/guru/materi/edit/' . $id)
                                    ->with('upload_error', 'Gagal upload gambar!');
                }
            }

            $existingChild = $gambarModel->getByMateri((int) $id);
            if (!empty($existingChild)) {
                foreach ($existingChild as $g) {
                    $oldPath = FCPATH . ltrim($g['gambar_url'], '/');
                    if (is_file($oldPath)) @unlink($oldPath);
                }
                $gambarModel->deleteByMateri((int) $id);
            }
        }

        // Update induk
        $updateData = [
            'judul'          => $this->request->getPost('judul'),
            'tipe_tampilan'  => $tipeTampilan,
            'teks_konten'    => $this->request->getPost('teks_konten'),
            'gambar_url'     => $gambarUrlInduk,
            'audio_url'      => $audioPath,
            'video_url'      => $this->request->getPost('video_url')      ?: ($existing['video_url']      ?? null),
            'interaktif_url' => $this->request->getPost('interaktif_url') ?: ($existing['interaktif_url'] ?? null),
        ];

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
        $guard = require_admin();
        if ($guard !== true) return $guard;

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
            $guard = require_admin();
        if ($guard !== true) return $guard;

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
        $guard = require_admin();
        if ($guard !== true) return $guard;

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
        $guard = require_admin();
        if ($guard !== true) return $guard;

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
        $guard = require_admin();
        if ($guard !== true) return $guard;

        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $model = new \App\Models\ZpdSoalModel();
        $model->delete($id);

        return redirect()->to('/guru/zpd/soal')->with('success', 'Soal ZPD berhasil dihapus!');
    }
}