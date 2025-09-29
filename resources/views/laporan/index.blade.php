@extends('layouts.app')

@section('title', 'Laporan Inventaris')

@push('styles')
    {{-- Jika ada style khusus untuk laporan --}}
    <link rel="stylesheet" href="{{ asset('css/laporan-style.css') }}">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Laporan Inventaris</h1>
        </div>

        {{-- CARD UNTUK FILTER --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.index') }}" method="GET">
                    <div class="row">
                        {{-- Filter Kondisi --}}
                        <div class="col-md-3 mb-3">
                            <label for="kondisi" class="form-label">Berdasarkan Kondisi</label>
                            <select name="kondisi" id="kondisi" class="form-select">
                                <option value="">Semua Kondisi</option>
                                @foreach($kondisiList as $kondisi)
                                    <option value="{{ $kondisi }}" {{ $request->kondisi == $kondisi ? 'selected' : '' }}>
                                        {{ $kondisi }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Lokasi --}}
                        <div class="col-md-3 mb-3">
                            <label for="lokasi" class="form-label">Berdasarkan Lokasi</label>
                            <select name="lokasi" id="lokasi" class="form-select">
                                <option value="">Semua Lokasi</option>
                                @foreach($lokasiList as $lokasi)
                                    <option value="{{ $lokasi }}" {{ $request->lokasi == $lokasi ? 'selected' : '' }}>
                                        {{ $lokasi }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Bulan --}}
                        <div class="col-md-2 mb-3">
                            <label for="bulan" class="form-label">Bulan Masuk</label>
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Semua</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $request->bulan == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- Filter Tahun --}}
                        <div class="col-md-2 mb-3">
                            <label for="tahun" class="form-label">Tahun Masuk</label>
                            <select name="tahun" id="tahun" class="form-select">
                                <option value="">Semua</option>
                                @for ($i = date('Y'); $i >= 2010; $i--)
                                    <option value="{{ $i }}" {{ $request->tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        {{-- Tombol Filter dan Reset --}}
                        <div class="col-md-2 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-primary me-2">Filter</button>
                            <a href="{{ route('laporan.index') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        {{-- CARD UNTUK HASIL LAPORAN --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Hasil Laporan</h6>
                <div>
                    {{-- Tombol Export --}}
                    <a href="{{ route('laporan.export.excel', $request->query()) }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('laporan.export.pdf', $request->query()) }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th class="text-center">Jumlah</th>
                                <th>Kondisi</th>
                                <th>Lokasi</th>
                                <th class="text-end">Total Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventaris as $item)
                                <tr>
                                    <td>{{ $loop->iteration + $inventaris->firstItem() - 1 }}</td>
                                    <td><a href="{{ route('inventaris.show', $item->id) }}">{{ $item->kode_inventaris }}</a>
                                    </td>
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ $item->kategori->nama_kategori ?? '-'}}</td>
                                    <td class="text-center">{{ $item->jumlah }}</td>
                                    <td>{{ $item->kondisi }}</td>
                                    <td>{{ $item->lokasi_penempatan }}</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Data tidak ditemukan. Silakan ubah filter Anda.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="7" class="text-end">Total Keseluruhan Nilai Aset:</th>
                                <th class="text-end">Rp {{ number_format($totalNilaiAset, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                {{-- Pagination --}}
                <div class="d-flex justify-content-center">
                    {{ $inventaris->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection