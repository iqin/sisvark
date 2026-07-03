<?php

namespace App\Models;

use CodeIgniter\Model;

class VarkTestSessionModel extends Model
{
    protected $table = 'vark_test_session';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'start_time', 'question_order'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}