@extends('layouts.app')

@section('title', 'Log Aktivitas - Sistem Inventaris Desa')

@section('styles')

@endsection

@section('content')
    <div id="log-activity-page" class="container-fluid">
        <!-- Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="h3 mb-0">Log Aktivitas Sistem</h1>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="card filter-card mb-4">
            <div class="card-body">
                <form action="{{ route('log-aktivitas.index') }}" method="GET" id="logFilterForm">
                    <div class="row align-items-end">
                        <div class="col-md-3 mb-3">
                            <label for="tipe_aksi" class="form-label text-muted small mb-2">TIPE AKSI</label>
                            <select name="tipe_aksi" id="tipe_aksi" class="form-control">
                                <option value="">Semua Tipe</option>
                                <option value="tambah" {{ request('tipe_aksi') == 'tambah' ? 'selected' : '' }}>Tambah
                                </option>
                                <option value="ubah" {{ request('tipe_aksi') == 'ubah' ? 'selected' : '' }}>Ubah</option>
                                <option value="hapus" {{ request('tipe_aksi') == 'hapus' ? 'selected' : '' }}>Hapus</option>
                                <option value="restore" {{ request('tipe_aksi') == 'restore' ? 'selected' : '' }}>Restore
                                </option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tanggal_dari" class="form-label text-muted small mb-2">TANGGAL DARI</label>
                            <input type="date" name="tanggal_dari" id="tanggal_dari" class="form-control"
                                value="{{ request('tanggal_dari') }}" max="{{ date('Y-m-d') }}">
                            <div id="tanggalDariError" class="invalid-feedback d-none"></div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tanggal_sampai" class="form-label text-muted small mb-2">TANGGAL SAMPAI</label>
                            <input type="date" name="tanggal_sampai" id="tanggal_sampai" class="form-control"
                                value="{{ request('tanggal_sampai') }}" max="{{ date('Y-m-d') }}">
                            <div id="tanggalSampaiError" class="invalid-feedback d-none"></div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="d-grid gap-2 d-md-block">
                                <button type="submit" class="btn btn-primary me-md-2" id="filterButton">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('log-aktivitas.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-refresh me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Log -->
        <div class="card table-card">
            <div class="card-body p-0">
                @if($logs->count() > 0)
                    <div class="table-responsive">
                        <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th style="width: 200px;">Pengguna</th>
                                    <th style="width: 120px;">Aksi</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 180px;">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logs as $log)
                                    <tr>
                                        <td class="text-muted">
                                            {{ $loop->iteration + ($logs->currentPage() - 1) * $logs->perPage() }}
                                        </td>
                                        <td>
                                            <div class="user-info">
                                                @if($log->user)
                                                    <div class="user-avatar">
                                                        {{ substr($log->user->nama, 0, 1) }}
                                                    </div>
                                                    <span class="user-name">{{ $log->user->nama }}</span>
                                                @else
                                                    <div class="user-avatar">
                                                        ?
                                                    </div>
                                                    <span class="user-missing">
                                                        User ID: {{ $log->user_id ?? 'N/A' }} (Tidak Ditemukan)
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match ($log->tipe_aksi) {
                                                    'tambah' => 'badge-tambah',
                                                    'ubah' => 'badge-ubah',
                                                    'hapus' => 'badge-hapus',
                                                    'restore' => 'badge-restore',
                                                    default => 'badge-lainnya'
                                                };
                                            @endphp
                                            <span class="action-badge {{ $badgeClass }}">
                                                {{ ucfirst($log->tipe_aksi ?? 'Lainnya') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-dark">{{ $log->deskripsi }}</span>
                                        </td>
                                        <td>
                                            <span class="time-text">
                                                {{ $log->waktu ? $log->waktu->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <h5 class="mt-3 mb-2">Tidak Ada Log Aktivitas</h5>
                        <p class="text-muted">Belum ada aktivitas yang tercatat dalam sistem atau coba ubah filter pencarian.
                        </p>
                    </div>
                @endif
            </div>
            @if($logs->hasPages())
                <div class="card-footer bg-light border-0">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                        <div class="text-muted small">
                            Menampilkan {{ $logs->firstItem() }} - {{ $logs->lastItem() }} dari {{ $logs->total() }} hasil
                        </div>
                        <div>
                            {{ $logs->appends(request()->query())->onEachSide(1)->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Filter Validation Script --}}
    <script>
        document.getElementById('logFilterForm').addEventListener('submit', function (e) {
            const tanggalDari = document.getElementById('tanggal_dari').value;
            const tanggalSampai = document.getElementById('tanggal_sampai').value;
            let isValid = true;

            // Reset error messages
            document.getElementById('tanggalDariError').textContent = '';
            document.getElementById('tanggalSampaiError').textContent = '';
            document.getElementById('tanggalDariError').classList.add('d-none');
            document.getElementById('tanggalSampaiError').classList.add('d-none');

            // Validate dates
            if (tanggalDari && tanggalSampai) {
                if (new Date(tanggalDari) > new Date(tanggalSampai)) {
                    isValid = false;
                    const errorMessage = 'Tanggal Dari tidak boleh lebih besar dari Tanggal Sampai.';
                    document.getElementById('tanggalDariError').textContent = errorMessage;
                    document.getElementById('tanggalDariError').classList.remove('d-none');
                    document.getElementById('tanggalDariError').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
@endsection