<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriAdaptifModel extends Model
{
    protected $table = 'materi_adaptif';
    protected $primaryKey = 'id';
    protected $allowedFields = ['modul_id', 'kode_konten', 'tipe_vark', 'level_zpd', 'judul', 'isi_konten', 'url_media'];
    protected $useTimestamps = false; // Matikan timestamps karena tidak ada updated_at

    public function getWithModul()
    {
        return $this->select('materi_adaptif.*, modul.judul as modul_judul')
                    ->join('modul', 'modul.id = materi_adaptif.modul_id')
                    ->orderBy('materi_adaptif.modul_id', 'ASC')
                    ->orderBy('materi_adaptif.kode_konten', 'ASC')
                    ->findAll();
    }
}