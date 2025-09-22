<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Inventaris extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventaris';

    protected $fillable = [
        'kategori_id',
        'nama_barang',
        'kode_inventaris',
        'jumlah',
        'kondisi',
        'lokasi_penempatan',
        'tanggal_masuk',
        'sumber_dana',
        'harga_perolehan',
        'masa_pakai_tahun',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_dihapus' => 'date',
        'harga_perolehan' => 'decimal:2'
    ];

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(KategoriInventaris::class, 'kategori_id');
    }

    public function asetPeralatanKantor()
    {
        return $this->hasOne(AsetPeralatanKantor::class, 'inventaris_id');
    }

    public function asetPeralatanKomunikasi()
    {
        return $this->hasOne(AsetPeralatanKomunikasi::class, 'inventaris_id');
    }

    public function asetBangunan()
    {
        return $this->hasOne(AsetBangunan::class, 'inventaris_id');
    }

    public function asetKendaraan()
    {
        return $this->hasOne(AsetKendaraan::class, 'inventaris_id');
    }

    public function asetKesehatanPosyandu()
    {
        return $this->hasOne(AsetKesehatanPosyandu::class, 'inventaris_id');
    }

    public function asetTanah()
    {
        return $this->hasOne(AsetTanah::class, 'inventaris_id');
    }

    public function asetInfrastruktur()
    {
        return $this->hasOne(AsetInfrastruktur::class, 'inventaris_id');
    }

    public function asetPertanian()
    {
        return $this->hasOne(AsetPertanian::class, 'inventaris_id');
    }

    public function asetLainnya()
    {
        return $this->hasOne(AsetLainnya::class, 'inventaris_id');
    }

    /**
     * Accessor untuk menghitung total nilai aset.
     *
     * @return float
     */
    public function getTotalNilaiAttribute()
    {
        return $this->attributes['harga_perolehan'] * $this->attributes['jumlah'];
    }


    public function penghapusanInventaris()
    {
        return $this->hasMany(PenghapusanInventaris::class, 'inventaris_id_lama');
    }

    public function penghapusanTerakhir()
    {
        return $this->hasOne(PenghapusanInventaris::class, 'inventaris_id_lama')->latest();
    }

    public function mutasiInventaris()
    {
        return $this->hasMany(MutasiInventaris::class, 'inventaris_id');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status_penghapusan', 'Aktif');
    }

    public function scopeDihapus($query)
    {
        return $query->where('status_penghapusan', 'Dihapus');
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('nama_barang', 'like', '%' . $search . '%')
                    ->orWhere('kode_inventaris', 'like', '%' . $search . '%');
            });
        });

        $query->when($filters['kategori_id'] ?? false, function ($query, $kategoriId) {
            return $query->where('kategori_id', $kategoriId);
        });

        $query->when($filters['kondisi'] ?? false, function ($query, $kondisi) {
            return $query->where('kondisi', $kondisi);
        });

        $query->when($filters['lokasi'] ?? false, function ($query, $lokasi) {
            return $query->where('lokasi_penempatan', 'like', '%' . $lokasi . '%');
        });
    }

    // Helper method untuk mendapatkan detail aset sesuai kategori
    public function getDetailAset()
    {
        if (!$this->relationLoaded('kategori') || !$this->kategori) {
            return null;
        }

        // Gunakan getAttribute untuk mengakses nama_kategori
        $namaKategori = $this->kategori->getAttribute('nama_kategori');

        switch ($namaKategori) {
            case 'Peralatan Kantor':
                return $this->asetPeralatanKantor;
            case 'Peralatan Komunikasi':
                return $this->asetPeralatanKomunikasi;
            case 'Bangunan':
                return $this->asetBangunan;
            case 'Kendaraan':
                return $this->asetKendaraan;
            case 'Kesehatan Posyandu':
                return $this->asetKesehatanPosyandu;
            case 'Tanah':
                return $this->asetTanah;
            case 'Infrastruktur':
                return $this->asetInfrastruktur;
            case 'Pertanian':
                return $this->asetPertanian;
            default:
                return $this->asetLainnya;
        }

    }
}