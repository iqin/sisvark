<?php

namespace App\Controllers;

use App\Models\ZpdSoalModel;
use App\Models\ZpdResultModel;
use App\Models\VarkResultModel;
use App\Models\ZpdTestSessionModel;

class ZpdController extends BaseController
{
    // ======== HALAMAN INTRO ZPD ========
    public function test($moduleId = 1)
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

    // ======== MULAI TES ZPD (SIMPAN START_TIME DI DATABASE) ========
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

        $userId = session()->get('user_id');
        $sessionModel = new ZpdTestSessionModel();

        // Cek apakah sudah ada sesi di database untuk modul ini
        $existingSession = $sessionModel->where([
            'user_id' => $userId,
            'module_id' => $moduleId
        ])->first();

        if ($existingSession) {
            // Jika sudah ada, langsung redirect ke soal (tanpa membuat start_time baru)
            return redirect()->to('/zpd/soal/' . $moduleId);
        }

        // Belum ada sesi, buat baru
        $startTime = time();
        $sessionModel->save([
            'user_id' => $userId,
            'module_id' => $moduleId,
            'start_time' => $startTime,
        ]);

        return redirect()->to('/zpd/soal/' . $moduleId);
    }

    // ======== HALAMAN SOAL ZPD ========
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

        $userId = session()->get('user_id');
        $sessionModel = new ZpdTestSessionModel();

        // Ambil sesi dari database
        $sessionData = $sessionModel->where([
            'user_id' => $userId,
            'module_id' => $moduleId
        ])->first();

        if (!$sessionData) {
            return redirect()->to('/zpd/test/' . $moduleId);
        }

        $startTime = $sessionData['start_time'];
        $elapsed = time() - $startTime;
        $remaining = 600 - $elapsed;

        if ($remaining <= 0) {
            $sessionModel->delete($sessionData['id']);
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

        $userId = session()->get('user_id');
        $sessionModel = new ZpdTestSessionModel();

        // Ambil sesi dari database
        $sessionData = $sessionModel->where([
            'user_id' => $userId,
            'module_id' => $moduleId
        ])->first();

        if (!$sessionData) {
            return redirect()->to('/zpd/test/' . $moduleId);
        }

        $startTime = $sessionData['start_time'];
        $elapsed = time() - $startTime;
        $remaining = 600 - $elapsed;

        // Jika waktu habis, tetap proses jawaban yang ada
        $isTimeout = ($remaining <= 0);
        if ($isTimeout) {
            // Lanjutkan proses (tetap simpan jawaban yang ada)
            // Jangan hapus sesi dulu, nanti dihapus setelah selesai
        }

        $answers = $this->request->getPost('answers');

        // Jika tidak ada jawaban sama sekali, redirect timeout
        if (!$answers) {
            $sessionModel->delete($sessionData['id']);
            return redirect()->to('/zpd/hasil/' . $moduleId . '?timeout=1');
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

        // Tentukan level ZPD
        if ($totalScore < 50) {
            $zpdLevel = 'novice';
        } elseif ($totalScore <= 84) {
            $zpdLevel = 'apprentice';
        } else {
            $zpdLevel = 'master';
        }

        // Simpan hasil
        $resultModel->save([
            'pengguna_id' => $userId,
            'modul_id' => $moduleId,
            'total_nilai' => $totalScore,
            'level_zpd' => $zpdLevel,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Hapus sesi di database setelah selesai
        $sessionModel->delete($sessionData['id']);

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

    // ======== HALAMAN HASIL ZPD (TIMEOUT) ========
    public function hasil($moduleId = 1)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $timeout = $this->request->getGet('timeout');
        if ($timeout) {
            $sessionModel = new ZpdTestSessionModel();
            $sessionModel->where([
                'user_id' => session()->get('user_id'),
                'module_id' => $moduleId
            ])->delete();
            return view('zpd/hasil_timeout', [
                'title' => 'Waktu Habis - ZPD Modul ' . $moduleId,
                'module_id' => $moduleId
            ]);
        }

        return redirect()->to('/siswa/modul');
    }
}