<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\KategoriInventarisController;
use App\Http\Controllers\MutasiInventarisController;
use App\Http\Controllers\PenghapusanInventarisController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\DetailAsetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Struktur:
| - Public routes (login, register)
| - Auth routes (logout, dashboard)
| - Read-only routes (Administrator & Viewer) - VIEW SEMUA HALAMAN
| - Write routes (Administrator only) - CREATE, UPDATE, DELETE
|--------------------------------------------------------------------------
*/

// === PUBLIC ROUTES ===
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


// === AUTHENTICATED ROUTES ===
Route::middleware(['auth'])->group(function () {

    // Logout & Dashboard - Semua user yang login
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // === ROUTES UNTUK VIEWER & ADMINISTRATOR (READ-ONLY + VIEW ALL PAGES) ===
    Route::middleware(['role:Administrator|Viewer'])->group(function () {

        // ==================== INVENTARIS - READ ONLY ====================
        Route::prefix('inventaris')->name('inventaris.')->group(function () {
            // View pages
            Route::get('/', [InventarisController::class, 'index'])->name('index');
            Route::get('/create', [InventarisController::class, 'create'])->name('create'); // Viewer bisa lihat form (tapi tombol simpan di-hidden di view)
            Route::get('/arsip/data', [InventarisController::class, 'trashed'])->name('trashed');
            Route::get('/{inventaris}', [InventarisController::class, 'show'])->name('show');
            Route::get('/{inventaris}/edit', [InventarisController::class, 'edit'])->name('edit'); // Viewer bisa lihat form (tapi tombol update di-hidden di view)

            // Get kategori detail (untuk form dropdown)
            Route::get('/get-kategori-detail/{kategori}', [InventarisController::class, 'getKategoriDetail'])->name('getKategoriDetail');

            // Export - kedua role bisa export
            Route::get('/export/csv', [InventarisController::class, 'exportCsv'])->name('export.csv');
            Route::get('/export/pdf', [InventarisController::class, 'exportPdf'])->name('export.pdf');
            Route::get('/export/excel', [InventarisController::class, 'exportExcel'])->name('export.excel');
            Route::get('/report', [InventarisController::class, 'report'])->name('report');
        });

        // ==================== KATEGORI - READ ONLY ====================
        Route::get('/kategori', [KategoriInventarisController::class, 'index'])->name('kategori.index');
        Route::get('/kategori/{kategori}/edit', [KategoriInventarisController::class, 'edit'])->name('kategori.edit');

        // ==================== MUTASI - READ ONLY ====================
        Route::get('/mutasi-inventaris', [MutasiInventarisController::class, 'index'])->name('mutasi-inventaris.index');
        Route::get('/mutasi-inventaris/create', [MutasiInventarisController::class, 'create'])->name('mutasi-inventaris.create');
        Route::get('/mutasi-inventaris/{mutasiInventaris}', [MutasiInventarisController::class, 'show'])->name('mutasi-inventaris.show');

        // ==================== PENGHAPUSAN - READ ONLY ====================
        Route::get('/penghapusan-inventaris', [PenghapusanInventarisController::class, 'index'])->name('penghapusan-inventaris.index');
        Route::get('/penghapusan', [PenghapusanInventarisController::class, 'index'])->name('penghapusan.index');
        Route::get('/penghapusan/create', [PenghapusanInventarisController::class, 'create'])->name('penghapusan.create');
        Route::get('/penghapusan/{penghapusan}', [PenghapusanInventarisController::class, 'show'])->name('penghapusan.show');

        // ==================== LOG AKTIVITAS ====================
        Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');

        // ==================== LAPORAN ====================
        Route::prefix('laporan')->name('laporan.')->group(function () {
            Route::get('/', [LaporanController::class, 'index'])->name('index');
            Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('export.excel');
            Route::get('/export/pdf', [LaporanController::class, 'exportPdf'])->name('export.pdf');
        });

        // Export untuk mutasi & penghapusan
        Route::prefix('export')->group(function () {
            Route::get('/mutasi', [MutasiInventarisController::class, 'exportMutasi'])->name('export.mutasi');
            Route::get('/penghapusan', [PenghapusanInventarisController::class, 'exportPenghapusan'])->name('export.penghapusan');
        });
    });

    // === ROUTES KHUSUS ADMINISTRATOR (CREATE, UPDATE, DELETE) ===
    Route::middleware(['role:Administrator'])->group(function () {

        // ==================== INVENTARIS - WRITE OPERATIONS ====================
        Route::prefix('inventaris')->name('inventaris.')->group(function () {
            // Create, Update, Delete
            Route::post('/', [InventarisController::class, 'store'])->name('store');
            Route::put('/{inventaris}', [InventarisController::class, 'update'])->name('update');
            Route::delete('/{inventaris}', [InventarisController::class, 'destroy'])->name('destroy');

            // Generate kode
            Route::post('/generate-kode', [InventarisController::class, 'generateKode'])->name('generate-kode');
            Route::post('/generate-kode-ajax', [InventarisController::class, 'generateKodeAjax'])->name('generate-kode.ajax');

            // Arsip operations
            Route::post('/arsip/{inventaris}/restore', [InventarisController::class, 'restore'])->name('restore')->withTrashed();
            Route::delete('/arsip/{inventaris}/force-delete', [InventarisController::class, 'forceDelete'])->name('forceDelete')->withTrashed();
        });

        // ==================== DETAIL ASET ====================
        Route::post('/inventaris/{inventaris}/detail-aset', [DetailAsetController::class, 'store'])
            ->name('inventaris.detail-aset.store');
        Route::put('/inventaris/{inventaris}/detail-aset', [DetailAsetController::class, 'update'])
            ->name('inventaris.detail-aset.update');

        // ==================== KATEGORI - WRITE OPERATIONS ====================
        Route::put('/kategori/{kategori}', [KategoriInventarisController::class, 'update'])->name('kategori.update');

        // ==================== MUTASI - WRITE OPERATIONS ====================
        Route::post('/mutasi-inventaris', [MutasiInventarisController::class, 'store'])->name('mutasi-inventaris.store');

        // ==================== PENGHAPUSAN - WRITE OPERATIONS ====================
        Route::post('/penghapusan', [PenghapusanInventarisController::class, 'store'])->name('penghapusan.store');
        Route::delete('/penghapusan/{penghapusan}', [PenghapusanInventarisController::class, 'destroy'])->name('penghapusan.destroy');
    });
});