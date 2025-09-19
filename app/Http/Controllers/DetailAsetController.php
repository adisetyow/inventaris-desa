<?php

namespace App\Http\Controllers;

use App\Models\KategoriInventaris;
use App\Models\AsetBangunan;
use App\Models\AsetKendaraan;
use App\Models\AsetKesehatanPosyandu;
use App\Models\AsetLainnya;
use App\Models\AsetPeralatanKantor;
use App\Models\AsetPeralatanKomunikasi;
use App\Models\AsetPertanian;
use App\Models\AsetTanah;
use App\Models\Inventaris;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailAsetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Inventaris $inventaris)
    {
        // Perbaikan 1: Gunakan getAttribute() untuk mengakses nama_kategori
        $kategori = $inventaris->kategori->getAttribute('nama_kategori');

        try {
            DB::beginTransaction();

            // Perbaikan 2: Gunakan getKey() untuk mengakses ID inventaris
            $inventarisId = $inventaris->getKey();

            switch ($kategori) {
                case 'Peralatan Kantor':
                    $this->validatePeralatanKantor($request);
                    AsetPeralatanKantor::create($request->all() + ['inventaris_id' => $inventarisId]);
                    break;

                case 'Peralatan Komunikasi':
                    $this->validatePeralatanKomunikasi($request);
                    AsetPeralatanKomunikasi::create($request->all() + ['inventaris_id' => $inventarisId]);
                    break;

                case 'Bangunan':
                    $this->validateBangunan($request);
                    AsetBangunan::create($request->all() + ['inventaris_id' => $inventarisId]);
                    break;

                case 'Kendaraan':
                    $this->validateKendaraan($request);
                    AsetKendaraan::create($request->all() + ['inventaris_id' => $inventarisId]);
                    break;

                case 'Kesehatan Posyandu':
                    $this->validateKesehatanPosyandu($request);
                    AsetKesehatanPosyandu::create($request->all() + ['inventaris_id' => $inventarisId]);
                    break;

                case 'Tanah':
                    $this->validateTanah($request);
                    AsetTanah::create($request->all() + ['inventaris_id' => $inventarisId]);
                    break;

                case 'Pertanian':
                    $this->validatePertanian($request);
                    AsetPertanian::create($request->all() + ['inventaris_id' => $inventarisId]);
                    break;

                default:
                    $this->validateLainnya($request);
                    AsetLainnya::create($request->all() + ['inventaris_id' => $inventarisId]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Detail aset berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Inventaris $inventaris)
    {
        // Perbaikan 3: Gunakan getAttribute() untuk mengakses nama_kategori
        $kategori = $inventaris->kategori->getAttribute('nama_kategori');
        $detailAset = $inventaris->getDetailAset();

        try {
            DB::beginTransaction();

            switch ($kategori) {
                case 'Peralatan Kantor':
                    $this->validatePeralatanKantor($request);
                    $detailAset->update($request->all());
                    break;

                case 'Peralatan Komunikasi':
                    $this->validatePeralatanKomunikasi($request);
                    $detailAset->update($request->all());
                    break;

                case 'Bangunan':
                    $this->validateBangunan($request);
                    $detailAset->update($request->all());
                    break;

                case 'Kendaraan':
                    $this->validateKendaraan($request);
                    $detailAset->update($request->all());
                    break;

                case 'Kesehatan Posyandu':
                    $this->validateKesehatanPosyandu($request);
                    $detailAset->update($request->all());
                    break;

                case 'Tanah':
                    $this->validateTanah($request);
                    $detailAset->update($request->all());
                    break;

                case 'Pertanian':
                    $this->validatePertanian($request);
                    $detailAset->update($request->all());
                    break;

                default:
                    $this->validateLainnya($request);
                    $detailAset->update($request->all());
            }

            DB::commit();
            return redirect()->back()->with('success', 'Detail aset berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Validasi untuk setiap jenis aset
    protected function validatePeralatanKantor(Request $request)
    {
        return $request->validate([
            'merk' => 'required|string|max:100',
            'bahan' => 'nullable|string|max:100',
            'tahun_perolehan' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'warna' => 'nullable|string|max:50',
            'nomor_inventaris_internal' => 'nullable|string|max:100'
        ]);
    }

    protected function validatePeralatanKomunikasi(Request $request)
    {
        return $request->validate([
            'merk' => 'required|string|max:100',
            'frekuensi' => 'nullable|string|max:100',
            'serial_number' => 'nullable|string|max:100',
            'jenis_peralatan' => 'required|string|max:100',
            'tahun_perolehan' => 'required|integer|min:1900|max:' . (date('Y') + 1)
        ]);
    }

    protected function validateBangunan(Request $request)
    {
        return $request->validate([
            'nama_bangunan' => 'required|string|max:255',
            'alamat' => 'required|string|max:500',
            'luas' => 'required|numeric|min:0',
            'tahun_bangun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'status_sertifikat' => 'required|string|max:100',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'kondisi_fisik' => 'required|string|max:100'
        ]);
    }

    protected function validateKendaraan(Request $request)
    {
        return $request->validate([
            'jenis_kendaraan' => 'required|string|max:100',
            'merk_tipe' => 'required|string|max:100',
            'nomor_polisi' => 'nullable|string|max:20',
            'nomor_rangka' => 'nullable|string|max:100',
            'nomor_mesin' => 'nullable|string|max:100',
            'tahun_perolehan' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'warna' => 'nullable|string|max:50'
        ]);
    }

    protected function validateKesehatanPosyandu(Request $request)
    {
        return $request->validate([
            'nama_alat' => 'required|string|max:255',
            'merk' => 'required|string|max:100',
            'tahun_perolehan' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'jumlah' => 'required|integer|min:1',
            'lokasi_penempatan' => 'required|string|max:255'
        ]);
    }

    protected function validateTanah(Request $request)
    {
        return $request->validate([
            'luas' => 'required|numeric|min:0',
            'alamat' => 'required|string|max:500',
            'nomor_sertifikat' => 'nullable|string|max:100',
            'status_sertifikat' => 'required|string|max:100',
            'tahun_diperoleh' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'penggunaan_saat_ini' => 'required|string|max:255'
        ]);
    }

    protected function validatePertanian(Request $request)
    {
        return $request->validate([
            'jenis_alat' => 'required|string|max:100',
            'merk' => 'required|string|max:100',
            'tahun_perolehan' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'lokasi_penyimpanan' => 'required|string|max:255'
        ]);
    }

    protected function validateLainnya(Request $request)
    {
        return $request->validate([
            'nama_aset' => 'required|string|max:255',
            'merk' => 'nullable|string|max:100',
            'tahun_perolehan' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'deskripsi' => 'nullable|string'
        ]);
    }
}