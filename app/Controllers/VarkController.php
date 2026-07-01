<?php

namespace App\Controllers;

use App\Models\VarkResultModel;
use App\Models\VarkSoalModel;

class VarkController extends BaseController
{
    // ======== HALAMAN INTRO VARK (Page 3) ========
    public function index()
    {
        // Cek login
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        // Cek apakah sudah pernah mengerjakan VARK
        $model = new VarkResultModel();
        $existing = $model->where('pengguna_id', session()->get('user_id'))->first();

        if ($existing) {
            // Jika sudah, arahkan ke halaman pilih modul dengan pesan
            return redirect()->to('/siswa/modul')->with('info', 'Anda sudah mengerjakan tes VARK. Hasil: ' . $existing['kategori_hasil']);
        }

        $data['title'] = 'Tes VARK - Sistem Adaptif';
        return view('vark/intro', $data);
    }

    // ======== MULAI TES VARK (SET TIMER) ========
    public function start()
    {
        // Cek login
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        // Cek apakah sudah pernah mengerjakan VARK
        $model = new VarkResultModel();
        $existing = $model->where('pengguna_id', session()->get('user_id'))->first();
        if ($existing) {
            return redirect()->to('/siswa/modul')->with('info', 'Anda sudah mengerjakan tes VARK. Hasil: ' . $existing['kategori_hasil']);
        }

        // Set waktu mulai tes (server timestamp dalam detik)
        session()->set('vark_start_time', time());

        // Redirect ke halaman soal
        return redirect()->to('/vark/soal');
    }

    // ======== HALAMAN SOAL VARK (Page 4) ========
    public function soal()
    {
        // Cek login
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        // Cek apakah sudah pernah mengerjakan VARK
        $model = new VarkResultModel();
        $existing = $model->where('pengguna_id', session()->get('user_id'))->first();
        if ($existing) {
            return redirect()->to('/siswa/modul')->with('info', 'Anda sudah mengerjakan tes VARK.');
        }

        // Cek apakah timer sudah dimulai
        $startTime = session()->get('vark_start_time');
        if (!$startTime) {
            // Jika belum ada timer, arahkan ke start
            return redirect()->to('/vark/start');
        }

        // Hitung sisa waktu (10 menit = 600 detik)
        $elapsed = time() - $startTime;
        $remaining = 600 - $elapsed;

        if ($remaining <= 0) {
            // Waktu habis, arahkan ke halaman timeout
            return redirect()->to('/vark/hasil?timeout=1');
        }

        // Ambil soal dari database
        $soalModel = new VarkSoalModel();
        $questions = $soalModel->orderBy('nomor', 'ASC')->findAll();

        // Acak urutan soal (shuffle)
        shuffle($questions);

        $data = [
            'title'     => 'Soal Tes VARK',
            'questions' => $questions,
            'remaining' => $remaining, // kirim sisa waktu ke view
            'start_time' => $startTime,
        ];
        return view('vark/soal', $data);
    }

    // ======== PROSES SUBMIT VARK ========
    public function submit()
    {
        // Cek login
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        // Cek apakah sudah pernah mengerjakan VARK
        $model = new VarkResultModel();
        $existing = $model->where('pengguna_id', session()->get('user_id'))->first();
        if ($existing) {
            return redirect()->to('/siswa/modul')->with('info', 'Anda sudah mengerjakan tes VARK.');
        }

        // Cek waktu
        $startTime = session()->get('vark_start_time');
        if (!$startTime) {
            return redirect()->to('/vark/start');
        }

        $elapsed = time() - $startTime;
        $remaining = 600 - $elapsed;

        if ($remaining <= 0) {
            return redirect()->to('/vark/hasil?timeout=1');
        }

        $answers = $this->request->getPost('answers');

        // Validasi: harus ada 10 jawaban
        if (!$answers || count($answers) !== 10) {
            return redirect()->to('/vark/soal')->with('error', 'Silakan jawab semua pertanyaan!');
        }

        // Klasifikasi menggunakan helper
        $result = classify_vark($answers);

        // Simpan ke database
        $model->save([
            'pengguna_id'    => session()->get('user_id'),
            'skor_v'         => $result['scores']['V'],
            'skor_a'         => $result['scores']['A'],
            'skor_r'         => $result['scores']['R'],
            'skor_k'         => $result['scores']['K'],
            'selisih'        => $result['delta'],
            'kategori_hasil' => $result['label'],
            'tipe_hasil'     => $result['type'],
            'created_at'     => date('Y-m-d H:i:s'),
            'created_at'     => date('Y-m-d H:i:s')
        ]);

        // Hapus session timer setelah selesai
        session()->remove('vark_start_time');

        // Tampilkan halaman hasil
        $data = [
            'title' => 'Hasil Tes VARK',
            'result' => $result,
            'nama' => session()->get('name')
        ];
        return view('vark/hasil', $data);
    }

    // ======== HALAMAN HASIL (UNTUK TIMEOUT) ========
    public function hasil()
    {
        // Cek login
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $timeout = $this->request->getGet('timeout');
        if ($timeout) {
            // Hapus session timer
            session()->remove('vark_start_time');
            return view('vark/hasil_timeout', ['title' => 'Waktu Habis - Tes VARK']);
        }

        // Jika tidak ada parameter timeout, redirect ke modul
        return redirect()->to('/siswa/modul');
    }
}