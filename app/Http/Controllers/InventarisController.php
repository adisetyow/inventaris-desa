<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Inventaris;
use App\Models\KategoriInventaris;
use App\Models\PenghapusanInventaris;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\AsetHandlerService;
use App\Services\ReportService;
use App\Http\Requests\BaseInventarisRequest;
use Illuminate\Support\Facades\Storage;
use Exception;

class InventarisController extends Controller
{
    protected $asetHandlerService;
    protected $reportService;

    public function __construct(AsetHandlerService $asetHandlerService, ReportService $reportService)
    {
        $this->asetHandlerService = $asetHandlerService;
        $this->reportService = $reportService;
    }

    public function index()
    {
        $inventaris = Inventaris::with(['kategori'])
            ->filter(request(['search', 'kategori_id', 'kondisi', 'lokasi']))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        $kategoris = KategoriInventaris::all();
        $deletedCount = Inventaris::dihapus()->count();

        return view('inventaris.index', compact('inventaris', 'kategoris', 'deletedCount'));
    }

    public function generateKode(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_inventaris,id'
        ]);

        $kategori = KategoriInventaris::find($request->input('kategori_id'));

        if (!$kategori) {
            return response()->json(['error' => 'Kategori tidak ditemukan.'], 404);
        }

        // 1. Ambil kode awalan desa dari file config
        $kodeDesa = config('app.kode_desa');

        // 2. Ambil ID kategori dan format menjadi 2 digit (misal: 1 -> "01")
        $kodeKategori = str_pad($kategori->id, 2, '0', STR_PAD_LEFT);

        // 3. Buat prefix untuk query pencarian nomor terakhir
        // Contoh: "33.22.02.003.02."
        $prefix = $kodeDesa . '.' . $kodeKategori . '.';

        // 4. Cari inventaris terakhir dengan prefix yang sama
        $lastItem = Inventaris::where('kode_inventaris', 'like', $prefix . '%')
            ->orderBy('kode_inventaris', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastItem) {
            // Ekstrak nomor urut terakhir dari kode inventaris
            $lastNumberStr = substr($lastItem->kode_inventaris, strlen($prefix));
            $lastNumber = (int) $lastNumberStr;
            $nextNumber = $lastNumber + 1;
        }

        // 5. Gabungkan semua bagian menjadi kode final (nomor urut dibuat 3 digit)
        // Contoh: "33.22.02.003.02." + "001" -> "33.22.02.003.02.001"
        $finalCode = $prefix . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'kode' => $finalCode
        ]);
    }


    // public function generateKode(Request $request)
    // {
    //     $request->validate([
    //         'kategori_id' => 'required|exists:kategori_inventaris,id'
    //     ]);

    //     // Perbaikan: Gunakan first() dan pastikan kategori ada
    //     $kategori = KategoriInventaris::where('id', $request->input('kategori_id'))->first();

    //     if (!$kategori) {
    //         return response()->json(['error' => 'Kategori tidak ditemukan'], 404);
    //     }

    //     // Perbaikan: Gunakan getAttribute untuk mengakses nama_kategori
    //     $prefix = strtoupper(substr(str_replace(' ', '', $kategori->getAttribute('nama_kategori')), 0, 3));

    //     $lastItem = Inventaris::where('kode_inventaris', 'like', $prefix . '%')
    //         ->orderBy('kode_inventaris', 'desc')
    //         ->first();

    //     $nextNumber = 1;
    //     if ($lastItem) {
    //         $lastNumber = (int) substr($lastItem->kode_inventaris, strlen($prefix));
    //         $nextNumber = $lastNumber + 1;
    //     }

    //     return response()->json([
    //         'kode' => $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT)
    //     ]);
    // }

    public function create()
    {
        $kategori = KategoriInventaris::all();
        return view('inventaris.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $kategori = KategoriInventaris::findOrFail($request->input('kategori_id'));

        // Gunakan getAttribute untuk mengakses nama_kategori
        $formattedName = 'Aset' . str_replace(' ', '', $kategori->nama_kategori);
        $requestClass = 'App\\Http\\Requests\\' . $formattedName . 'Request';

        try {
            DB::beginTransaction();

            $validated = $this->validateRequest($request, $requestClass);

            $inventaris = Inventaris::create([
                'kategori_id' => $validated['kategori_id'],
                'nama_barang' => $validated['nama_barang'],
                'kode_inventaris' => $validated['kode_inventaris'],
                'jumlah' => $validated['jumlah'],
                'kondisi' => $validated['kondisi'],
                'lokasi_penempatan' => $validated['lokasi_penempatan'],
                'tanggal_masuk' => $validated['tanggal_masuk'],
                'sumber_dana' => $validated['sumber_dana'],
                'harga_perolehan' => $validated['harga_perolehan'],
                'masa_pakai_tahun' => $validated['masa_pakai_tahun'] ?? null,
            ]);

            $this->asetHandlerService->storeDetailAset($validated, $inventaris);

            // Gunakan createLog method
            LogAktivitas::createLog(
                Auth::id(),
                'tambah',
                "Menambahkan inventaris baru: {$validated['nama_barang']} (Kode: {$validated['kode_inventaris']})"
            );

            DB::commit();

            return redirect()->route('inventaris.index')
                ->with('success', 'Inventaris berhasil ditambahkan');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Inventaris $inventaris)
    {
        $inventaris->load(['kategori']);
        $detailAset = $inventaris->getDetailAset();

        return view('inventaris.show', compact('inventaris', 'detailAset'));
    }

    public function edit(Inventaris $inventaris)
    {
        $inventaris->load(['kategori']);
        $kategoris = KategoriInventaris::all();
        $detailAset = $inventaris->getDetailAset();

        // Menggabungkan data lama (jika ada error validasi) dengan data asli
        // Ini memastikan perubahan pada form detail tidak hilang saat error
        $detailDataForView = collect(old())->filter(function ($value, $key) use ($detailAset) {
            return $detailAset ? array_key_exists($key, $detailAset->getAttributes()) : false;
        })->all();

        // Jika tidak ada data lama, gunakan data dari database
        if (empty($detailDataForView)) {
            $detailDataForView = $detailAset;
        } else {
            // Gabungkan dengan data asli untuk field yang tidak diisi
            $detailDataForView = array_merge($detailAset ? $detailAset->toArray() : [], $detailDataForView);
        }

        // Kirim $detailDataForView ke view
        return view('inventaris.edit', compact('inventaris', 'kategoris', 'detailAset', 'detailDataForView'));
    }


    public function update(Request $request, Inventaris $inventaris)
    {
        // Debug: Log request data
        Log::info('Update request data:', $request->all());
        Log::info('Current inventaris kategori:', [
            'id' => $inventaris->kategori_id,
            'nama' => $inventaris->kategori->nama_kategori ?? 'not loaded'
        ]);

        $kategori = KategoriInventaris::findOrFail($request->input('kategori_id'));
        $formattedName = 'Aset' . str_replace(' ', '', $kategori->nama_kategori);
        $requestClass = 'App\\Http\\Requests\\' . $formattedName . 'Request';

        Log::info('Request class being used:', ['class' => $requestClass]);

        try {
            DB::beginTransaction();

            // Validasi data utama
            $validated = $this->validateRequest($request, $requestClass);

            // Debug: Log validated data
            Log::info('Validated data:', $validated);

            // Update inventaris utama
            $inventaris->update($validated);
            Log::info('Main inventaris updated successfully');

            // Refresh model untuk memastikan relasi kategori ter-load
            $inventaris->refresh();
            $inventaris->load('kategori');

            Log::info('About to update detail aset for category:', [
                'category' => $inventaris->kategori->nama_kategori
            ]);

            // Update detail aset
            $this->asetHandlerService->updateDetailAset($validated, $inventaris);

            Log::info('Detail aset update completed');

            // Log aktivitas
            LogAktivitas::createLog(
                Auth::id(),
                'ubah',
                "Memperbarui inventaris: {$validated['nama_barang']} (Kode: {$validated['kode_inventaris']})"
            );

            DB::commit();

            return redirect()->route('inventaris.show', $inventaris->getKey())
                ->with('success', 'Inventaris berhasil diperbarui');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function validateRequest(Request $request, string $requestClass): array
    {
        try {
            if (class_exists($requestClass)) {
                // Buat instance dari request class yang spesifik
                $formRequest = app($requestClass);
                $formRequest->setContainer(app());

                // Set data dari request asli
                $formRequest->replace($request->all());
                $formRequest->files->replace($request->files->all());

                // Set route dan method untuk form request
                $formRequest->setRouteResolver(function () use ($request) {
                    return $request->route();
                });

                // Validasi dan kembalikan data yang sudah divalidasi
                $formRequest->validateResolved();
                $validated = $formRequest->validated();

                // Debug: Log specific validation
                Log::info('Specific form request validation successful', [
                    'class' => $requestClass,
                    'validated' => $validated
                ]);

                return $validated;

            } else {
                // Fallback ke BaseInventarisRequest
                $formRequest = app(BaseInventarisRequest::class);
                $formRequest->setContainer(app());

                // Set data dari request asli
                $formRequest->replace($request->all());
                $formRequest->files->replace($request->files->all());

                // Set route dan method untuk form request
                $formRequest->setRouteResolver(function () use ($request) {
                    return $request->route();
                });

                // Validasi dan kembalikan data yang sudah divalidasi
                $formRequest->validateResolved();
                $validated = $formRequest->validated();

                // Debug: Log base validation
                Log::info('Base form request validation successful', [
                    'validated' => $validated
                ]);

                return $validated;
            }
        } catch (Exception $e) {
            Log::error('Validation error:', [
                'error' => $e->getMessage(),
                'class' => $requestClass,
                'request_data' => $request->all()
            ]);
            throw $e;
        }
    }

    public function destroy(Inventaris $inventaris)
    {
        try {
            $inventaris->delete(); // Ini akan otomatis melakukan soft delete

            LogAktivitas::createLog(
                Auth::id(),
                'ubah',
                "Mengarsipkan inventaris: {$inventaris->nama_barang} (Kode: {$inventaris->kode_inventaris})"
            );

            return redirect()->route('inventaris.index')
                ->with('success', 'Inventaris berhasil diarsipkan.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengarsipkan inventaris: ' . $e->getMessage());
        }
    }

    public function trashed()
    {
        $inventaris = Inventaris::onlyTrashed() // Mengambil HANYA data yang sudah di-soft delete
            ->with(['kategori'])
            ->latest('deleted_at')
            ->paginate(10)
            ->withQueryString();

        $activeCount = Inventaris::count(); // Hitung inventaris yang masih aktif

        return view('inventaris.trashed', compact('inventaris', 'activeCount'));
    }

    public function restore(Inventaris $inventaris)
    {
        try {
            $inventaris->restore();

            LogAktivitas::createLog(
                Auth::id(),
                'ubah',
                "Mengembalikan inventaris: {$inventaris->nama_barang} (Kode: {$inventaris->kode_inventaris})"
            );

            return redirect()->route('inventaris.trashed')
                ->with('success', 'Inventaris berhasil dikembalikan.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengembalikan inventaris: ' . $e->getMessage());
        }
    }


    /* // public function forceDelete(Request $request, $id)
    // {
    //     // Cari inventaris yang sudah di-soft delete
    //     $inventaris = Inventaris::onlyTrashed()->findOrFail($id);

    //     $validated = $request->validate([
    //         'nomor_berita_acara' => 'required|string|max:100|unique:penghapusan_inventaris,nomor_berita_acara',
    //         'alasan_penghapusan' => 'required|string|max:500',
    //         'file_berita_acara' => 'required|file|mimes:pdf|max:2048'
    //     ]);

    //     $filePath = null;

    //     try {
    //         DB::beginTransaction();

    //         // 1. Simpan file berita acara
    //         $filePath = $request->file('file_berita_acara')->store('berita_acara', 'public');

    //         // 2. Catat riwayat penghapusan (DISIMPAN SEBELUM data dihapus)
    //         PenghapusanInventaris::create([
    //             'inventaris_id' => $inventaris->id,
    //             'tanggal_penghapusan' => now(),
    //             'alasan_penghapusan' => $validated['alasan_penghapusan'],
    //             'nomor_berita_acara' => $validated['nomor_berita_acara'],
    //             'file_berita_acara' => $filePath,
    //             'dihapus_oleh' => Auth::id()
    //         ]);

    //         // 3. Hapus SEMUA relasi anak yang masih terkait
    //         // Hapus data mutasi
    //         $inventaris->mutasiInventaris()->delete();

    //         // Hapus data detail aset
    //         $inventaris->asetPeralatanKantor()->delete();
    //         $inventaris->asetPeralatanKomunikasi()->delete();
    //         $inventaris->asetBangunan()->delete();
    //         $inventaris->asetKendaraan()->delete();
    //         $inventaris->asetKesehatanPosyandu()->delete();
    //         $inventaris->asetTanah()->delete();
    //         $inventaris->asetInfrastruktur()->delete();
    //         $inventaris->asetPertanian()->delete();
    //         $inventaris->asetLainnya()->delete();

    //         // Simpan informasi untuk log
    //         $namaBarangLog = $inventaris->nama_barang;
    //         $kodeInventarisLog = $inventaris->kode_inventaris;

    //         // 4. Hapus data dari tabel inventaris secara permanen
    //         $inventaris->forceDelete();

    //         // 5. Buat log aktivitas
    //         LogAktivitas::createLog(
    //             Auth::id(),
    //             'hapus',
    //             "Menghapus permanen inventaris: {$namaBarangLog} (Kode: {$kodeInventarisLog})"
    //         );

    //         DB::commit();

    //         return redirect()->route('inventaris.trashed')
    //             ->with('success', 'Inventaris berhasil dihapus secara permanen.');

    //     } catch (Exception $e) {
    //         DB::rollBack();

    //         if ($filePath && Storage::disk('public')->exists($filePath)) {
    //             Storage::disk('public')->delete($filePath);
    //         }

    //         return redirect()->back()
    //             ->with('error', 'Gagal menghapus inventaris: ' . $e->getMessage())->withInput();
    //     }
    // }
    */

    public function getKategoriDetail($id)
    {
        try {
            $kategori = KategoriInventaris::findOrFail($id);
            // Gunakan getAttribute untuk mengakses nama_kategori
            $fields = $this->asetHandlerService->getFieldsForCategory($kategori->getAttribute('nama_kategori'));

            return response()->json(['fields' => $fields]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Kategori tidak ditemukan'], 404);
        }
    }

    public function report(Request $request)
    {
        $inventaris = $this->reportService->getFilteredInventaris($request);
        $kategoris = KategoriInventaris::all();
        $stats = $this->reportService->generateReportStats($inventaris);

        return view('inventaris.report', [
            'inventaris' => $inventaris,
            'kategoris' => $kategoris,
            'totalInventaris' => $stats['totalInventaris'],
            'totalNilai' => $stats['totalNilai'],
            'kondisiStats' => $stats['kondisiStats'],
            'kategoriStats' => $stats['kategoriStats']
        ]);
    }

    public function laporanIndex()
    {
        $kategoris = KategoriInventaris::all();
        return view('laporan.index', compact('kategoris'));
    }

    public function exportCsv(Request $request)
    {
        return $this->reportService->exportCsv($request);
    }

    public function exportPdf(Request $request)
    {
        return $this->reportService->exportPdf($request);
    }
}