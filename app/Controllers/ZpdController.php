<?php

namespace App\Controllers;

use App\Models\ZpdSoalModel;
use App\Models\ZpdResultModel;
use App\Models\VarkResultModel;

class ZpdController extends BaseController
{
    // ======== HALAMAN INTRO ZPD (Halaman 6) ========
    public function test($moduleId = 1)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        // Cek apakah sudah pernah mengerjakan ZPD modul ini
        $resultModel = new ZpdResultModel();
        $existing = $resultModel->where([
            'pengguna_id' => session()->get('user_id'),
            'modul_id' => $moduleId
        ])->first();

        if ($existing) {
            return redirect()->to('/siswa/modul')->with('info', "Anda sudah mengerjakan ZPD Modul {$moduleId}.");
        }

        // Cek apakah VARK sudah dikerjakan
        $varkModel = new VarkResultModel();
        $vark = $varkModel->where('pengguna_id', session()->get('user_id'))->first();
        if (!$vark) {
            return redirect()->to('/vark')->with('error', 'Silakan kerjakan tes VARK terlebih dahulu!');
        }

        $modulNames = [
            1 => 'Struktur & Fungsi Sel',
            2 => 'Organel Sel',
            3 => 'Transpor Membran'
        ];

        $data = [
            'title' => 'Tes ZPD - Modul ' . $moduleId,
            'modul_id' => $moduleId,
            'modul_name' => $modulNames[$moduleId] ?? 'Modul ' . $moduleId,
        ];

        return view('zpd/intro', $data);
    }

    // ======== MULAI TES ZPD (SET TIMER) ========
    public function start($moduleId = 1)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $resultModel = new ZpdResultModel();
        $existing = $resultModel->where([
            'pengguna_id' => session()->get('user_id'),
            'modul_id' => $moduleId
        ])->first();

        if ($existing) {
            return redirect()->to('/siswa/modul')->with('info', "Anda sudah mengerjakan ZPD Modul {$moduleId}.");
        }

        // Set start_time di session (key unik per modul)
        $sessionKey = 'zpd_start_' . $moduleId;
        session()->set($sessionKey, time());

        return redirect()->to('/zpd/soal/' . $moduleId);
    }

    // ======== HALAMAN SOAL ZPD (Halaman 7) ========
    public function soal($moduleId = 1)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $resultModel = new ZpdResultModel();
        $existing = $resultModel->where([
            'pengguna_id' => session()->get('user_id'),
            'modul_id' => $moduleId
        ])->first();

        if ($existing) {
            return redirect()->to('/siswa/modul')->with('info', "Anda sudah mengerjakan ZPD Modul {$moduleId}.");
        }

        // Cek timer
        $sessionKey = 'zpd_start_' . $moduleId;
        $startTime = session()->get($sessionKey);

        if (!$startTime) {
            return redirect()->to('/zpd/test/' . $moduleId);
        }

        $elapsed = time() - $startTime;
        $remaining = 600 - $elapsed; // 10 menit

        if ($remaining <= 0) {
            session()->remove($sessionKey);
            return redirect()->to('/zpd/hasil/' . $moduleId . '?timeout=1');
        }

        // Ambil soal
        $soalModel = new ZpdSoalModel();
        $questions = $soalModel->where('modul_id', $moduleId)
                               ->orderBy('level_zpd', 'ASC')
                               ->findAll();

        if (empty($questions)) {
            return redirect()->to('/siswa/modul')->with('error', 'Soal ZPD belum tersedia untuk modul ini.');
        }

        $data = [
            'title' => 'Soal ZPD - Modul ' . $moduleId,
            'questions' => $questions,
            'module_id' => $moduleId,
            'remaining' => $remaining,
            'start_time' => $startTime,
        ];

        return view('zpd/soal', $data);
    }

    // ======== PROSES SUBMIT ZPD ========
    public function submit($moduleId = 1)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $resultModel = new ZpdResultModel();
        $existing = $resultModel->where([
            'pengguna_id' => session()->get('user_id'),
            'modul_id' => $moduleId
        ])->first();

        if ($existing) {
            return redirect()->to('/siswa/modul')->with('info', "Anda sudah mengerjakan ZPD Modul {$moduleId}.");
        }

        // Cek timer
        $sessionKey = 'zpd_start_' . $moduleId;
        $startTime = session()->get($sessionKey);

        if (!$startTime) {
            return redirect()->to('/zpd/test/' . $moduleId);
        }

        $elapsed = time() - $startTime;
        $remaining = 600 - $elapsed;

        // Jika waktu habis, tetap proses tapi tandai timeout
        $isTimeout = ($remaining <= 0);
        if ($isTimeout) {
            session()->remove($sessionKey);
            // Lanjutkan proses (tetap simpan jawaban yang ada)
        }

        $answers = $this->request->getPost('answers');

        if (!$answers) {
            return redirect()->to('/zpd/soal/' . $moduleId)->with('error', 'Silakan jawab setidaknya satu pertanyaan!');
        }

        // Ambil kunci jawaban
        $soalModel = new ZpdSoalModel();
        $questions = $soalModel->where('modul_id', $moduleId)
                               ->orderBy('id', 'ASC')
                               ->findAll();

        $correctKeys = [];
        $weights = [];
        foreach ($questions as $q) {
            $correctKeys[] = $q['jawaban_benar'];
            $weights[] = $q['bobot_nilai'];
        }

        // Hitung skor
        $totalScore = 0;
        $answeredCount = 0;
        for ($i = 0; $i < 10; $i++) {
            $idx = $i + 1;
            if (isset($answers[$idx]) && !empty($answers[$idx])) {
                $answeredCount++;
                if (strtoupper($answers[$idx]) === $correctKeys[$i]) {
                    $totalScore += $weights[$i];
                }
            }
        }

        // Tentukan level ZPD (menggunakan aturan di proposal)
        if ($totalScore < 50) {
            $zpdLevel = 'novice';
        } elseif ($totalScore <= 84) {
            $zpdLevel = 'apprentice';
        } else {
            $zpdLevel = 'master';
        }

        // Simpan hasil
        $resultModel->save([
            'pengguna_id' => session()->get('user_id'),
            'modul_id' => $moduleId,
            'total_nilai' => $totalScore,
            'level_zpd' => $zpdLevel,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Hapus session timer
        session()->remove($sessionKey);

        // Tampilkan hasil
        $data = [
            'title' => 'Hasil ZPD - Modul ' . $moduleId,
            'module_id' => $moduleId,
            'score' => $totalScore,
            'level' => $zpdLevel,
            'answered' => $answeredCount,
            'is_timeout' => $isTimeout,
            'nama' => session()->get('name')
        ];

        return view('zpd/hasil', $data);
    }

    // ======== HALAMAN HASIL ZPD (Halaman 8) ========
    public function hasil($moduleId = 1)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $timeout = $this->request->getGet('timeout');
        if ($timeout) {
            $sessionKey = 'zpd_start_' . $moduleId;
            session()->remove($sessionKey);
            return view('zpd/hasil_timeout', [
                'title' => 'Waktu Habis - ZPD Modul ' . $moduleId,
                'module_id' => $moduleId
            ]);
        }

        return redirect()->to('/siswa/modul');
    }
}