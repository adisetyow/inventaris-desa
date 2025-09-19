<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutasiInventaris extends Model
{
    use HasFactory;

    protected $table = 'mutasi_inventaris';
    protected $fillable = [
        'inventaris_id',
        'lokasi_awal',
        'lokasi_baru',
        'tanggal_mutasi',
        'keterangan',
        'dilakukan_oleh'
    ];

    protected $casts = [
        'tanggal_mutasi' => 'date'
    ];

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'inventaris_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'dilakukan_oleh');
    }
}