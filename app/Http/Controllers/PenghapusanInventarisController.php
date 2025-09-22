<?php

namespace App\Http\Controllers;

use App\Models\PenghapusanInventaris;
use App\Models\Inventaris;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PenghapusanInventarisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // 1. Mulai query dasar
        $query = PenghapusanInventaris::with('user')->orderBy('tanggal_penghapusan', 'desc');

        // 2. Terapkan PENCARIAN jika ada input 'search'
        $query->when($request->input('search'), function ($q, $search) {
            return $q->where(function ($subQuery) use ($search) {
                $subQuery->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('kode_inventaris', 'like', "%{$search}%")
                    ->orWhere('nomor_berita_acara', 'like', "%{$search}%");
            });
        });

        // 3. Terapkan filter lain jika ada (opsional, sudah ada)
        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal_penghapusan', [
                $request->input('tanggal_dari'),
                $request->input('tanggal_sampai')
            ]);
        }

        // 4. Lakukan paginasi setelah semua filter diterapkan
        $penghapusan = $query->paginate(10)->withQueryString();

        return view('penghapusan.index', compact('penghapusan'));
    }


    public function create(Request $request)
    {
        // 1. Validasi untuk memastikan inventaris_id dikirim dari halaman arsip
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id,deleted_at,NOT_NULL'
        ]);

        // 2. Cari inventaris yang ada di dalam arsip (onlyTrashed)
        $inventaris = Inventaris::onlyTrashed()->findOrFail($request->inventaris_id);

        // 3. Kirim data inventaris yang ditemukan ke view
        return view('penghapusan.create', compact('inventaris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'tanggal_penghapusan' => 'required|date|before_or_equal:today',
            'alasan_penghapusan' => 'required|string|max:1000',
            'nomor_berita_acara' => 'required|string|max:100|unique:penghapusan_inventaris,nomor_berita_acara',
            'file_berita_acara' => 'required|file|mimes:pdf|max:2048'
        ]);

        $inventaris = Inventaris::withTrashed()->find($request->inventaris_id);

        if (!$inventaris) {
            return redirect()->route('arsip.index')->with('error', 'Inventaris yang akan dihapus tidak ditemukan.');
        }

        DB::beginTransaction();
        try {
            // 1. Upload file berita acara
            $filePath = $request->file('file_berita_acara')->store('berita_acara_penghapusan', 'public');

            // 2. BUAT CATATAN RIWAYAT dengan MENYALIN data penting
            PenghapusanInventaris::create([
                'inventaris_id_lama' => $inventaris->id,
                'kode_inventaris' => $inventaris->kode_inventaris,
                'nama_barang' => $inventaris->nama_barang,
                'tanggal_penghapusan' => $request->tanggal_penghapusan,
                'alasan_penghapusan' => $request->alasan_penghapusan,
                'nomor_berita_acara' => $request->nomor_berita_acara,
                'file_berita_acara' => $filePath,
                'dihapus_oleh' => Auth::id(),
            ]);

            // 3. HAPUS PERMANEN inventaris setelah riwayat dicatat
            $inventaris->forceDelete();

            // 4. Catat Log Aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'tipe_aksi' => 'hapus',
                'deskripsi' => "Menghapus permanen inventaris '{$inventaris->nama_barang}' (Kode: {$inventaris->kode_inventaris})"
            ]);

            DB::commit();

            // 5. Arahkan ke halaman riwayat penghapusan
            return redirect()->route('penghapusan.index')->with('success', 'Inventaris berhasil dihapus permanen dan riwayatnya telah dicatat.');

        } catch (\Exception $e) {
            DB::rollback();
            if (isset($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            return redirect()->back()->with('error', 'Gagal menghapus inventaris: ' . $e->getMessage())->withInput();
        }
    }

    public function show(PenghapusanInventaris $penghapusanInventari)
    {
        $penghapusanInventari->load(['inventaris', 'user']);
        return view('penghapusan.show', compact('penghapusanInventari'));
    }

    public function exportPenghapusan(Request $request)
    {
        $query = PenghapusanInventaris::with(['inventaris', 'user']);

        if ($request->filled('inventaris_id')) {
            $query->where('inventaris_id', $request->input('inventaris_id'));
        }

        if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
            $query->whereBetween('tanggal_penghapusan', [
                $request->input('tanggal_dari'),
                $request->input('tanggal_sampai')
            ]);
        }

        $penghapusan = $query->orderBy('tanggal_penghapusan', 'desc')->get();

        $filename = 'penghapusan_inventaris_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($penghapusan) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'Tanggal Penghapusan',
                'Kode Inventaris',
                'Nama Barang',
                'Nomor Berita Acara',
                'Alasan Penghapusan',
                'Dihapus Oleh'
            ]);

            // Data
            foreach ($penghapusan as $item) {
                fputcsv($file, [
                    $item->tanggal_penghapusan,
                    $item->inventaris->kode_inventaris,
                    $item->inventaris->nama_barang,
                    $item->nomor_berita_acara,
                    $item->alasan_penghapusan,
                    $item->user->nama
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function destroy(PenghapusanInventaris $penghapusan)
    {
        try {
            // 1. Hapus file PDF dari storage
            if ($penghapusan->file_berita_acara) {
                Storage::disk('public')->delete($penghapusan->file_berita_acara);
            }

            // 2. Simpan nama barang untuk pesan log sebelum dihapus
            $namaBarang = $penghapusan->nama_barang;

            // 3. Hapus data dari database
            $penghapusan->delete();

            // 4. Catat Log Aktivitas
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'tipe_aksi' => 'hapus permanen',
                'deskripsi' => "Menghapus permanen riwayat penghapusan untuk barang '{$namaBarang}'"
            ]);

            return redirect()->route('penghapusan.index')->with('success', 'Riwayat penghapusan berhasil dihapus permanen.');

        } catch (\Exception $e) {
            return redirect()->route('penghapusan.index')->with('error', 'Gagal menghapus riwayat: ' . $e->getMessage());
        }
    }
}