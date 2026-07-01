<?php

namespace App\Models;

use CodeIgniter\Model;

class VarkSoalModel extends Model
{
    protected $table = 'vark_soal';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nomor', 'teks_soal', 'opsi_v', 'opsi_a', 'opsi_r', 'opsi_k'];
    
    // Nonaktifkan timestamps karena tabel tidak punya updated_at
    protected $useTimestamps = false;
}