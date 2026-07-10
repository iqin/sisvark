<?php

namespace App\Controllers;

use App\Models\VarkResultModel;
use App\Models\ZpdResultModel;

class ModulController extends BaseController
{
    public function index()
    {
        // Cek login
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        // =============================================================
        // AMBIL HASIL VARK SISWA (PASTIKAN DATA TERAMBIL)
        // =============================================================
        $varkModel = new VarkResultModel();
        $varkResult = $varkModel->where('pengguna_id', $userId)
                                ->orderBy('id', 'DESC')
                                ->first();

        // =============================================================
        // AMBIL STATUS ZPD UNTUK 3 MODUL
        // =============================================================
        $zpdModel = new ZpdResultModel();
        $zpdStatus = [];
        for ($i = 1; $i <= 3; $i++) {
            $zpd = $zpdModel->where(['pengguna_id' => $userId, 'modul_id' => $i])->first();
            $zpdStatus[$i] = [
                'done'  => ($zpd !== null),
                'level' => $zpd ? $zpd['level_zpd'] : null,
                'score' => $zpd ? $zpd['total_nilai'] : null,
            ];
        }

        // =============================================================
        // KIRIM DATA KE VIEW
        // =============================================================
        $data = [
            'title'       => 'Pilih Modul',
            'vark_done'   => ($varkResult !== null),
            'vark_result' => $varkResult, // <-- PASTIKAN INI TERKIRIM
            'zpd_status'  => $zpdStatus,
        ];

        // =============================================================
        // DEBUG: CEK APAKAH DATA ADA (HAPUS NANTI)
        // =============================================================
        // log_message('info', 'VARK Result: ' . print_r($varkResult, true));

        return view('siswa/pilih_modul', $data);
    }
}