<?php

namespace App\Models;

use CodeIgniter\Model;

class PesanModel extends Model
{
    protected $table = 'pesan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['siswa_id', 'guru_id', 'pesan', 'is_read', 'created_at', 'is_from_guru', 'parent_id'];
    
    // MATIKAN TIMESTAMPS OTOMATIS
    protected $useTimestamps = false;
    
    // Jika ingin timestamps manual, set false dan kita isi created_at manual di controller
    // protected $useTimestamps = false;

    public function getPesanByGuru($guruId)
    {
        return $this->select('pesan.*, pengguna.nama as siswa_nama')
                    ->join('pengguna', 'pengguna.id = pesan.siswa_id')
                    ->where('guru_id', $guruId)
                    ->orderBy('pesan.created_at', 'DESC')
                    ->findAll();
    }

    public function getPesanBySiswa($siswaId)
    {
        return $this->select('pesan.*, pengguna.nama as guru_nama')
                    ->join('pengguna', 'pengguna.id = pesan.guru_id')
                    ->where('siswa_id', $siswaId)
                    ->orderBy('pesan.created_at', 'DESC')
                    ->findAll();
    }

    public function markAsRead($id)
    {
        return $this->update($id, ['is_read' => 1]);
    }

    public function countUnread($guruId)
    {
        return $this->where(['guru_id' => $guruId, 'is_read' => 0])->countAllResults();
    }
}