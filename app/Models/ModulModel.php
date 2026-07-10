<?php

namespace App\Models;

use CodeIgniter\Model;

class ModulModel extends Model
{
    protected $table = 'modul';
    protected $primaryKey = 'id';
    protected $allowedFields = ['judul', 'deskripsi', 'urutan'];
    protected $useTimestamps = false; // Matikan timestamps otomatis
}