<?php

namespace App\Controllers;

use App\Models\VarkResultModel;
use App\Models\ZpdResultModel;

class ModulController extends BaseController
{
    public function index()
    {
        // Pastikan login dan role siswa
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        // Cek apakah sudah tes VARK
        $varkModel = new VarkResultModel();
        $varkDone = $varkModel->where('pengguna_id', $userId)->first() ? true : false;

        // Cek apakah sudah tes ZPD untuk modul 1 (sebagai syarat awal)
        $zpdModel = new ZpdResultModel();
        $zpdMod1Done = $zpdModel->where(['pengguna_id' => $userId, 'modul_id' => 1])->first() ? true : false;

        $data = [
            'title' => 'Pilih Modul',
            'vark_done' => $varkDone,
            'zpd_mod1_done' => $zpdMod1Done,
        ];

        return view('siswa/pilih_modul', $data);
    }
}