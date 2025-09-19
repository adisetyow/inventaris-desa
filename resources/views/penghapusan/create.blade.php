@extends('layouts.app')

@section('title', 'Proses Penghapusan Inventaris - Sistem Inventaris Desa')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-trash-alt me-2"></i>Proses Penghapusan Inventaris</h1>
            <a href="{{ route('inventaris.trashed') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Arsip
            </a>
        </div>

        @include('layouts.partials.alerts')

        <form method="POST" action="{{ route('penghapusan.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Input tersembunyi untuk membawa ID inventaris yang akan dihapus --}}
            <input type="hidden" name="inventaris_id" value="{{ $inventaris->id }}">

            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">Langkah 1: Konfirmasi Aset</h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-light border">
                        <p class="mb-1"><strong>Nama Barang:</strong> {{ $inventaris->nama_barang }}</p>
                        <p class="mb-0"><strong>Kode Inventaris:</strong> {{ $inventaris->kode_inventaris }}</p>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Langkah 2: Isi Berita Acara Penghapusan</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="tanggal_penghapusan" class="form-label">Tanggal Penghapusan <span
                                class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_penghapusan') is-invalid @enderror"
                            id="tanggal_penghapusan" name="tanggal_penghapusan"
                            value="{{ old('tanggal_penghapusan', date('Y-m-d')) }}" required>
                        @error('tanggal_penghapusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nomor_berita_acara" class="form-label">Nomor Berita Acara <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nomor_berita_acara') is-invalid @enderror"
                            id="nomor_berita_acara" name="nomor_berita_acara" value="{{ old('nomor_berita_acara') }}"
                            placeholder="Contoh: BA/PENGHAPUSAN/001" required>
                        @error('nomor_berita_acara')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="alasan_penghapusan" class="form-label">Alasan Penghapusan <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control @error('alasan_penghapusan') is-invalid @enderror"
                            id="alasan_penghapusan" name="alasan_penghapusan" rows="4"
                            placeholder="Jelaskan alasan aset ini dihapus (contoh: Rusak berat, hilang, dll.)"
                            required>{{ old('alasan_penghapusan') }}</textarea>
                        @error('alasan_penghapusan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="file_berita_acara" class="form-label">Upload File Berita Acara (Wajib PDF) <span
                                class="text-danger">*</span></label>
                        <input type="file" class="form-control @error('file_berita_acara') is-invalid @enderror"
                            id="file_berita_acara" name="file_berita_acara" accept=".pdf" required>
                        @error('file_berita_acara')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="{{ route('inventaris.trashed') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-check-circle me-2"></i>Konfirmasi & Hapus Permanen Aset
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection