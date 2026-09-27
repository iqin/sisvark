<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'pengguna';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'email', 'kata_sandi', 'peran', 'is_admin', 'is_active', 'sekolah', 'kelas'];
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

        /**
     * Ambil semua guru di sekolah yang sama (jika fitur sekolah dipakai nanti).
     * Untuk sekarang, method ini mengembalikan guru pertama (fallback legacy).
     * Akan di-refactor ketika fitur kelas sudah diterapkan penuh.
     */
    public function getGuruPertama(): ?array
    {
        return $this->where('peran', 'guru')->first();
    }

    /**
     * Ambil data pengguna + nama sekolah (jika nanti ada tabel sekolah).
     * Placeholder untuk pengembangan selanjutnya.
     */
    public function getWithRelasi(int $userId): ?array
    {
        return $this->find($userId);
    }

    /**
     * Cek apakah user adalah guru
     */
    public function isGuru(int $userId): bool
    {
        $user = $this->find($userId);
        return $user !== null && $user['peran'] === 'guru';
    }

    /**
     * Cek apakah user adalah siswa
     */
    public function isSiswa(int $userId): bool
    {
        $user = $this->find($userId);
        return $user !== null && $user['peran'] === 'siswa';
    }
}