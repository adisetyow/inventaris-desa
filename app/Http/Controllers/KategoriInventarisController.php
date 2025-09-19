<?php

namespace App\Http\Controllers;

use App\Models\KategoriInventaris;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KategoriInventarisController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $kategoris = KategoriInventaris::orderBy('nama_kategori', 'asc')->paginate(10);
        return view('kategori.index', compact('kategoris'));
    }


    public function edit(KategoriInventaris $kategori)
    {
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Memperbarui HANYA deskripsi kategori.
     * Nama kategori tidak dapat diubah.
     */
    public function update(Request $request, KategoriInventaris $kategori)
    {
        // Validasi hanya untuk deskripsi
        $request->validate([
            'deskripsi' => 'nullable|string|max:1000'
        ]);

        try {
            DB::beginTransaction();

            // Simpan deskripsi lama untuk log
            $deskripsiLama = $kategori->deskripsi;

            // Update hanya field deskripsi
            $kategori->deskripsi = $request->deskripsi;
            $kategori->save();

            LogAktivitas::create([
                'user_id' => Auth::id(),
                'tipe_aksi' => 'ubah',
                'deskripsi' => "Mengubah deskripsi kategori '{$kategori->nama_kategori}'."
            ]);

            DB::commit();

            return redirect()->route('kategori.index')->with('success', 'Deskripsi kategori berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }


}