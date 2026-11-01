<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriAdaptifGambarModel extends Model
{
    protected $table            = 'materi_adaptif_gambar';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'materi_id',
        'gambar_url',
        'judul',
        'penjelasan',
        'urutan',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ---------------------------------------------------------------
    // QUERY HELPERS
    // ---------------------------------------------------------------

    /**
     * Ambil semua gambar milik satu materi, urut berdasarkan `urutan`.
     *
     * @param  int $materiId
     * @return array
     */
    public function getByMateri(int $materiId): array
    {
        return $this->where('materi_id', $materiId)
                    ->orderBy('urutan', 'ASC')
                    ->orderBy('id', 'ASC')
                    ->findAll();
    }

    /**
     * Hitung jumlah gambar milik satu materi.
     * Dipakai untuk cek fallback: kalau 0, tampilkan gambar_url lama.
     *
     * @param  int $materiId
     * @return int
     */
    public function countByMateri(int $materiId): int
    {
        return $this->where('materi_id', $materiId)->countAllResults();
    }

    /**
     * Ambil semua path gambar milik satu materi (untuk hapus file fisik).
     *
     * @param  int $materiId
     * @return string[]
     */
    public function getPathsByMateri(int $materiId): array
    {
        $rows = $this->select('gambar_url')
                     ->where('materi_id', $materiId)
                     ->findAll();

        return array_column($rows, 'gambar_url');
    }

    // ---------------------------------------------------------------
    // BULK OPERATIONS (dipakai saat simpan/update materi)
    // ---------------------------------------------------------------

    /**
     * Ganti SELURUH gambar milik satu materi.
     * Hapus baris lama, insert baris baru dalam satu transaksi.
     *
     * @param  int   $materiId
     * @param  array $rows  Format: [['gambar_url'=>..., 'judul'=>..., 'penjelasan'=>..., 'urutan'=>...], ...]
     * @return bool
     */
    public function replaceAll(int $materiId, array $rows): bool
    {
        $db = $this->db;
        $db->transStart();

        // 1. Hapus semua baris lama
        $this->where('materi_id', $materiId)->delete();

        // 2. Insert baris baru (kalau ada)
        if (! empty($rows)) {
            $payload = [];
            foreach (array_values($rows) as $i => $row) {
                $payload[] = [
                    'materi_id'  => $materiId,
                    'gambar_url' => $row['gambar_url'],
                    'judul'      => $row['judul']       ?? null,
                    'penjelasan' => $row['penjelasan']  ?? null,
                    'urutan'     => $row['urutan']      ?? $i,
                ];
            }
            $this->insertBatch($payload);
        }

        $db->transComplete();

        return $db->transStatus() !== false;
    }

    /**
     * Hapus semua baris gambar milik satu materi.
     * (Biasanya dipanggil saat materi dihapus — walaupun FK CASCADE 
     *  sudah otomatis, method ini berguna untuk hapus file fisik juga.)
     *
     * @param  int $materiId
     * @return bool
     */
    public function deleteByMateri(int $materiId): bool
    {
        return $this->where('materi_id', $materiId)->delete();
    }
}