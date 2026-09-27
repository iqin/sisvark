<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\KelasAnggotaModel;

class KelasController extends BaseController
{
    // =============================================================
    // Daftar Kelas
    // Admin: semua kelas | Guru: kelasnya saja
    // =============================================================
    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $kelasModel = new KelasModel();

        if (is_admin()) {
            $kelas = $kelasModel->getAllWithStats();
        } else {
            $kelas = $kelasModel->getWithStats(session()->get('user_id'));
        }

        return view('guru/kelas_index', [
            'title' => 'Daftar Kelas',
            'kelas' => $kelas,
        ]);
    }

    // =============================================================
    // Detail Kelas + Daftar Siswa
    // Admin: semua kelas | Guru: kelasnya saja
    // =============================================================
    public function show($kelasId = null)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $kelasModel = new KelasModel();
        $kelas = $kelasModel->find($kelasId);

        if (!$kelas) {
            return redirect()->to('/guru/kelas')->with('error', 'Kelas tidak ditemukan.');
        }

        if (!is_admin() && (int) $kelas['guru_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/guru/kelas')->with('error', 'Anda tidak berhak mengakses kelas ini.');
        }

        $anggotaModel = new KelasAnggotaModel();

        return view('guru/kelas_detail', [
            'title' => 'Detail Kelas: ' . $kelas['nama'],
            'kelas' => $kelas,
            'siswa' => $anggotaModel->getSiswaByKelas($kelasId),
        ]);
    }

    // =============================================================
    // Toggle Aktif / Arsip Kelas — ADMIN ONLY
    // =============================================================
    public function toggleActive($kelasId = null)
    {
        $guard = require_admin();
        if ($guard !== true) return $guard;

        $kelasModel = new KelasModel();
        $kelas = $kelasModel->find($kelasId);

        if (!$kelas) {
            return redirect()->to('/guru/kelas')->with('error', 'Kelas tidak ditemukan.');
        }

        $newStatus = $kelas['is_active'] ? 0 : 1;
        $kelasModel->update($kelasId, ['is_active' => $newStatus]);

        $msg = $newStatus ? 'Kelas berhasil diaktifkan kembali.' : 'Kelas berhasil diarsipkan.';
        return redirect()->to('/guru/kelas')->with('success', $msg);
    }

    // =============================================================
    // Regenerate Kode Kelas — ADMIN ONLY
    // =============================================================
    public function regenerateCode($kelasId = null)
    {
        $guard = require_admin();
        if ($guard !== true) return $guard;

        $kelasModel = new KelasModel();
        $kelas = $kelasModel->find($kelasId);

        if (!$kelas) {
            return redirect()->to('/guru/kelas')->with('error', 'Kelas tidak ditemukan.');
        }

        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $kelas['mata_pelajaran']), 0, 3));
        if (empty($prefix)) {
            $prefix = 'KLS';
        }

        $kodeBaru = $kelasModel->generateKode($prefix);
        $kelasModel->update($kelasId, ['kode_kelas' => $kodeBaru]);

        return redirect()->to('/guru/kelas/detail/' . $kelasId)
                         ->with('success', 'Kode kelas berhasil diganti: <strong>' . esc($kodeBaru) . '</strong>');
    }

    // =============================================================
    // Keluarkan Siswa dari Kelas — Guru kelas atau Admin
    // =============================================================
    public function kick($kelasId = null, $siswaId = null)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $kelasModel = new KelasModel();
        $kelas = $kelasModel->find($kelasId);

        if (!$kelas) {
            return redirect()->to('/guru/kelas')->with('error', 'Kelas tidak ditemukan.');
        }

        // Guard: admin boleh semua, guru hanya kelasnya
        if (!is_admin() && (int) $kelas['guru_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/guru/kelas')->with('error', 'Anda tidak berhak mengelola kelas ini.');
        }

        $anggotaModel = new KelasAnggotaModel();
        $anggota = $anggotaModel->findAnggota($kelasId, $siswaId);
        if (!$anggota) {
            return redirect()->to('/guru/kelas/detail/' . $kelasId)
                             ->with('error', 'Siswa tidak tergabung di kelas ini.');
        }

        // Ambil nama siswa untuk pesan
        $userModel = new \App\Models\UserModel();
        $siswa = $userModel->find($siswaId);
        $namaSiswa = $siswa['nama'] ?? 'Siswa';

        // Update status jadi keluar (akun tetap ada, hanya dikeluarkan dari kelas)
        $anggotaModel->update($anggota['id'], ['status' => 'keluar']);

        return redirect()->to('/guru/kelas/detail/' . $kelasId)
                         ->with('success', 'Siswa <strong>' . esc($namaSiswa) . '</strong> berhasil dikeluarkan dari kelas.');
    }

        // =============================================================
    // Aktivasi Siswa — Guru kelas atau Admin
    // =============================================================
    public function aktivasiSiswa($kelasId = null, $siswaId = null)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        // Cek kelas
        $kelasModel = new KelasModel();
        $kelas = $kelasModel->find($kelasId);

        if (!$kelas) {
            return redirect()->to('/guru/kelas')->with('error', 'Kelas tidak ditemukan.');
        }

        // Guard: admin boleh semua, guru hanya kelasnya
        if (!is_admin() && (int) $kelas['guru_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/guru/kelas')->with('error', 'Anda tidak berhak mengelola kelas ini.');
        }

        // Cek siswa ada di kelas ini
        $anggotaModel = new KelasAnggotaModel();
        $anggota = $anggotaModel->findAnggota($kelasId, $siswaId);
        if (!$anggota) {
            return redirect()->to('/guru/kelas/detail/' . $kelasId)
                             ->with('error', 'Siswa tidak tergabung di kelas ini.');
        }

        // Aktifkan siswa
        $userModel = new \App\Models\UserModel();
        $userModel->update($siswaId, ['is_active' => 1]);

        return redirect()->to('/guru/kelas/detail/' . $kelasId)
                         ->with('success', 'Akun siswa berhasil diaktifkan. Siswa sekarang bisa login.');
    }

    // =============================================================
    // Tolak / Hapus Siswa — Guru kelas atau Admin
    // =============================================================
    public function tolakSiswa($kelasId = null, $siswaId = null)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        // Cek kelas
        $kelasModel = new KelasModel();
        $kelas = $kelasModel->find($kelasId);

        if (!$kelas) {
            return redirect()->to('/guru/kelas')->with('error', 'Kelas tidak ditemukan.');
        }

        // Guard: admin boleh semua, guru hanya kelasnya
        if (!is_admin() && (int) $kelas['guru_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/guru/kelas')->with('error', 'Anda tidak berhak mengelola kelas ini.');
        }

        // Cek siswa ada di kelas ini
        $anggotaModel = new KelasAnggotaModel();
        $anggota = $anggotaModel->findAnggota($kelasId, $siswaId);
        if (!$anggota) {
            return redirect()->to('/guru/kelas/detail/' . $kelasId)
                             ->with('error', 'Siswa tidak tergabung di kelas ini.');
        }

        // Hapus siswa dari database (cascade akan hapus kelas_anggota, VARK, ZPD, dll.)
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($siswaId);

        if (!$user || $user['peran'] !== 'siswa') {
            return redirect()->to('/guru/kelas/detail/' . $kelasId)
                             ->with('error', 'Data siswa tidak valid.');
        }

        $namaSiswa = $user['nama'];
        $userModel->delete($siswaId);

        return redirect()->to('/guru/kelas/detail/' . $kelasId)
                         ->with('success', 'Siswa <strong>' . esc($namaSiswa) . '</strong> berhasil ditolak dan dihapus dari sistem.');
    }

    // =============================================================
    // METHOD LEGACY — Tidak dipakai lagi, hanya placeholder
    // agar route lama tidak error kalau diakses.
    // =============================================================

    public function create()       { return redirect()->to('/guru/kelas'); }
    public function store()        { return redirect()->to('/guru/kelas'); }
    public function joinForm()     { return redirect()->to('/login'); }
    public function joinProcess()  { return redirect()->to('/login'); }
    public function leave()        { return redirect()->to('/login'); }
}