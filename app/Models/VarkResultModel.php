<?php

namespace App\Models;

use CodeIgniter\Model;

class VarkResultModel extends Model
{
    protected $table = 'vark_hasil';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'pengguna_id', 'skor_v', 'skor_a', 'skor_r', 'skor_k',
        'selisih', 'kategori_hasil', 'tipe_hasil', 'created_at'
    ];
    protected $useTimestamps = false; // Matikan timestamps otomatis
}