<?php

namespace App\Controllers;

use App\Models\MateriAdaptifModel;
use App\Models\VarkResultModel;
use App\Models\ZpdResultModel;

class MateriController extends BaseController
{
    // ======== HALAMAN MATERI ADAPTIF (Halaman 9) ========
    public function index($moduleId = 1)
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'siswa') {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        // 1. Cek VARK
        $varkModel = new VarkResultModel();
        $vark = $varkModel->where('pengguna_id', $userId)->first();
        if (!$vark) {
            return redirect()->to('/vark')->with('error', 'Silakan kerjakan tes VARK terlebih dahulu!');
        }

        // =============================================================
        // 2. Cek ZPD Modul 1 (sebagai acuan level untuk semua modul)
        // =============================================================
        $zpdModel = new ZpdResultModel();
        $zpdModul1 = $zpdModel->where(['pengguna_id' => $userId, 'modul_id' => 1])->first();
        if (!$zpdModul1) {
            return redirect()->to('/zpd/test/1')->with('error', 'Silakan kerjakan tes ZPD Modul 1 terlebih dahulu!');
        }

        // =============================================================
        // 3. Cek apakah modul ini sudah bisa diakses
        //    Modul 2: hanya jika Modul 1 sudah selesai
        //    Modul 3: hanya jika Modul 2 sudah selesai
        // =============================================================
        if ($moduleId > 1) {
            $prevModuleId = $moduleId - 1;
            $prevZpd = $zpdModel->where(['pengguna_id' => $userId, 'modul_id' => $prevModuleId])->first();
            if (!$prevZpd) {
                return redirect()->to('/siswa/modul')->with('error', 'Selesaikan modul sebelumnya terlebih dahulu!');
            }
        }

        // =============================================================
        // 4. Tentukan konten adaptif berdasarkan VARK + ZPD (dari Modul 1)
        // =============================================================
        $varkType = $this->request->getGet('vark') ?? $vark['tipe_hasil'];
        $zpdLevel = $zpdModul1['level_zpd']; // Level ZPD dari Modul 1

        // Cek apakah mode "show all" aktif (dari parameter URL)
        $showAll = $this->request->getGet('show') === 'all';

        // 5. Ambil konten dari database
        $materiModel = new MateriAdaptifModel();
        $konten = $materiModel->where([
            'modul_id' => $moduleId,
            'tipe_vark' => $varkType,
            'level_zpd' => $zpdLevel
        ])->first();

        // Jika tidak ada konten yang cocok, fallback ke Multimodal atau default
        if (!$konten) {
            // Coba cari dengan tipe Multimodal (M)
            $konten = $materiModel->where([
                'modul_id' => $moduleId,
                'tipe_vark' => 'M',
                'level_zpd' => $zpdLevel
            ])->first();
        }

        if (!$konten) {
            // Fallback: ambil konten pertama untuk modul ini
            $konten = $materiModel->where('modul_id', $moduleId)->first();
        }

        if (!$konten) {
            return redirect()->to('/siswa/modul')->with('error', 'Konten materi belum tersedia untuk modul ini.');
        }

        // 6. Siapkan data scaffolding berdasarkan level ZPD
        $scaffolding = [
            'novice' => [
                'label' => 'High Scaffolding (Bantuan Penuh)',
                'description' => 'Materi disajikan secara bertahap dengan bantuan lengkap.',
                'level' => 'high'
            ],
            'apprentice' => [
                'label' => 'Fading Scaffolding (Bantuan Parsial)',
                'description' => 'Bantuan mulai dikurangi untuk mendorong kemandirian.',
                'level' => 'fading'
            ],
            'master' => [
                'label' => 'Zero Scaffolding (Mandiri)',
                'description' => 'Tantangan tanpa bantuan untuk menguji kemandirian.',
                'level' => 'zero'
            ]
        ];

        $data = [
            'title' => 'Materi Adaptif - Modul ' . $moduleId,
            'konten' => $konten,
            'vark_type' => $varkType,
            'zpd_level' => $zpdLevel,
            'module_id' => $moduleId,
            'scaffolding' => $scaffolding[$zpdLevel] ?? $scaffolding['novice'],
            'vark_label' => $this->getVarkLabel($varkType),
            'show_all' => $showAll,
        ];

        return view('siswa/materi_adaptif', $data);
    }

    private function getVarkLabel($type)
    {
        $labels = [
            'V' => 'Visual',
            'A' => 'Aural',
            'R' => 'Read/Write',
            'K' => 'Kinestetik',
            'M' => 'Multimodal'
        ];
        return $labels[$type] ?? $type;
    }
}