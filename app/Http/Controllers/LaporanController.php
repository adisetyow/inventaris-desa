<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf; // Tambahkan ini untuk PDF
use Illuminate\Support\Facades\Response; // Tambahkan ini untuk Excel

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman utama laporan dengan filter.
     */
    public function index(Request $request)
    {
        // Panggil method query yang bisa difilter
        $query = $this->getFilteredQuery($request);
        $inventaris = $query->paginate(15)->withQueryString();

        // Ambil data untuk filter dropdowns
        $lokasiList = Inventaris::select('lokasi_penempatan')->distinct()->pluck('lokasi_penempatan');
        $kondisiList = ['Baik', 'Perlu Perbaikan', 'Rusak', 'Hilang'];

        // Menghitung statistik berdasarkan hasil query yang sudah difilter
        $totalItems = (clone $query)->count();
        $totalNilaiAset = (clone $query)->get()->sum('total_nilai');


        return view('laporan.index', [
            'inventaris' => $inventaris,
            'lokasiList' => $lokasiList,
            'kondisiList' => $kondisiList,
            'totalItems' => $totalItems,
            'totalNilaiAset' => $totalNilaiAset,
            'request' => $request, // Kirim request untuk mengisi ulang form filter
        ]);
    }

    /**
     * Logika utama untuk query inventaris dengan filter.
     * Method ini akan kita gunakan di semua fungsi (index, pdf, excel).
     */
    private function getFilteredQuery(Request $request)
    {
        $query = Inventaris::with('kategori')->latest();

        // Filter berdasarkan Kondisi
        $query->when($request->filled('kondisi'), function ($q) use ($request) {
            return $q->where('kondisi', $request->kondisi);
        });

        // Filter berdasarkan Lokasi
        $query->when($request->filled('lokasi'), function ($q) use ($request) {
            return $q->where('lokasi_penempatan', $request->lokasi);
        });

        // Filter berdasarkan Bulan (dari tanggal masuk)
        $query->when($request->filled('bulan'), function ($q) use ($request) {
            return $q->whereMonth('tanggal_masuk', $request->bulan);
        });

        // Filter berdasarkan Tahun (dari tanggal masuk)
        $query->when($request->filled('tahun'), function ($q) use ($request) {
            return $q->whereYear('tanggal_masuk', $request->tahun);
        });

        // Filter berdasarkan Keyword Pencarian (nama barang atau kode)
        $query->when($request->filled('search'), function ($q) use ($request) {
            return $q->where(function ($sub) use ($request) {
                $sub->where('nama_barang', 'like', '%' . $request->search . '%')
                    ->orWhere('kode_inventaris', 'like', '%' . $request->search . '%');
            });
        });

        return $query;
    }

    /**
     * Export laporan ke format Excel (CSV).
     */
    public function exportExcel(Request $request)
    {
        $inventaris = $this->getFilteredQuery($request)->get();
        $filename = 'laporan_inventaris_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($inventaris) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Kode',
                'Nama Barang',
                'Kategori',
                'Jumlah',
                'Kondisi',
                'Lokasi',
                'Tgl Masuk',
                'Harga Satuan',
                'Total Nilai'
            ]);
            foreach ($inventaris as $item) {
                fputcsv($file, [
                    $item->kode_inventaris,
                    $item->nama_barang,
                    $item->kategori->nama_kategori ?? '-',
                    $item->jumlah,
                    $item->kondisi,
                    $item->lokasi_penempatan,
                    $item->tanggal_masuk,
                    $item->harga_perolehan,
                    $item->total_nilai, // Accessor dari langkah sebelumnya
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export laporan ke format PDF.
     */
    public function exportPdf(Request $request)
    {
        // Pastikan Anda sudah install: composer require barryvdh/laravel-dompdf
        $inventaris = $this->getFilteredQuery($request)->get();
        $totalNilaiAset = $inventaris->sum('total_nilai');

        $data = [
            'inventaris' => $inventaris,
            'totalNilaiAset' => $totalNilaiAset,
            'filters' => $request->all(),
            'date' => now()->translatedFormat('d F Y')
        ];

        $pdf = Pdf::loadView('laporan.pdf', $data)->setPaper('a4', 'portrait');
        return $pdf->download('Laporan_Inventaris_Karangduren_' . date('Y-m-d') . '.pdf');
    }
}