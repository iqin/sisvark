<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasAnggotaModel extends Model
{
    protected $table         = 'kelas_anggota';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'kelas_id',
        'siswa_id',
        'status',
        'joined_at',
    ];
    protected $useTimestamps = false;

    /**
     * Ambil semua kelas yang diikuti siswa (status aktif) + nama guru
     */
    public function getKelasBySiswa(int $siswaId): array
    {
        return $this->select('
                kelas.*,
                pengguna.nama AS guru_nama,
                pengguna.email AS guru_email,
                kelas_anggota.joined_at
            ')
            ->join('kelas', 'kelas.id = kelas_anggota.kelas_id')
            ->join('pengguna', 'pengguna.id = kelas.guru_id')
            ->where('kelas_anggota.siswa_id', $siswaId)
            ->where('kelas_anggota.status', 'aktif')
            ->where('kelas.is_active', 1)
            ->orderBy('kelas_anggota.joined_at', 'DESC')
            ->findAll();
    }

    /**
     * Ambil semua siswa di suatu kelas (status aktif) + nama + email + status aktivasi
     */
    public function getSiswaByKelas(int $kelasId): array
    {
        return $this->select('
                pengguna.id,
                pengguna.nama,
                pengguna.email,
                pengguna.is_active,
                pengguna.kelas AS kelas_sekolah,
                kelas_anggota.joined_at
            ')
            ->join('pengguna', 'pengguna.id = kelas_anggota.siswa_id')
            ->where('kelas_anggota.kelas_id', $kelasId)
            ->where('kelas_anggota.status', 'aktif')
            ->orderBy('pengguna.is_active', 'ASC') // nonaktif dulu biar kelihatan
            ->orderBy('pengguna.nama', 'ASC')
            ->findAll();
    }

    /**
     * Hitung jumlah siswa aktif di kelas
     */
    public function countByKelas(int $kelasId): int
    {
        return $this->where(['kelas_id' => $kelasId, 'status' => 'aktif'])
                    ->countAllResults();
    }

    /**
     * Cek apakah siswa tergabung di kelas (status apapun)
     */
    public function findAnggota(int $kelasId, int $siswaId): ?array
    {
        return $this->where(['kelas_id' => $kelasId, 'siswa_id' => $siswaId])->first();
    }

    /**
     * Cek apakah siswa tergabung aktif di kelas
     */
    public function isAnggotaAktif(int $kelasId, int $siswaId): bool
    {
        $row = $this->where([
            'kelas_id' => $kelasId,
            'siswa_id' => $siswaId,
            'status'   => 'aktif',
        ])->first();

        return $row !== null;
    }

    /**
     * Ambil kelas pertama siswa (dipakai untuk default chat / notifikasi)
     */
    public function getKelasUtamaSiswa(int $siswaId): ?array
    {
        return $this->select('
                kelas.*,
                pengguna.nama AS guru_nama
            ')
            ->join('kelas', 'kelas.id = kelas_anggota.kelas_id')
            ->join('pengguna', 'pengguna.id = kelas.guru_id')
            ->where('kelas_anggota.siswa_id', $siswaId)
            ->where('kelas_anggota.status', 'aktif')
            ->where('kelas.is_active', 1)
            ->orderBy('kelas_anggota.joined_at', 'ASC')
            ->first();
    }

    /**
     * Ambil daftar kelas_id yang diikuti siswa (untuk filter query)
     */
    public function getKelasIdsBySiswa(int $siswaId): array
    {
        $rows = $this->select('kelas_id')
                     ->where('siswa_id', $siswaId)
                     ->where('status', 'aktif')
                     ->findAll();

        return array_map('intval', array_column($rows, 'kelas_id'));
    }

    /**
     * Ambil daftar siswa_id di kelas tertentu (untuk filter query)
     */
    public function getSiswaIdsByKelas(int $kelasId): array
    {
        $rows = $this->select('siswa_id')
                     ->where('kelas_id', $kelasId)
                     ->where('status', 'aktif')
                     ->findAll();

        return array_map('intval', array_column($rows, 'siswa_id'));
    }
}