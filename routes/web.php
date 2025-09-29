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

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('login.form');
    Route::post('/', [AuthController::class, 'login'])->name('login');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ==================== INVENTARIS ROUTES ====================
    Route::prefix('inventaris')->name('inventaris.')->group(function () {
        // CRUD Routes
        Route::get('/', [InventarisController::class, 'index'])->name('index');
        Route::get('/create', [InventarisController::class, 'create'])->name('create');
        Route::post('/', [InventarisController::class, 'store'])->name('store');
        Route::get('/{inventaris}', [InventarisController::class, 'show'])->name('show');
        Route::get('/{inventaris}/edit', [InventarisController::class, 'edit'])->name('edit');
        Route::put('/{inventaris}', [InventarisController::class, 'update'])->name('update');
        Route::delete('/{inventaris}', [InventarisController::class, 'destroy'])->name('destroy');

        // Additional Routes
        Route::post('/generate-kode', [InventarisController::class, 'generateKode'])->name('generate-kode');

        // Trash/Archive Management
        Route::get('/arsip/data', [InventarisController::class, 'trashed'])->name('trashed');
        Route::post('/arsip/{inventaris}/restore', [InventarisController::class, 'restore'])->name('restore')->withTrashed();
        Route::delete('/arsip/{inventaris}/force-delete', [InventarisController::class, 'forceDelete'])->name('forceDelete')->withTrashed();

        // Export Routes - TAMBAHKAN INI
        Route::get('/export/csv', [InventarisController::class, 'exportCsv'])->name('export.csv');
        Route::get('/export/pdf', [InventarisController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/export/excel', [InventarisController::class, 'exportExcel'])->name('export.excel');

        // Report
        Route::get('/report', [InventarisController::class, 'report'])->name('report');
    });

    // Detail aset routes
    Route::post('/inventaris/{inventaris}/detail-aset', [DetailAsetController::class, 'store'])
        ->name('inventaris.detail-aset.store');
    Route::put('/inventaris/{inventaris}/detail-aset', [DetailAsetController::class, 'update'])
        ->name('inventaris.detail-aset.update');

    // Kategori inventaris routes
    Route::resource('kategori', KategoriInventarisController::class)->only([
        'index',
        'edit',
        'update'
    ]);
    Route::get('/inventaris/get-kategori-detail/{kategori}', [InventarisController::class, 'getKategoriDetail'])->name('inventaris.getKategoriDetail');

    // Mutasi inventaris routes
    Route::resource('mutasi-inventaris', MutasiInventarisController::class)->except(['edit', 'update', 'destroy']);

    // Penghapusan inventaris routes
    Route::resource('penghapusan-inventaris', PenghapusanInventarisController::class)->except(['edit', 'update', 'destroy']);
    Route::resource('penghapusan', PenghapusanInventarisController::class)->only(['index', 'create', 'store', 'show']);
    Route::delete('/penghapusan/{penghapusan}', [PenghapusanInventarisController::class, 'destroy'])->name('penghapusan.destroy');
    // Log aktivitas routes
    Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');

    // Laporan routes
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [LaporanController::class, 'exportPdf'])->name('export.pdf');
    });

    // Export routes
    Route::prefix('export')->group(function () {
        Route::get('/mutasi', [MutasiInventarisController::class, 'exportMutasi'])->name('export.mutasi');
        Route::get('/penghapusan', [PenghapusanInventarisController::class, 'exportPenghapusan'])->name('export.penghapusan');
    });
});