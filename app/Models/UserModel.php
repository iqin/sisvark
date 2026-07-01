<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'email', 'kata_sandi', 'peran', 'sekolah', 'kelas'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];

    protected function hashPassword(array $data)
    {
        if (isset($data['data']['kata_sandi'])) {
            $data['data']['kata_sandi'] = password_hash($data['data']['kata_sandi'], PASSWORD_DEFAULT);
        }
        return $data;
    }
}