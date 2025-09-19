<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventaris;
use App\Models\KategoriInventaris;
use App\Models\MutasiInventaris;
use App\Models\PenghapusanInventaris;
use App\Models\LogAktivitas;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik utama
        $totalInventaris = Inventaris::aktif()->count();
        $totalNilai = Inventaris::aktif()->sum('harga_perolehan');
        $totalKategori = KategoriInventaris::count();
        $totalMutasi = MutasiInventaris::whereMonth('tanggal_mutasi', now()->month)->count();

        // Kondisi inventaris
        $kondisiStats = Inventaris::aktif()
            ->selectRaw('kondisi, count(*) as total')
            ->groupBy('kondisi')
            ->get()
            ->pluck('total', 'kondisi')
            ->toArray();

        // Inventaris per kategori
        $kategoriStats = Inventaris::aktif()
            ->join('kategori_inventaris', 'inventaris.kategori_id', '=', 'kategori_inventaris.id')
            ->selectRaw('kategori_inventaris.nama_kategori, count(*) as total')
            ->groupBy('kategori_inventaris.nama_kategori')
            ->get()
            ->pluck('total', 'nama_kategori')
            ->toArray();

        // Inventaris terbaru
        $inventarisTerbaru = Inventaris::aktif()
            ->with('kategori')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Mutasi terbaru
        $mutasiTerbaru = MutasiInventaris::with(['inventaris', 'user'])
            ->orderBy('tanggal_mutasi', 'desc')
            ->take(5)
            ->get();

        // Log aktivitas terbaru
        $logAktivitas = LogAktivitas::with(['user', 'inventaris'])
            ->orderBy('waktu', 'desc')
            ->take(10)
            ->get();

        // Inventaris yang perlu perhatian
        $inventarisPerluPerhatian = Inventaris::aktif()
            ->where(function ($query) {
                $query->where('kondisi', 'Rusak')
                    ->orWhere('kondisi', 'Perlu Perbaikan');
            })
            ->with('kategori')
            ->take(5)
            ->get();

        // Grafik inventaris bulanan (6 bulan terakhir)
        $inventarisBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $count = Inventaris::whereYear('tanggal_masuk', $bulan->year)
                ->whereMonth('tanggal_masuk', $bulan->month)
                ->count();

            $inventarisBulanan[] = [
                'bulan' => $bulan->format('M Y'),
                'total' => $count
            ];
        }

        return view('dashboard.index', [
            'totalInventaris' => Inventaris::count(),
            'totalNilai' => Inventaris::sum('harga_perolehan'),
            'totalKategori' => KategoriInventaris::count(),
            'totalMutasi' => MutasiInventaris::whereMonth('tanggal_mutasi', now()->month)->count(),
            'inventarisBulanan' => $this->getInventarisBulanan(),
            'kondisiStats' => $this->getKondisiStats(),
            'inventarisTerbaru' => Inventaris::with('kategori')->latest()->take(5)->get(),
            'mutasiTerbaru' => MutasiInventaris::with(['inventaris', 'user'])->latest()->take(5)->get(),
            'inventarisPerluPerhatian' => Inventaris::whereIn('kondisi', ['Rusak', 'Perlu Perbaikan'])->take(5)->get(),
            'logAktivitas' => LogAktivitas::with('user')->latest()->take(5)->get()
        ]);
    }

    private function getInventarisBulanan()
    {
        $inventarisBulanan = [];

        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i);
            $count = Inventaris::whereYear('tanggal_masuk', $bulan->year)
                ->whereMonth('tanggal_masuk', $bulan->month)
                ->count();

            $inventarisBulanan[] = [
                'bulan' => $bulan->format('M Y'),
                'total' => $count
            ];
        }

        return $inventarisBulanan;
    }

    private function getKondisiStats()
    {
        return Inventaris::aktif()
            ->selectRaw('kondisi, count(*) as total')
            ->groupBy('kondisi')
            ->get()
            ->pluck('total', 'kondisi')
            ->toArray();
    }
}