<?php

namespace App\Models;

use CodeIgniter\Model;

class ZpdSoalModel extends Model
{
    protected $table = 'zpd_soal';
    protected $primaryKey = 'id';
    protected $allowedFields = ['modul_id', 'level_zpd', 'bobot_nilai', 'teks_soal', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'jawaban_benar', 'created_at'];
    protected $useTimestamps = false; // Matikan timestamps otomatis
}