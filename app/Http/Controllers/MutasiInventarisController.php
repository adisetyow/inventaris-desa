<?php

namespace App\Http\Controllers;

use App\Models\MutasiInventaris;
use App\Models\Inventaris;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MutasiInventarisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = MutasiInventaris::with(['inventaris', 'user'])
            ->orderBy('tanggal_mutasi', 'desc');

        // Perbaikan: Menggunakan $request->input() untuk mengakses parameter
        if ($request->has('inventaris_id')) {
            $query->where('inventaris_id', $request->input('inventaris_id'));
        }

        if ($request->has('tanggal_dari') && $request->has('tanggal_sampai')) {
            $query->whereBetween('tanggal_mutasi', [
                $request->input('tanggal_dari'),
                $request->input('tanggal_sampai')
            ]);
        }

        $mutasi = $query->paginate(10);
        $inventarisList = Inventaris::aktif()->get();

        return view('mutasi.index', compact('mutasi', 'inventarisList'));
    }

    public function create()
    {
        $inventarisList = Inventaris::aktif()->get();
        return view('mutasi.create', compact('inventarisList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'lokasi_awal' => 'required|string|max:255',
            'lokasi_baru' => 'required|string|max:255|different:lokasi_awal',
            'tanggal_mutasi' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            $data = $request->all();
            $data['dilakukan_oleh'] = Auth::id();
            $mutasi = MutasiInventaris::create($data);

            LogAktivitas::create([
                'user_id' => Auth::id(),
                'tipe_aksi' => 'mutasi',
                'deskripsi' => "Mutasi inventaris ID {$data['inventaris_id']} dari {$data['lokasi_awal']} ke {$data['lokasi_baru']}"
            ]);

            DB::commit();

            return redirect()->route('mutasi-inventaris.index')->with('success', 'Mutasi berhasil dicatat');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(MutasiInventaris $mutasiInventari)
    {
        $mutasiInventari->load(['inventaris', 'user']);
        return view('mutasi.show', compact('mutasiInventari'));
    }

    public function exportMutasi(Request $request)
    {
        $query = MutasiInventaris::with(['inventaris', 'user']);

        // Perbaikan: Menggunakan $request->input() untuk mengakses parameter
        if ($request->has('inventaris_id')) {
            $query->where('inventaris_id', $request->input('inventaris_id'));
        }

        if ($request->has('tanggal_dari') && $request->has('tanggal_sampai')) {
            $query->whereBetween('tanggal_mutasi', [
                $request->input('tanggal_dari'),
                $request->input('tanggal_sampai')
            ]);
        }

        $mutasi = $query->orderBy('tanggal_mutasi', 'desc')->get();

        $filename = 'mutasi_inventaris_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($mutasi) {
            $file = fopen('php://output', 'w');

            // Header CSV
            fputcsv($file, [
                'Tanggal Mutasi',
                'Kode Inventaris',
                'Nama Barang',
                'Lokasi Awal',
                'Lokasi Baru',
                'Keterangan',
                'Dilakukan Oleh'
            ]);

            // Data
            foreach ($mutasi as $item) {
                fputcsv($file, [
                    $item->tanggal_mutasi,
                    $item->inventaris->kode_inventaris,
                    $item->inventaris->nama_barang,
                    $item->lokasi_awal,
                    $item->lokasi_baru,
                    $item->keterangan ?? '-',
                    $item->user->nama
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}