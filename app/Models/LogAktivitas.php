<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\User;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';
    protected $fillable = [
        'user_id',
        'tipe_aksi',
        'deskripsi',
        'waktu',
    ];

    protected $casts = [
        'waktu' => 'datetime'
    ];

    public $timestamps = false;


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Pastikan waktu selalu diset saat creating
            if (!$model->waktu) {
                $model->waktu = now()->setTimezone('Asia/Jakarta');
            }
        });
    }

    public function getWaktuAttribute($value)
    {
        if ($value) {
            return Carbon::parse($value)->setTimezone('Asia/Jakarta');
        }
        return null;
    }

    public function setWaktuAttribute($value)
    {
        if ($value) {
            $this->attributes['waktu'] = Carbon::parse($value)->setTimezone('Asia/Jakarta');
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function inventaris()
    {
        return $this->belongsTo(Inventaris::class, 'id_inventaris');
    }

    // Perbaiki method createLog untuk memastikan waktu selalu ada
    public static function createLog($userId, $tipeAksi, $deskripsi = null)
    {
        return self::create([
            'user_id' => $userId,
            'tipe_aksi' => $tipeAksi,
            'deskripsi' => $deskripsi,
            'waktu' => now()->setTimezone('Asia/Jakarta') // Tambahkan explicit waktu
        ]);
    }

    // Aksesor untuk tipe_aksi
    public function getTipeAksiAttribute($value)
    {
        return $value;
    }

    public function getActionIcon()
    {
        $tipeAksi = $this->getAttribute('tipe_aksi');

        switch ($tipeAksi) {
            case 'tambah':
                return 'plus-circle';
            case 'ubah':
                return 'edit';
            case 'hapus':
            case 'penghapusan':
                return 'trash';
            case 'mutasi':
                return 'exchange-alt';
            case 'export':
            case 'cetak':
                return 'file-export';
            case 'login':
                return 'sign-in-alt';
            case 'logout':
                return 'sign-out-alt';
            default:
                return 'circle';
        }
    }

    public function getActionColor()
    {
        $tipeAksi = $this->getAttribute('tipe_aksi');

        switch ($tipeAksi) {
            case 'tambah':
                return 'success';
            case 'ubah':
                return 'warning';
            case 'hapus':
            case 'penghapusan':
                return 'danger';
            case 'mutasi':
                return 'info';
            case 'export':
            case 'cetak':
                return 'primary';
            case 'login':
                return 'success';
            case 'logout':
                return 'secondary';
            default:
                return 'light';
        }
    }
}