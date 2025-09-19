<?php

namespace App\Services;

use App\Models\Inventaris;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AsetHandlerService
{

    public function storeDetailAset(array $validatedData, Inventaris $inventaris)
    {
        // Ambil nama kategori langsung dari relasi, karena sudah pasti ada dan valid.
        $namaKategori = $inventaris->kategori->nama_kategori;

        // 1. Ambil HANYA NAMA FIELD yang relevan untuk kategori ini
        $detailFieldKeys = array_keys($this->getFieldsForCategory($namaKategori));

        // 2. Filter array $validatedData agar hanya berisi field-field di atas
        $detailData = array_filter(
            $validatedData,
            fn($key) => in_array($key, $detailFieldKeys),
            ARRAY_FILTER_USE_KEY
        );

        // 3. Tambahkan inventaris_id ke data yang sudah bersih
        $dataToCreate = array_merge($detailData, ['inventaris_id' => $inventaris->id]);

        switch ($namaKategori) {
            case 'Peralatan Kantor':
                $inventaris->asetPeralatanKantor()->create($dataToCreate);
                break;
            case 'Peralatan Komunikasi':
                $inventaris->asetPeralatanKomunikasi()->create($dataToCreate);
                break;
            case 'Bangunan':
                $inventaris->asetBangunan()->create($dataToCreate);
                break;
            case 'Kendaraan':
                $inventaris->asetKendaraan()->create($dataToCreate);
                break;
            case 'Kesehatan Posyandu':
                $inventaris->asetKesehatanPosyandu()->create($dataToCreate);
                break;
            case 'Tanah':
                $inventaris->asetTanah()->create($dataToCreate);
                break;
            case 'Infrastruktur':
                $inventaris->asetInfrastruktur()->create($dataToCreate);
                break;
            case 'Pertanian':
                $inventaris->asetPertanian()->create($dataToCreate);
                break;
            default:
                $inventaris->asetLainnya()->create($dataToCreate);
                break;
        }
    }

    public function updateDetailAset(array $validatedData, Inventaris $inventaris)
    {
        $namaKategori = $inventaris->kategori->nama_kategori;
        $tableName = $this->getTableNameForCategory($namaKategori);

        if (!$tableName) {
            Log::warning('Tidak ada tabel detail untuk kategori: ' . $namaKategori);
            return;
        }

        $detailFieldKeys = array_keys($this->getFieldsForCategory($namaKategori));
        $detailData = array_filter(
            $validatedData,
            fn($key) => in_array($key, $detailFieldKeys),
            ARRAY_FILTER_USE_KEY
        );

        if (empty($detailData)) {
            Log::warning('Tidak ada data detail untuk diupdate pada kategori: ' . $namaKategori);
            return;
        }

        Log::info('Attempting to update/insert detail aset via Query Builder.', [
            'table' => $tableName,
            'data' => $detailData
        ]);

        // Gunakan Query Builder's updateOrInsert
        DB::table($tableName)->updateOrInsert(
            ['inventaris_id' => $inventaris->id],
            $detailData
        );
    }

    private function getTableNameForCategory($kategori)
    {
        switch ($kategori) {
            case 'Peralatan Kantor':
                return 'aset_peralatan_kantor';
            case 'Peralatan Komunikasi':
                return 'aset_peralatan_komunikasi';
            case 'Bangunan':
                return 'aset_bangunan';
            case 'Kendaraan':
                return 'aset_kendaraan';
            case 'Kesehatan Posyandu':
                return 'aset_kesehatan_posyandu';
            case 'Tanah':
                return 'aset_tanah';
            case 'Infrastruktur':
                return 'aset_infrastruktur';
            case 'Pertanian':
                return 'aset_pertanian';
            default:
                return 'aset_lainnya';
        }
    }

    public function getFieldsForCategory($kategori)
    {
        switch ($kategori) {
            case 'Peralatan Kantor':
                return [
                    'merk' => ['label' => 'Merk', 'type' => 'text'],
                    'bahan' => ['label' => 'Bahan', 'type' => 'text'],
                    'tahun_perolehan' => ['label' => 'Tahun Perolehan', 'type' => 'number'],
                    'warna' => ['label' => 'Warna', 'type' => 'text'],
                    'nomor_inventaris_internal' => ['label' => 'Nomor Inventaris Internal', 'type' => 'text'],
                ];

            case 'Peralatan Komunikasi':
                return [
                    'merk' => ['label' => 'Merk', 'type' => 'text'],
                    'frekuensi' => ['label' => 'Frekuensi', 'type' => 'text'],
                    'serial_number' => ['label' => 'Serial Number', 'type' => 'text'],
                    'jenis_peralatan' => ['label' => 'Jenis Peralatan', 'type' => 'text'],
                    'tahun_perolehan' => ['label' => 'Tahun Perolehan', 'type' => 'number'],
                ];

            case 'Bangunan':
                return [
                    'nama_bangunan' => ['label' => 'Nama Bangunan', 'type' => 'text'],
                    'alamat' => ['label' => 'Alamat', 'type' => 'text'],
                    'luas' => ['label' => 'Luas (m²)', 'type' => 'number', 'step' => '0.01'],
                    'tahun_bangun' => ['label' => 'Tahun Bangun', 'type' => 'number'],
                    'status_sertifikat' => ['label' => 'Status Sertifikat', 'type' => 'text'],
                    'nomor_sertifikat' => ['label' => 'Nomor Sertifikat', 'type' => 'text'],
                    'kondisi_fisik' => ['label' => 'Kondisi Fisik', 'type' => 'text'],
                ];

            case 'Kendaraan':
                return [
                    'jenis_kendaraan' => ['label' => 'Jenis Kendaraan', 'type' => 'text'],
                    'merk_tipe' => ['label' => 'Merk/Tipe', 'type' => 'text'],
                    'nomor_polisi' => ['label' => 'Nomor Polisi', 'type' => 'text'],
                    'nomor_rangka' => ['label' => 'Nomor Rangka', 'type' => 'text'],
                    'nomor_mesin' => ['label' => 'Nomor Mesin', 'type' => 'text'],
                    'tahun_perolehan' => ['label' => 'Tahun Perolehan', 'type' => 'number'],
                    'warna' => ['label' => 'Warna', 'type' => 'text'],
                ];

            case 'Kesehatan Posyandu':
                return [
                    'nama_alat' => ['label' => 'Nama Alat', 'type' => 'text'],
                    'merk' => ['label' => 'Merk', 'type' => 'text'],
                    'tahun_perolehan' => ['label' => 'Tahun Perolehan', 'type' => 'number'],
                    'jumlah' => ['label' => 'Jumlah', 'type' => 'number'],
                    'lokasi_penempatan' => ['label' => 'Lokasi Penempatan', 'type' => 'text'],
                ];

            case 'Tanah':
                return [
                    'luas' => ['label' => 'Luas (m²)', 'type' => 'number', 'step' => '0.01'],
                    'alamat' => ['label' => 'Alamat', 'type' => 'text'],
                    'nomor_sertifikat' => ['label' => 'Nomor Sertifikat', 'type' => 'text'],
                    'status_sertifikat' => ['label' => 'Status Sertifikat', 'type' => 'text'],
                    'tahun_diperoleh' => ['label' => 'Tahun Diperoleh', 'type' => 'number'],
                    'penggunaan_saat_ini' => ['label' => 'Penggunaan Saat Ini', 'type' => 'text'],
                ];

            case 'Infrastruktur':
                return [
                    'jenis_infrastruktur' => ['label' => 'Jenis Infrastruktur', 'type' => 'text'],
                    'lokasi' => ['label' => 'Lokasi', 'type' => 'text'],
                    'panjang' => ['label' => 'Panjang (m)', 'type' => 'number', 'step' => '0.01'],
                    'lebar' => ['label' => 'Lebar (m)', 'type' => 'number', 'step' => '0.01'],
                    'tahun_bangun' => ['label' => 'Tahun Bangun', 'type' => 'number'],
                    'status_kepemilikan' => ['label' => 'Status Kepemilikan', 'type' => 'text'],
                    'kondisi_fisik' => ['label' => 'Kondisi Fisik', 'type' => 'text'],
                ];

            case 'Pertanian':
                return [
                    'jenis_alat' => ['label' => 'Jenis Alat', 'type' => 'text'],
                    'merk' => ['label' => 'Merk', 'type' => 'text'],
                    'tahun_perolehan' => ['label' => 'Tahun Perolehan', 'type' => 'number'],
                    'lokasi_penyimpanan' => ['label' => 'Lokasi Penyimpanan', 'type' => 'text'],
                ];

            default:
                return [
                    'nama_aset' => ['label' => 'Nama Aset', 'type' => 'text'],
                    'merk' => ['label' => 'Merk', 'type' => 'text'],
                    'tahun_perolehan' => ['label' => 'Tahun Perolehan', 'type' => 'number'],
                    'deskripsi' => ['label' => 'Deskripsi', 'type' => 'textarea'],
                ];
        }
    }
}
