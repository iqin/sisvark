<?php

namespace App\Models;

use CodeIgniter\Model;

class ZpdTestSessionModel extends Model
{
    protected $table = 'zpd_test_session';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'module_id', 'start_time'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}