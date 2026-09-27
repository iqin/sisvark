<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table         = 'kelas';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'guru_id',
        'nama',
        'mata_pelajaran',
        'deskripsi',
        'kode_kelas',
        'tahun_ajaran',
        'is_active',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Generate kode kelas unik, format: {PREFIX}-{4CHAR}
     * Contoh: BIO-X9M3, BIO-K2P7
     */
    public function generateKode(string $prefix = 'KLS'): string
    {
        $prefix = strtoupper(substr($prefix, 0, 3));

        do {
            $random = strtoupper(substr(bin2hex(random_bytes(4)), 0, 4));
            $kode   = $prefix . '-' . $random;
        } while ($this->where('kode_kelas', $kode)->first() !== null);

        return $kode;
    }

    /**
     * Cari kelas berdasarkan kode (case-insensitive, hanya yang aktif)
     */
    public function findByKode(string $kode): ?array
    {
        return $this->where('kode_kelas', strtoupper(trim($kode)))
                    ->where('is_active', 1)
                    ->first();
    }

    /**
     * Ambil semua kelas milik guru tertentu
     */
    public function getByGuru(int $guruId): array
    {
        return $this->where('guru_id', $guruId)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Ambil kelas + statistik (jumlah siswa, jumlah yang sudah VARK, dsb.)
     * Digunakan di dashboard guru
     */
    public function getWithStats(int $guruId): array
    {
        $db = \Config\Database::connect();

        return $db->query("
            SELECT 
                k.*,
                (SELECT COUNT(*) 
                 FROM kelas_anggota ka 
                 WHERE ka.kelas_id = k.id AND ka.status = 'aktif'
                ) AS total_siswa,
                (SELECT COUNT(DISTINCT v.pengguna_id) 
                 FROM vark_hasil v
                 INNER JOIN kelas_anggota ka ON ka.siswa_id = v.pengguna_id
                 WHERE ka.kelas_id = k.id AND ka.status = 'aktif'
                ) AS total_vark,
                (SELECT COUNT(DISTINCT z.pengguna_id) 
                 FROM zpd_hasil z
                 INNER JOIN kelas_anggota ka ON ka.siswa_id = z.pengguna_id
                 WHERE ka.kelas_id = k.id AND ka.status = 'aktif'
                ) AS total_zpd
            FROM kelas k
            WHERE k.guru_id = ?
            ORDER BY k.created_at DESC
        ", [$guruId])->getResultArray();
    }

    /**
     * Ambil detail kelas + nama guru
     */
    public function getWithGuru(int $kelasId): ?array
    {
        return $this->select('kelas.*, pengguna.nama AS guru_nama, pengguna.email AS guru_email')
                    ->join('pengguna', 'pengguna.id = kelas.guru_id')
                    ->where('kelas.id', $kelasId)
                    ->first();
    }

    /**
     * Cek apakah guru adalah pemilik kelas ini
     */
    public function isOwner(int $kelasId, int $guruId): bool
    {
        $kelas = $this->find($kelasId);
        return $kelas !== null && (int) $kelas['guru_id'] === $guruId;
    }
}