<?php

namespace App\Controllers;

use App\Models\ZpdSoalModel;
use App\Models\ZpdResultModel;
use App\Models\PostTestModel;
use App\Models\VarkResultModel;
use App\Models\UserModel;

class PostTestController extends BaseController
{
    // ======== HALAMAN INTRO POST TEST ========
    public function intro($moduleId = 1)
    {
        // Cek login
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        // =============================================================
        // CEK ZPD MODUL 1 (SEBAGAI SYARAT UMUM)
        // =============================================================
        $zpdModel = new ZpdResultModel();
        $zpdModul1 = $zpdModel->where(['pengguna_id' => $userId, 'modul_id' => 1])->first();
        if (!$zpdModul1) {
            return redirect()->to('/zpd/test/1')->with('error', 'Silakan kerjakan tes ZPD Modul 1 terlebih dahulu!');
        }

        // =============================================================
        // CEK APAKAH MODUL INI SUDAH BISA DIAKSES
        // Modul 2: hanya jika Modul 1 sudah selesai
        // Modul 3: hanya jika Modul 2 sudah selesai
        // =============================================================
        if ($moduleId > 1) {
            $prevModuleId = $moduleId - 1;
            $prevZpd = $zpdModel->where(['pengguna_id' => $userId, 'modul_id' => $prevModuleId])->first();
            if (!$prevZpd) {
                return redirect()->to('/siswa/modul')->with('error', 'Selesaikan modul sebelumnya terlebih dahulu!');
            }
        }

        // =============================================================
        // CEK APAKAH SUDAH PERNAH MENGERJAKAN POST TEST MODUL INI
        // =============================================================
        $postTestModel = new PostTestModel();
        $existing = $postTestModel->getHasilByUserAndModul($userId, $moduleId);

        if ($existing) {
            // Jika status tidak lulus, boleh ulang (remedial)
            if ($existing['status'] == 'tidak_lulus') {
                // Hapus session timer lama
                $sessionKey = 'posttest_start_' . $moduleId;
                session()->remove($sessionKey);
                
                // Tampilkan halaman intro dengan pesan remedial
                $modulNames = [
                    1 => 'Struktur & Fungsi Sel',
                    2 => 'Organel Sel',
                    3 => 'Transpor Membran'
                ];

                $data = [
                    'title'       => 'Post Test Remedial - Modul ' . $moduleId,
                    'module_id'   => $moduleId,
                    'modul_name'  => $modulNames[$moduleId] ?? 'Modul ' . $moduleId,
                    'is_remedial' => true,
                ];

                return view('posttest/intro', $data);
            }

            // Jika sudah lulus, redirect ke hasil (tidak bisa ulang)
            return redirect()->to('/posttest/hasil/' . $moduleId . '?existing=1');
        }

        $modulNames = [
            1 => 'Struktur & Fungsi Sel',
            2 => 'Organel Sel',
            3 => 'Transpor Membran'
        ];

        $data = [
            'title'      => 'Post Test - Modul ' . $moduleId,
            'module_id'  => $moduleId,
            'modul_name' => $modulNames[$moduleId] ?? 'Modul ' . $moduleId,
            'is_remedial' => false,
        ];

        return view('posttest/intro', $data);
    }

    // ======== MULAI POST TEST ========
    public function start($moduleId = 1)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        // Cek apakah sudah pernah mengerjakan post test
        $postTestModel = new PostTestModel();
        $existing = $postTestModel->getHasilByUserAndModul($userId, $moduleId);
        if ($existing) {
            // Jika status tidak lulus, boleh ulang (remedial) - HAPUS DATA LAMA
            if ($existing['status'] == 'tidak_lulus') {
                // Hapus record post test lama agar bisa buat baru
                $postTestModel->delete($existing['id']);
            } else {
                // Jika sudah lulus, tidak boleh ulang
                return redirect()->to('/siswa/modul')->with('info', "Anda sudah lulus post test Modul {$moduleId}.");
            }
        }

        // Set start_time di session
        session()->set('posttest_start_' . $moduleId, time());

        return redirect()->to('/posttest/soal/' . $moduleId);
    }

    // ======== HALAMAN SOAL POST TEST ========
    public function soal($moduleId = 1)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        // Cek apakah sudah pernah mengerjakan post test
        $postTestModel = new PostTestModel();
        $existing = $postTestModel->getHasilByUserAndModul($userId, $moduleId);
        if ($existing) {
            return redirect()->to('/siswa/modul')->with('info', "Anda sudah mengerjakan post test Modul {$moduleId}.");
        }

        // Cek timer
        $sessionKey = 'posttest_start_' . $moduleId;
        $startTime = session()->get($sessionKey);
        if (!$startTime) {
            return redirect()->to('/posttest/intro/' . $moduleId);
        }

        $elapsed = time() - $startTime;
        $remaining = 600 - $elapsed; // 10 menit

        if ($remaining <= 0) {
            session()->remove($sessionKey);
            return redirect()->to('/posttest/hasil/' . $moduleId . '?timeout=1');
        }

        // Ambil soal dari database dan acak urutannya
        $soalModel = new ZpdSoalModel();
        $questions = $soalModel->where('modul_id', $moduleId)->findAll();
        
        // Acak urutan soal
        shuffle($questions);

        $data = [
            'title' => 'Post Test - Modul ' . $moduleId,
            'questions' => $questions,
            'module_id' => $moduleId,
            'remaining' => $remaining,
            'start_time' => $startTime,
        ];

        return view('posttest/soal', $data);
    }

    // ======== PROSES SUBMIT POST TEST ========
    public function submit($moduleId = 1)
    {
        // Cek login
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        // Cek apakah sudah pernah mengerjakan post test
        $postTestModel = new PostTestModel();
        $existing = $postTestModel->getHasilByUserAndModul($userId, $moduleId);
        if ($existing) {
            return redirect()->to('/siswa/modul')->with('info', "Anda sudah mengerjakan post test Modul {$moduleId}.");
        }

        // Cek timer
        $sessionKey = 'posttest_start_' . $moduleId;
        $startTime = session()->get($sessionKey);
        if (!$startTime) {
            return redirect()->to('/posttest/intro/' . $moduleId);
        }

        $elapsed = time() - $startTime;
        $remaining = 600 - $elapsed;

        // Jika waktu habis, tetap proses
        $isTimeout = ($remaining <= 0);
        if ($isTimeout) {
            session()->remove($sessionKey);
        }

        $answers = $this->request->getPost('answers');

        if (!$answers) {
            return redirect()->to('/posttest/hasil/' . $moduleId . '?timeout=1');
        }

        // Ambil soal dari database
        $soalModel = new ZpdSoalModel();
        $questions = $soalModel->where('modul_id', $moduleId)
                            ->orderBy('id', 'ASC')
                            ->findAll();

        if (empty($questions)) {
            return redirect()->to('/siswa/modul')->with('error', 'Soal post test tidak ditemukan.');
        }

        // =============================================================
        // HITUNG SKOR (menggunakan ID soal sebagai key)
        // =============================================================
        $totalScore = 0;
        $answeredCount = 0;

        foreach ($questions as $q) {
            $soalId = $q['id'];
            if (isset($answers[$soalId]) && !empty($answers[$soalId])) {
                $answeredCount++;
                if (strtoupper($answers[$soalId]) === $q['jawaban_benar']) {
                    $totalScore += $q['bobot_nilai'];
                }
            }
        }

        // Tentukan level ZPD hasil post test
        if ($totalScore < 50) {
            $levelAkhir = 'novice';
        } elseif ($totalScore <= 84) {
            $levelAkhir = 'apprentice';
        } else {
            $levelAkhir = 'master';
        }

        // =============================================================
        // AMBIL LEVEL AWAL DARI MODUL 1 (BUKAN MODUL INI)
        // =============================================================
        $zpdModel = new ZpdResultModel();
        $zpdModul1 = $zpdModel->where(['pengguna_id' => $userId, 'modul_id' => 1])->first();
        $levelAwal = $zpdModul1['level_zpd'] ?? 'novice';
        $skorPretest = $zpdModul1['total_nilai'] ?? 0;

        // =============================================================
        // TENTUKAN STATUS KELULUSAN
        // =============================================================
        $levelOrder = ['novice' => 1, 'apprentice' => 2, 'master' => 3];
        $levelAwalIndex = $levelOrder[$levelAwal] ?? 1;
        $levelAkhirIndex = $levelOrder[$levelAkhir] ?? 1;

        $status = 'lulus';

        if ($levelAkhirIndex < $levelAwalIndex) {
            $status = 'tidak_lulus';
        } elseif ($levelAkhirIndex > $levelAwalIndex) {
            $status = 'lulus';
        } else {
            // Level tetap
            if ($totalScore >= $skorPretest) {
                $status = 'lulus';
            } else {
                $status = 'tidak_lulus';
            }
        }

        // =============================================================
        // UPDATE ZPD DI DATABASE (gunakan Modul 1 sebagai acuan)
        // =============================================================
        // Perhatikan: kita update ZPD Modul 1, bukan Modul 2
        $zpdModel->where(['pengguna_id' => $userId, 'modul_id' => 1])
                ->set([
                    'level_zpd'   => $levelAkhir,
                    'total_nilai' => $totalScore
                ])
                ->update();

        // TAMBAHKAN: TANDAI MODUL YANG BARU DISELESAIKAN
        // (agar modul berikutnya terbuka)
        // =============================================================
        $cekZpdModul = $zpdModel->where(['pengguna_id' => $userId, 'modul_id' => $moduleId])->first();
        if (!$cekZpdModul) {
            $zpdModel->save([
                'pengguna_id' => $userId,
                'modul_id'    => $moduleId,
                'total_nilai' => $totalScore,
                'level_zpd'   => $levelAkhir,
                'created_at'  => date('Y-m-d H:i:s'),
            ]);
        }

        // Simpan hasil post test
        $postTestModel->save([
            'pengguna_id'    => $userId,
            'modul_id'       => $moduleId,
            'skor'           => $totalScore,
            'level_zpd_awal' => $levelAwal,
            'level_zpd_akhir'=> $levelAkhir,
            'status'         => $status,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        // Hapus session timer
        session()->remove($sessionKey);

        // Jika tidak lulus, kirim notifikasi ke guru
        if ($status == 'tidak_lulus') {
            $this->notifikasiGuru($userId, $moduleId, $levelAwal, $levelAkhir, $totalScore, $skorPretest);
        }

        // Tampilkan hasil
        $data = [
            'title' => 'Hasil Post Test - Modul ' . $moduleId,
            'module_id' => $moduleId,
            'score' => $totalScore,
            'skor_pretest' => $skorPretest,
            'level_awal' => $levelAwal,
            'level_akhir' => $levelAkhir,
            'status' => $status,
            'answered' => $answeredCount,
            'is_timeout' => $isTimeout,
            'nama' => session()->get('name'),
            'level_order' => $levelOrder,
        ];

        return view('posttest/hasil', $data);
    }

    // ======== HALAMAN HASIL POST TEST ========
    public function hasil($moduleId = 1)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        $timeout = $this->request->getGet('timeout');
        if ($timeout) {
            $sessionKey = 'posttest_start_' . $moduleId;
            session()->remove($sessionKey);
            return view('posttest/hasil_timeout', [
                'title' => 'Waktu Habis - Post Test Modul ' . $moduleId,
                'module_id' => $moduleId
            ]);
        }

        return redirect()->to('/siswa/modul');
    }

    // ======== NOTIFIKASI KE GURU ========
    private function notifikasiGuru($siswaId, $moduleId, $levelAwal, $levelAkhir, $skor)
    {
        $db = \Config\Database::connect();
        
        // Cari guru (ambil guru pertama)
        $userModel = new UserModel();
        $guru = $userModel->where('peran', 'guru')->first();
        if (!$guru) {
            return;
        }

        // Ambil nama siswa
        $siswa = $userModel->find($siswaId);
        $namaSiswa = $siswa['nama'] ?? 'Siswa';

        $modulNames = [
            1 => 'Struktur & Fungsi Sel',
            2 => 'Organel Sel',
            3 => 'Transpor Membran'
        ];
        $namaModul = $modulNames[$moduleId] ?? 'Modul ' . $moduleId;

        $levelLabels = [
            'novice' => 'Novice (Pemula)',
            'apprentice' => 'Apprentice (Menengah)',
            'master' => 'Master (Ahli)'
        ];

        $pesan = "⚠️ **Notifikasi Post Test**\n\n" .
                 "Siswa: {$namaSiswa}\n" .
                 "Modul: {$namaModul}\n" .
                 "Skor: {$skor}\n" .
                 "Level Awal: " . ($levelLabels[$levelAwal] ?? $levelAwal) . "\n" .
                 "Level Akhir: " . ($levelLabels[$levelAkhir] ?? $levelAkhir) . "\n" .
                 "Status: ❌ TIDAK LULUS (Level menurun)\n\n" .
                 "Siswa memerlukan intervensi remedial.";

        // Simpan notifikasi ke tabel pesan (untuk muncul di dashboard guru)
        $pesanModel = new \App\Models\PesanModel();
        $pesanModel->save([
            'siswa_id'    => $siswaId,
            'guru_id'     => $guru['id'],
            'pesan'       => $pesan,
            'is_read'     => 0,
            'is_from_guru'=> 0,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);
    }
}