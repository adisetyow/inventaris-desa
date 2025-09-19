<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LogAktivitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = LogAktivitas::with(['user'])
            ->orderBy('waktu', 'desc');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->has('tipe_aksi')) {
            $query->where('tipe_aksi', $request->input('tipe_aksi'));
        }

        if ($request->has('tanggal_dari') && $request->has('tanggal_sampai')) {
            $tanggalDari = Carbon::parse($request->input('tanggal_dari'))
                ->setTimezone('Asia/Jakarta')
                ->startOfDay();
            $tanggalSampai = Carbon::parse($request->input('tanggal_sampai'))
                ->setTimezone('Asia/Jakarta')
                ->endOfDay();

            $query->whereBetween('waktu', [
                $tanggalDari,
                $tanggalSampai
            ]);
        }

        $logs = $query->paginate(20);

        return view('log.index', compact('logs'));
    }

    public static function logActivity($tipeAksi, $deskripsi = null, $userId = null)
    {
        $userId = $userId ?? Auth::id();

        if ($userId) {
            LogAktivitas::createLog($userId, $tipeAksi, $deskripsi);
        }
    }
}