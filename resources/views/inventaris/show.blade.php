@extends('layouts.app')

@section('title', 'Detail Inventaris - Sistem Inventaris Desa')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/inventaris-detail.css') }}">
@endsection

@section('content')
    <div id="inventaris-detail-page" class="container-fluid">
        {{-- Header halaman --}}
        <div class="page-header">
            <h1 class="h3 text-gray-800">Detail Inventaris</h1>
            <div>
                <a href="{{ route('inventaris.edit', $inventaris->id) }}" class="btn btn-primary shadow-sm">
                    <i class="fas fa-edit fa-sm text-white-50"></i> Edit
                </a>
                <button type="button" class="btn btn-warning shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#archiveModal" data-id="{{ $inventaris->id }}"
                    data-nama="{{ $inventaris->nama_barang }}">
                    <i class="fas fa-archive fa-sm text-white-50"></i> Arsipkan
                </button>
                <a href="{{ route('inventaris.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
                </a>
            </div>
        </div>

        {{-- Kartu detail --}}
        <div class="card shadow mb-4 detail-card">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold">Informasi Inventaris</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Informasi utama --}}
                    <div class="col-md-8">
                        <table class="table table-detail">
                            <tr>
                                <th width="30%">Kode Inventaris</th>
                                <td>{{ $inventaris->kode_inventaris }}</td>
                            </tr>
                            <tr>
                                <th>Nama Barang</th>
                                <td>{{ $inventaris->nama_barang }}</td>
                            </tr>
                            <tr>
                                <th>Kategori</th>
                                <td>{{ $inventaris->kategori->nama_kategori }}</td>
                            </tr>
                            <tr>
                                <th>Jumlah</th>
                                <td>{{ $inventaris->jumlah }}</td>
                            </tr>
                            <tr>
                                <th>Kondisi</th>
                                <td>
                                    @php
                                        $kondisiClass = str_replace(' ', '-', strtolower($inventaris->kondisi));
                                    @endphp
                                    <span class="condition-badge badge-{{ $kondisiClass }}">
                                        {{ $inventaris->kondisi }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Lokasi Penempatan</th>
                                <td>{{ $inventaris->lokasi_penempatan }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Masuk</th>
                                <td>{{ $inventaris->tanggal_masuk->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <th>Sumber Dana</th>
                                <td>{{ $inventaris->sumber_dana }}</td>
                            </tr>
                            <tr>
                                <th>Harga Perolehan</th>
                                <td>Rp {{ number_format($inventaris->harga_perolehan, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Masa Pakai</th>
                                <td>{{ $inventaris->masa_pakai_tahun ? $inventaris->masa_pakai_tahun . ' Tahun' : '-' }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    {{-- Foto & detail aset --}}
                    <div class="col-md-4">
                        @if($inventaris->foto_path)
                            <div class="inventaris-photo mb-4">
                                <img src="{{ asset('storage/' . $inventaris->foto_path) }}"
                                    alt="Foto {{ $inventaris->nama_barang }}" class="shadow-sm">
                            </div>
                        @endif

                        @if($detailAset)
                            <div class="card detail-aset-card">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="m-0 font-weight-bold">Detail Aset Khusus</h6>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-sm mb-0">
                                        @foreach($detailAset->toArray() as $key => $value)
                                            @if(!in_array($key, ['inventaris_id', 'created_at', 'updated_at']))
                                                <tr>
                                                    <th>{{ ucwords(str_replace('_', ' ', $key)) }}</th>
                                                    <td>{{ $value ?? '-' }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Riwayat Mutasi --}}
                <div class="card mt-4 history-card">
                    <div class="card-header bg-info text-white">
                        <h6 class="m-0 font-weight-bold">Riwayat Mutasi</h6>
                    </div>
                    <div class="card-body">
                        @if($inventaris->mutasiInventaris->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Lokasi Awal</th>
                                            <th>Lokasi Baru</th>
                                            <th>Keterangan</th>
                                            <th>Petugas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inventaris->mutasiInventaris as $mutasi)
                                            <tr>
                                                <td>{{ $mutasi->tanggal_mutasi->format('d/m/Y') }}</td>
                                                <td>{{ $mutasi->lokasi_awal }}</td>
                                                <td>{{ $mutasi->lokasi_baru }}</td>
                                                <td>{{ $mutasi->keterangan ?? '-' }}</td>
                                                <td>{{ $mutasi->user->nama }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center">Tidak ada riwayat mutasi</p>
                        @endif
                    </div>
                </div>

                {{-- Riwayat Penghapusan --}}
                @if($inventaris->penghapusanInventaris->count() > 0)
                    <div class="card mt-4 history-card">
                        <div class="card-header bg-danger text-white">
                            <h6 class="m-0 font-weight-bold">Riwayat Penghapusan</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Alasan</th>
                                            <th>No. Berita Acara</th>
                                            <th>Petugas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inventaris->penghapusanInventaris as $penghapusan)
                                            <tr>
                                                <td>{{ $penghapusan->tanggal_penghapusan->format('d/m/Y') }}</td>
                                                <td>{{ $penghapusan->alasan_penghapusan }}</td>
                                                <td>{{ $penghapusan->nomor_berita_acara }}</td>
                                                <td>{{ $penghapusan->user->nama }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Arsip tetap sama --}}
    <div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="archiveModalLabel">
                        <i class="fas fa-archive me-2"></i>Konfirmasi Arsip
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengarsipkan inventaris <strong id="archiveItemName"></strong>?</p>
                    <p class="text-muted small">Item ini akan dipindahkan ke halaman Arsip dan dapat dikembalikan atau
                        dihapus permanen nanti.</p>
                </div>
                <div class="modal-footer">
                    <form id="archiveForm" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">Ya, Arsipkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const archiveModal = document.getElementById('archiveModal');
            if (archiveModal) {
                archiveModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const inventarisId = button.getAttribute('data-id');
                    const namaBarang = button.getAttribute('data-nama');

                    const form = archiveModal.querySelector('#archiveForm');
                    const nameSpan = archiveModal.querySelector('#archiveItemName');

                    form.action = `/inventaris/${inventarisId}`;
                    nameSpan.textContent = namaBarang;
                });
            }
        });
    </script>
@endsection