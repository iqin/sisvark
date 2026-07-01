<?php

namespace App\Models;

use CodeIgniter\Model;

class ZpdResultModel extends Model
{
    protected $table = 'zpd_hasil';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pengguna_id', 'modul_id', 'total_nilai', 'level_zpd', 'created_at'];
    protected $useTimestamps = false;
}