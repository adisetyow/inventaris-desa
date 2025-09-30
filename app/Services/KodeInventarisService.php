<?php

namespace App\Services;

use App\Models\Inventaris;
use App\Models\KategoriInventaris;

class KodeInventarisService
{
    /**
     * Generate kode inventaris unik berdasarkan kode desa + kategori.
     *
     * @param int $kategori_id
     * @return string
     */
    public function generateKode(int $kategori_id): string
    {
        // 1. Ambil kode desa dari config
        $kodeDesa = config('app.kode_desa');

        // 2. Ambil ID kategori (format 2 digit)
        $kategori = KategoriInventaris::find($kategori_id);
        if (!$kategori) {
            throw new \Exception("Kategori dengan ID {$kategori_id} tidak ditemukan.");
        }

        $kodeKategori = str_pad($kategori->id, 2, '0', STR_PAD_LEFT);

        // 3. Buat prefix
        $prefix = $kodeDesa . '.' . $kodeKategori . '.';

        // 4. Cari kode inventaris terakhir dengan prefix yang sama
        $lastItem = Inventaris::where('kode_inventaris', 'like', $prefix . '%')
            ->orderBy('kode_inventaris', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastItem) {
            // Ambil nomor urut terakhir dari kode
            $lastNumberStr = substr($lastItem->kode_inventaris, strlen($prefix));
            $lastNumber = (int) $lastNumberStr;
            $nextNumber = $lastNumber + 1;
        }

        // 5. Format nomor urut 3 digit
        $nomorUrutFormatted = str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // 6. Gabungkan jadi kode akhir
        return $prefix . $nomorUrutFormatted;
    }
}
