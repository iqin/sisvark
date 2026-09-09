<?php

namespace App\Models;

use CodeIgniter\Model;

class PostTestModel extends Model
{
    protected $table = 'post_test_hasil';
    protected $primaryKey = 'id';
    protected $allowedFields = ['pengguna_id', 'modul_id', 'skor', 'level_zpd_awal', 'level_zpd_akhir', 'status', 'created_at'];
    protected $useTimestamps = false;

    public function getHasilByUserAndModul($userId, $moduleId)
    {
        return $this->where(['pengguna_id' => $userId, 'modul_id' => $moduleId])->first();
    }

    public function getLatestByUser($userId)
    {
        return $this->where('pengguna_id', $userId)
                    ->orderBy('id', 'DESC')
                    ->first();
    }
}