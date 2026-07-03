<?php

namespace App\Controllers;

use App\Models\VarkResultModel;
use App\Models\VarkSoalModel;
use App\Models\VarkTestSessionModel;

class VarkController extends BaseController
{
    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $model = new VarkResultModel();
        $existing = $model->where('pengguna_id', session()->get('user_id'))->first();
        if ($existing) {
            return redirect()->to('/siswa/modul')->with('info', 'Anda sudah mengerjakan tes VARK. Hasil: ' . $existing['kategori_hasil']);
        }

        $data['title'] = 'Tes VARK - Sistem Adaptif';
        return view('vark/intro', $data);
    }

    public function start()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $model = new VarkResultModel();
        $existing = $model->where('pengguna_id', session()->get('user_id'))->first();
        if ($existing) {
            return redirect()->to('/siswa/modul')->with('info', 'Anda sudah mengerjakan tes VARK. Hasil: ' . $existing['kategori_hasil']);
        }

        $userId = session()->get('user_id');
        $sessionModel = new VarkTestSessionModel();

        // Cek apakah sudah ada sesi di database
        $existingSession = $sessionModel->where('user_id', $userId)->first();

        if ($existingSession) {
            // Jika sudah ada, langsung redirect ke soal (tanpa membuat start_time baru)
            return redirect()->to('/vark/soal');
        }

        // Belum ada sesi, buat baru
        $startTime = time();
        $sessionModel->save([
            'user_id' => $userId,
            'start_time' => $startTime,
            'question_order' => null,
        ]);

        session()->remove('vark_question_order');

        return redirect()->to('/vark/soal');
    }

    public function soal()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $model = new VarkResultModel();
        $existing = $model->where('pengguna_id', session()->get('user_id'))->first();
        if ($existing) {
            return redirect()->to('/siswa/modul')->with('info', 'Anda sudah mengerjakan tes VARK.');
        }

        $userId = session()->get('user_id');

        // Ambil sesi dari database
        $sessionModel = new VarkTestSessionModel();
        $sessionData = $sessionModel->where('user_id', $userId)->first();

        if (!$sessionData) {
            return redirect()->to('/vark/start');
        }

        $startTime = $sessionData['start_time'];
        $elapsed = time() - $startTime;
        $remaining = 600 - $elapsed;

        if ($remaining <= 0) {
            $sessionModel->delete($sessionData['id']);
            return redirect()->to('/vark/hasil?timeout=1');
        }

        // Ambil soal
        $soalModel = new VarkSoalModel();
        $allQuestions = $soalModel->orderBy('nomor', 'ASC')->findAll();

        $questionOrder = session()->get('vark_question_order');
        if (empty($questionOrder)) {
            $ids = array_column($allQuestions, 'id');
            shuffle($ids);
            session()->set('vark_question_order', $ids);
            $questionOrder = $ids;
        }

        $orderedQuestions = [];
        foreach ($questionOrder as $id) {
            foreach ($allQuestions as $q) {
                if ($q['id'] == $id) {
                    $orderedQuestions[] = $q;
                    break;
                }
            }
        }

        $data = [
            'title'      => 'Soal Tes VARK',
            'questions'  => $orderedQuestions,
            'remaining'  => $remaining,
            'start_time' => $startTime,
        ];

        return view('vark/soal', $data);
    }

    public function submit()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $model = new VarkResultModel();
        $existing = $model->where('pengguna_id', session()->get('user_id'))->first();
        if ($existing) {
            return redirect()->to('/siswa/modul')->with('info', 'Anda sudah mengerjakan tes VARK.');
        }

        $userId = session()->get('user_id');
        $sessionModel = new VarkTestSessionModel();
        $sessionData = $sessionModel->where('user_id', $userId)->first();

        if (!$sessionData) {
            return redirect()->to('/vark/start');
        }

        $startTime = $sessionData['start_time'];
        $elapsed = time() - $startTime;
        $remaining = 600 - $elapsed;

        if ($remaining <= 0) {
            $sessionModel->delete($sessionData['id']);
            return redirect()->to('/vark/hasil?timeout=1');
        }

        $answers = $this->request->getPost('answers');
        if (!$answers || count($answers) !== 10) {
            return redirect()->to('/vark/soal')->with('error', 'Silakan jawab semua pertanyaan!');
        }

        $result = classify_vark($answers);

        $model->save([
            'pengguna_id'    => $userId,
            'skor_v'         => $result['scores']['V'],
            'skor_a'         => $result['scores']['A'],
            'skor_r'         => $result['scores']['R'],
            'skor_k'         => $result['scores']['K'],
            'selisih'        => $result['delta'],
            'kategori_hasil' => $result['label'],
            'tipe_hasil'     => $result['type'],
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        // Hapus sesi di database
        $sessionModel->delete($sessionData['id']);
        session()->remove('vark_question_order');

        $data = [
            'title'  => 'Hasil Tes VARK',
            'result' => $result,
            'nama'   => session()->get('name')
        ];
        return view('vark/hasil', $data);
    }

    public function hasil()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $timeout = $this->request->getGet('timeout');
        if ($timeout) {
            $sessionModel = new VarkTestSessionModel();
            $sessionModel->where('user_id', session()->get('user_id'))->delete();
            session()->remove('vark_question_order');
            return view('vark/hasil_timeout', ['title' => 'Waktu Habis - Tes VARK']);
        }

        return redirect()->to('/siswa/modul');
    }
}