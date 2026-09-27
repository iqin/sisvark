<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\KelasAnggotaModel;

class KelasController extends BaseController
{
    // =============================================================
    // GURU: Daftar Kelas
    // =============================================================
    public function index()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $kelasModel = new KelasModel();
        $data = [
            'title' => 'Kelas Saya',
            'kelas' => $kelasModel->getWithStats(session()->get('user_id')),
        ];

        return view('guru/kelas_index', $data);
    }

    // =============================================================
    // GURU: Form Buat Kelas
    // =============================================================
    public function create()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        return view('guru/kelas_form', [
            'title' => 'Buat Kelas Baru',
        ]);
    }

    // =============================================================
    // GURU: Simpan Kelas Baru
    // =============================================================
    public function store()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $rules = [
            'nama'           => 'required|min_length[3]|max_length[100]',
            'mata_pelajaran' => 'required|max_length[50]',
            'tahun_ajaran'   => 'required|max_length[20]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $kelasModel = new KelasModel();

        // Tentukan prefix kode berdasarkan mata pelajaran (max 3 huruf)
        $mapel  = $this->request->getPost('mata_pelajaran');
        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $mapel), 0, 3));
        if (empty($prefix)) {
            $prefix = 'KLS';
        }

        $kode = $kelasModel->generateKode($prefix);

        $kelasModel->save([
            'guru_id'        => session()->get('user_id'),
            'nama'           => $this->request->getPost('nama'),
            'mata_pelajaran' => $mapel,
            'deskripsi'      => $this->request->getPost('deskripsi'),
            'tahun_ajaran'   => $this->request->getPost('tahun_ajaran'),
            'kode_kelas'     => $kode,
            'is_active'      => 1,
        ]);

        return redirect()->to('/guru/kelas')->with('success',
            'Kelas berhasil dibuat! Kode kelas: <strong>' . esc($kode) . '</strong>');
    }

    // =============================================================
    // GURU: Detail Kelas (daftar siswa)
    // =============================================================
    public function show($kelasId = null)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $kelasModel = new KelasModel();
        $kelas = $kelasModel->find($kelasId);

        if (!$kelas || (int) $kelas['guru_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/guru/kelas')->with('error', 'Kelas tidak ditemukan.');
        }

        $anggotaModel = new KelasAnggotaModel();
        $data = [
            'title' => 'Detail Kelas: ' . $kelas['nama'],
            'kelas' => $kelas,
            'siswa' => $anggotaModel->getSiswaByKelas($kelasId),
        ];

        return view('guru/kelas_detail', $data);
    }

    // =============================================================
    // GURU: Toggle Aktif / Arsip Kelas
    // =============================================================
    public function toggleActive($kelasId = null)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $kelasModel = new KelasModel();
        $kelas = $kelasModel->find($kelasId);

        if (!$kelas || (int) $kelas['guru_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/guru/kelas')->with('error', 'Kelas tidak ditemukan.');
        }

        $newStatus = $kelas['is_active'] ? 0 : 1;
        $kelasModel->update($kelasId, ['is_active' => $newStatus]);

        $msg = $newStatus ? 'Kelas berhasil diaktifkan kembali.' : 'Kelas berhasil diarsipkan.';
        return redirect()->to('/guru/kelas')->with('success', $msg);
    }

    // =============================================================
    // GURU: Regenerate Kode Kelas (jika kode bocor)
    // =============================================================
    public function regenerateCode($kelasId = null)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $kelasModel = new KelasModel();
        $kelas = $kelasModel->find($kelasId);

        if (!$kelas || (int) $kelas['guru_id'] !== (int) session()->get('user_id')) {
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
    // GURU: Keluarkan Siswa dari Kelas
    // =============================================================
    public function kick($kelasId = null, $siswaId = null)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'guru') {
            return redirect()->to('/login');
        }

        $kelasModel = new KelasModel();
        $kelas = $kelasModel->find($kelasId);

        if (!$kelas || (int) $kelas['guru_id'] !== (int) session()->get('user_id')) {
            return redirect()->to('/guru/kelas')->with('error', 'Kelas tidak ditemukan.');
        }

        $anggotaModel = new KelasAnggotaModel();
        $anggota = $anggotaModel->findAnggota($kelasId, $siswaId);
        if ($anggota) {
            $anggotaModel->update($anggota['id'], ['status' => 'keluar']);
        }

        return redirect()->to('/guru/kelas/detail/' . $kelasId)
                         ->with('success', 'Siswa berhasil dikeluarkan dari kelas.');
    }

    // =============================================================
    // SISWA: Form Gabung Kelas + Daftar Kelas yang Diikuti
    // =============================================================
    public function joinForm()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $anggotaModel = new KelasAnggotaModel();
        $data = [
            'title'      => 'Gabung Kelas',
            'kelas_saya' => $anggotaModel->getKelasBySiswa(session()->get('user_id')),
        ];

        return view('siswa/gabung_kelas', $data);
    }

    // =============================================================
    // SISWA: Proses Gabung Kelas via Kode
    // =============================================================
    public function joinProcess()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $kode = strtoupper(trim($this->request->getPost('kode_kelas') ?? ''));
        if (empty($kode)) {
            return redirect()->back()->with('error', 'Kode kelas wajib diisi.');
        }

        $kelasModel = new KelasModel();
        $kelas = $kelasModel->findByKode($kode);
        if (!$kelas) {
            return redirect()->back()->with('error', 'Kode kelas tidak valid atau kelas sudah tidak aktif.');
        }

        $siswaId      = (int) session()->get('user_id');
        $anggotaModel = new KelasAnggotaModel();
        $existing     = $anggotaModel->findAnggota($kelas['id'], $siswaId);

        if ($existing) {
            if ($existing['status'] === 'keluar') {
                // Reaktivasi
                $anggotaModel->update($existing['id'], [
                    'status'    => 'aktif',
                    'joined_at' => date('Y-m-d H:i:s'),
                ]);
                return redirect()->to('/siswa/gabung-kelas')
                                 ->with('success', 'Berhasil bergabung kembali ke kelas <strong>' . esc($kelas['nama']) . '</strong>!');
            }

            return redirect()->to('/siswa/gabung-kelas')
                             ->with('info', 'Anda sudah tergabung di kelas ini.');
        }

        $anggotaModel->save([
            'kelas_id'  => $kelas['id'],
            'siswa_id'  => $siswaId,
            'status'    => 'aktif',
            'joined_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/siswa/gabung-kelas')
                         ->with('success', 'Berhasil bergabung ke kelas <strong>' . esc($kelas['nama']) . '</strong>!');
    }

    // =============================================================
    // SISWA: Keluar dari Kelas
    // =============================================================
    public function leave($kelasId = null)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $anggotaModel = new KelasAnggotaModel();
        $anggota = $anggotaModel->findAnggota($kelasId, session()->get('user_id'));

        if ($anggota && $anggota['status'] === 'aktif') {
            $anggotaModel->update($anggota['id'], ['status' => 'keluar']);
            return redirect()->to('/siswa/gabung-kelas')->with('success', 'Anda telah keluar dari kelas.');
        }

        return redirect()->to('/siswa/gabung-kelas')->with('info', 'Anda tidak tergabung di kelas ini.');
    }
}