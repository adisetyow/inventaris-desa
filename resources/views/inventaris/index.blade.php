@extends('layouts.app')

@section('title', 'Daftar Inventaris - Sistem Inventaris Desa')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/inventaris-index.css') }}">
    {{-- Google Font Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-boxes me-2"></i>Data Inventaris Aktif
                    </h6>
                    <div class="col-auto">
                        <div class="btn-group">
                            <a href="{{ route('inventaris.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Tambah
                            </a>
                        </div>
                    </div>
                </div>

                <div class="filter-controls">
                    <form method="GET" action="{{ route('inventaris.index') }}">
                        <div class="row g-2 align-items-end">
                            <div class="col-lg-3 col-md-6">
                                <label for="search" class="form-label">Nama Barang / Kode</label>
                                <input type="text" name="search" id="search" class="form-control"
                                    value="{{ request('search') }}" placeholder="Cari...">
                            </div>

                            <div class="col-lg-2 col-md-6">
                                <label for="kondisi" class="form-label">Kondisi</label>
                                <select name="kondisi" id="kondisi" class="form-select">
                                    <option value="">Semua</option>
                                    @foreach(['Baik', 'Rusak', 'Perlu Perbaikan', 'Hilang'] as $kondisiOption)
                                        <option value="{{ $kondisiOption }}" {{ request('kondisi') == $kondisiOption ? 'selected' : '' }}>
                                            {{ $kondisiOption }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-2 col-md-12 d-flex">
                                <button class="btn btn-primary w-100 me-2" type="submit">
                                    <i class="fas fa-search me-1"></i> Cari
                                </button>
                                @if(request()->hasAny(['search', 'kategori_id', 'kondisi', 'lokasi']))
                                    <a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary"
                                        data-bs-toggle="tooltip" title="Reset Filter">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th class="text-center">Jumlah</th>
                            <th>Kondisi</th>
                            <th class="text-end">Total Nilai inventaris</th>
                            <th class="text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventaris as $item)
                            <tr>
                                <td class="text-center">
                                    {{ $loop->iteration + ($inventaris->perPage() * ($inventaris->currentPage() - 1)) }}
                                </td>
                                <td>
                                    <a href="{{ route('inventaris.show', $item->id) }}" class="link-item">
                                        <span class="badge bg-light text-dark">{{ $item->kode_inventaris }}</span>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('inventaris.show', $item->id) }}"
                                        class="link-item">{{ $item->nama_barang }}</a>
                                    @if($item->status_penghapusan === 'Dihapus')
                                        <span class="badge bg-danger ms-2">Dihapus</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $item->jumlah }}</td>
                                <td>
                                    @php
                                        $badgeClass = [
                                            'Baik' => 'bg-success',
                                            'Rusak' => 'bg-danger',
                                            'Perlu Perbaikan' => 'bg-warning text-dark',
                                            'Hilang' => 'bg-secondary'
                                        ][$item->kondisi] ?? 'bg-primary';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $item->kondisi }}</span>
                                </td>
                                <td class="text-end col-harga">{{ number_format($item->harga_perolehan, 0, ',', '.') }}</td>
                                <td class="col-aksi text-center">
                                    <a href="{{ route('inventaris.show', $item->id) }}" class="btn btn-sm btn-outline-secondary"
                                        data-bs-toggle="tooltip" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventaris.edit', $item->id) }}" class="btn btn-sm btn-outline-primary"
                                        data-bs-toggle="tooltip" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <h5 class="mb-1">Data Tidak Ditemukan</h5>
                                    <p class="text-muted">Tidak ada data inventaris yang cocok dengan filter Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($inventaris->hasPages())
                <div class="pagination-container d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Menampilkan {{ $inventaris->firstItem() }} - {{ $inventaris->lastItem() }} dari
                        {{ $inventaris->total() }} item
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
        // Initialize Bootstrap tooltips
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection