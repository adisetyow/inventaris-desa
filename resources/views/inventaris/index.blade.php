@extends('layouts.app')

@section('title', 'Daftar Inventaris - Sistem Inventaris Desa')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/inventaris-index.css') }}">
    {{-- Google Font Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid page-container">

        {{-- Kartu utama dengan class baru untuk styling modern --}}
        <div class="card modern-card">
            <div class="card-header">
                {{-- Baris Atas: Judul di kiri, Aksi di kanan --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-boxes me-2"></i>Data Inventaris Aktif
                    </h6>

                    {{-- Grup Aksi: Form Pencarian dan Tombol Tambah --}}
                    <div class="card-header-actions d-flex align-items-center gap-2">
                        <form method="GET" action="{{ route('inventaris.index') }}" class="search-form" id="searchForm">
                            <div class="input-group-icon position-relative">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="search" id="searchInput" class="form-control"
                                    value="{{ request('search') }}" placeholder="Cari otomatis...">

                                {{-- Tombol X untuk reset --}}
                                @if(request('search'))
                                    <button type="button" id="clearSearch"
                                        class="btn btn-clear position-absolute top-50 end-0 translate-middle-y me-2">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </form>

                        {{-- Tombol untuk membuka filter --}}
                        <button class="btn btn-outline-secondary btn-pill" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>

                        {{-- Tombol Tambah --}}
                        <a href="{{ route('inventaris.create') }}" class="btn btn-primary btn-pill">
                            <i class="fas fa-plus me-1"></i>Tambah
                        </a>
                    </div>
                </div>

                {{-- Area Filter yang bisa disembunyikan (Collapse) --}}
                <div class="collapse mt-3" id="filterCollapse">
                    <div class="filter-collapse-container">
                        <form method="GET" action="{{ route('inventaris.index') }}" class="d-flex align-items-end gap-3">
                            {{-- Input 'search' tersembunyi agar nilainya tetap terbawa saat filter dari sini --}}
                            <input type="hidden" name="search" value="{{ request('search') }}">

                            <div class="flex-grow-1">
                                <label for="kondisi" class="form-label">Kondisi</label>
                                <select name="kondisi" id="kondisi" class="form-select">
                                    <option value="">Semua Kondisi</option>
                                    @foreach(['Baik', 'Rusak', 'Perlu Perbaikan', 'Hilang'] as $kondisiOption)
                                        <option value="{{ $kondisiOption }}" {{ request('kondisi') == $kondisiOption ? 'selected' : '' }}>
                                            {{ $kondisiOption }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tombol Aksi di dalam collapse --}}
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" type="submit">Terapkan</button>
                                @if(request()->hasAny(['search', 'kondisi']))
                                    <a href="{{ route('inventaris.index') }}" class="btn btn-light" data-bs-toggle="tooltip"
                                        title="Reset Filter">
                                        <i class="fas fa-sync-alt"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th class="text-center column-no">No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th class="text-center">Jumlah</th>
                            <th>Kondisi</th>
                            <th class="text-end">Harga Satuan (Rp)</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventaris as $item)
                            <tr>
                                <td class="text-center column-no">
                                    {{ $loop->iteration + ($inventaris->perPage() * ($inventaris->currentPage() - 1)) }}
                                </td>
                                <td>
                                    <a href="{{ route('inventaris.show', $item->id) }}"
                                        class="kode-inventaris-link font-monospace">
                                        {{ $item->kode_inventaris }}
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('inventaris.show', $item->id) }}" class="link-item">
                                        {{ $item->nama_barang }}
                                    </a>
                                </td>
                                <td class="text-center">{{ $item->jumlah }}</td>
                                <td>
                                    {{-- Logika badge disederhanakan dengan class --}}
                                    @php
                                        $kondisiClass = strtolower(str_replace(' ', '-', $item->kondisi));
                                    @endphp
                                    <span class="status-badge status-{{ $kondisiClass }}">{{ $item->kondisi }}</span>
                                </td>
                                <td class="text-end font-monospace">
                                    {{ number_format($item->harga_perolehan, 0, ',', '.') }}
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('inventaris.show', $item->id) }}" class="btn btn-icon action-view"
                                            data-bs-toggle="tooltip" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="{{ route('inventaris.edit', $item->id) }}" class="btn btn-icon action-edit"
                                            data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    {{-- Tampilan empty state yang lebih baik --}}
                                    <div class="text-center py-5">
                                        <i class="fas fa-box-open fa-3x text-slate-300 mb-3"></i>
                                        <h5 class="text-slate-600">Data Inventaris Kosong</h5>
                                        <p class="text-slate-400">Tidak ada data yang cocok dengan kriteria pencarian Anda.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($inventaris->hasPages())
                <div class="card-footer pagination-container">
                    <div class="text-slate-500 small">
                        Menampilkan **{{ $inventaris->firstItem() }}** - **{{ $inventaris->lastItem() }}** dari
                        **{{ $inventaris->total() }}** item
                    </div>
                    <div>
                        {{ $inventaris->appends(request()->query())->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("searchInput");
            const searchForm = document.getElementById("searchForm");
            const clearBtn = document.getElementById("clearSearch");

            let debounceTimer;
            searchInput.addEventListener("input", function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    searchForm.submit(); // auto submit
                }, 500); // delay 0.5 detik setelah berhenti mengetik
            });

            if (clearBtn) {
                clearBtn.addEventListener("click", function () {
                    searchInput.value = "";
                    searchForm.submit();
                });
            }
        });
    </script>
    <script>
        // Initialize Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection