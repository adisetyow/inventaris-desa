@extends('layouts.app')

@section('title', 'Detail Inventaris - Sistem Inventaris Desa')

@section('styles')
    {{-- Load CSS index untuk variabel --}}
    <link rel="stylesheet" href="{{ asset('css/inventaris-index.css') }}">
    {{-- CSS SPESIFIK UNTUK HALAMAN DETAIL --}}
    <link rel="stylesheet" href="{{ asset('css/inventaris-detail.css') }}">
    {{-- Google Font Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid page-container">
        {{-- Header Halaman yang Konsisten --}}
        <div class="page-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="page-header-content">
                    <h6 class="page-title mb-0">
                        <i class="fas fa-eye me-2"></i>Detail Inventaris
                    </h6>
                    <div class="page-subtitle">
                        <span class="kode-inventaris-link font-monospace">{{ $inventaris->kode_inventaris }}</span>
                        <span class="mx-1">•</span>
                        <span class="subtitle-nama-barang">{{ $inventaris->nama_barang }}</span>
                    </div>
                </div>
                <div class="page-header-actions">
                    @role('Administrator')
                    <a href="{{ route('inventaris.edit', $inventaris->id) }}" class="btn btn-primary btn-pill">
                        <i class="fas fa-edit me-1"></i>Edit
                    </a>
                    <button type="button" class="btn btn-warning btn-pill" data-bs-toggle="modal"
                        data-bs-target="#archiveModal" data-id="{{ $inventaris->id }}"
                        data-nama="{{ $inventaris->nama_barang }}">
                        <i class="fas fa-archive me-1"></i>Arsipkan
                    </button>
                    @endrole
                    <a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary btn-pill">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>


        <div class="row g-4">
            {{-- Informasi Utama --}}
            <div class="col-lg-8">
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>Informasi Inventaris
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="detail-table">
                            <div class="detail-row">
                                <div class="detail-label">Kode Inventaris</div>
                                <div class="detail-value font-monospace">{{ $inventaris->kode_inventaris }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Nama Barang</div>
                                <div class="detail-value">{{ $inventaris->nama_barang }}</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Kategori</div>
                                <div class="detail-value">
                                    <span class="category-badge">
                                        <i class="fas {{ $inventaris->kategori->icon ?? 'fa-cube' }} me-1"></i>
                                        {{ $inventaris->kategori->nama_kategori }}
                                    </span>
                                </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Jumlah</div>
                                <div class="detail-value">
                                    <span class="quantity-badge">{{ $inventaris->jumlah }}</span>
                                </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Kondisi</div>
                                <div class="detail-value">
                                    @php
                                        $kondisiClass = strtolower(str_replace(' ', '-', $inventaris->kondisi));
                                    @endphp
                                    <span class="status-badge status-{{ $kondisiClass }}">{{ $inventaris->kondisi }}</span>
                                </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Lokasi Penempatan</div>
                                <div class="detail-value">
                                    <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                                    {{ $inventaris->lokasi_penempatan }}
                                </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Tanggal Masuk</div>
                                <div class="detail-value">
                                    <i class="fas fa-calendar me-1 text-muted"></i>
                                    {{ $inventaris->tanggal_masuk->format('d/m/Y') }}
                                </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Sumber Dana</div>
                                <div class="detail-value">
                                    <span class="source-badge">{{ $inventaris->sumber_dana }}</span>
                                </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Harga Perolehan (Satuan)</div>
                                <div class="detail-value font-monospace">
                                    Rp {{ number_format($inventaris->harga_perolehan, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Total Nilai Aset</div>
                                <div class="detail-value">
                                    <span class="total-value font-monospace">
                                        Rp {{ number_format($inventaris->total_nilai, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Masa Pakai</div>
                                <div class="detail-value">
                                    {{ $inventaris->masa_pakai_tahun ? $inventaris->masa_pakai_tahun . ' Tahun' : '-' }}
                                </div>
                            </div>
                            @if($inventaris->deskripsi)
                                <div class="detail-row">
                                    <div class="detail-label">Deskripsi</div>
                                    <div class="detail-value">{{ $inventaris->deskripsi }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Detail Aset Khusus --}}
                @if($detailAset)
                    <div class="modern-card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-list-alt me-2"></i>Detail Aset Khusus
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="detail-table">
                                @foreach($detailAset->toArray() as $key => $value)
                                    @if(!in_array($key, ['id', 'inventaris_id', 'created_at', 'updated_at']) && $value)
                                        <div class="detail-row">
                                            <div class="detail-label">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                                            <div class="detail-value">{{ $value }}</div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                {{-- Foto Inventaris --}}
                @if($inventaris->foto_path)
                    <div class="modern-card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-image me-2"></i>Foto Inventaris
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="inventaris-photo">
                                <img src="{{ asset('storage/' . $inventaris->foto_path) }}"
                                    alt="Foto {{ $inventaris->nama_barang }}" class="img-fluid">
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Quick Stats --}}
                <div class="modern-card mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-chart-bar me-2"></i>Ringkasan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Usia Aset</div>
                                    @php
                                        $diff = $inventaris->tanggal_masuk->diff(now());

                                        $usia = [];
                                        if ($diff->y > 0) {
                                            $usia[] = $diff->y . ' Tahun';
                                        }
                                        if ($diff->m > 0) {
                                            $usia[] = $diff->m . ' Bulan';
                                        }
                                        if ($diff->d > 0) {
                                            $usia[] = $diff->d . ' Hari';
                                        }
                                    @endphp

                                    <div class="stat-value">
                                        {{ implode(' ', $usia) ?: 'Baru' }}
                                    </div>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Total Mutasi</div>
                                    <div class="stat-value">{{ $inventaris->mutasiInventaris->count() }}</div>
                                </div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-icon">
                                    <i class="fas fa-tools"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Status Kondisi</div>
                                    <div class="stat-value">{{ $inventaris->kondisi }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Riwayat Mutasi --}}
        @if($inventaris->mutasiInventaris->count() > 0)
            <div class="modern-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>Riwayat Mutasi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
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
                                        <td>
                                            <span class="date-badge">
                                                {{ $mutasi->tanggal_mutasi->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td>
                                            <i class="fas fa-map-marker-alt me-1 text-muted"></i>
                                            {{ $mutasi->lokasi_awal }}
                                        </td>
                                        <td>
                                            <i class="fas fa-map-marker-alt me-1 text-success"></i>
                                            {{ $mutasi->lokasi_baru }}
                                        </td>
                                        <td>{{ $mutasi->keterangan ?? '-' }}</td>
                                        <td>
                                            <div class="user-info">
                                                <i class="fas fa-user me-1 text-muted"></i>
                                                {{ $mutasi->user->nama }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Riwayat Penghapusan --}}
        @if($inventaris->penghapusanInventaris->count() > 0)
            <div class="modern-card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-trash me-2"></i>Riwayat Penghapusan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
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
                                        <td>
                                            <span class="date-badge">
                                                {{ $penghapusan->tanggal_penghapusan->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td>{{ $penghapusan->alasan_penghapusan }}</td>
                                        <td class="font-monospace">{{ $penghapusan->nomor_berita_acara }}</td>
                                        <td>
                                            <div class="user-info">
                                                <i class="fas fa-user me-1 text-muted"></i>
                                                {{ $penghapusan->user->nama }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Arsip dengan desain modern --}}
    <div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modern-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="archiveModalLabel">
                        <i class="fas fa-archive me-2"></i>Konfirmasi Arsip
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="warning-box mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Apakah Anda yakin ingin mengarsipkan inventaris <strong id="archiveItemName"></strong>?
                    </div>
                    <p class="text-muted small mb-0">
                        Item ini akan dipindahkan ke halaman Arsip dan dapat dikembalikan atau dihapus permanen nanti.
                    </p>
                </div>
                <div class="modal-footer">
                    <form id="archiveForm" action="" method="POST" class="d-flex gap-2">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-secondary btn-pill" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-warning btn-pill">
                            <i class="fas fa-archive me-1"></i>Ya, Arsipkan
                        </button>
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