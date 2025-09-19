@extends('layouts.app')

@section('title', 'Dashboard - Sistem Inventaris Desa')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
            <a href="{{ route('laporan.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-download fa-sm text-white-50"></i> Generate Laporan
            </a>
        </div>

        <!-- Statistik Utama -->
        <div class="row mb-4">
            <!-- Total Inventaris -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Total Inventaris</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $totalInventaris }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Nilai Inventaris -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Total Nilai Inventaris</div>
                                <div class="h5 mb-0 font-weight-bold text-white">Rp
                                    {{ number_format($totalNilai, 0, ',', '.') }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-money-bill-wave fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Kategori -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Total Kategori</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $totalKategori }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tags fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mutasi Bulan Ini -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Mutasi Bulan Ini</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $totalMutasi }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exchange-alt fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafik dan Data Utama -->
        <div class="row">
            <!-- Grafik Inventaris Bulanan -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Inventaris Masuk (6 Bulan Terakhir)</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                aria-labelledby="dropdownMenuLink">
                                <div class="dropdown-header">Opsi:</div>
                                <a class="dropdown-item" href="#" id="refreshChart">
                                    <i class="fas fa-sync-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Refresh Data
                                </a>
                                <a class="dropdown-item" href="#" id="exportChart">
                                    <i class="fas fa-download fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Export Gambar
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="inventarisChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kondisi Inventaris -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Kondisi Inventaris</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="kondisiChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            @foreach($kondisiStats as $kondisi => $total)
                                <span class="mr-2">
                                    <i class="fas fa-circle kondisi-{{ strtolower($kondisi) }}"></i>
                                    {{ $kondisi }} ({{ $total }})
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel dan Data Pendukung -->
        <div class="row">
            <!-- Inventaris Terbaru -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Inventaris Terbaru</h6>
                        <a href="{{ route('inventaris.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="dataTableInventaris" width="100%"
                                cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Tanggal Masuk</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inventarisTerbaru as $item)
                                        <tr>
                                            <td>
                                                <a href="{{ route('inventaris.show', $item->id) }}" class="text-primary">
                                                    {{ $item->kode_inventaris }}
                                                </a>
                                            </td>
                                            <td>{{ $item->nama_barang }}</td>
                                            <td>{{ $item->kategori->nama_kategori }}</td>
                                            <td>{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data inventaris terbaru</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mutasi Terbaru -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Mutasi Terbaru</h6>
                        <a href="{{ route('mutasi-inventaris.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="dataTableMutasi" width="100%"
                                cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Inventaris</th>
                                        <th>Lokasi Baru</th>
                                        <th>Petugas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mutasiTerbaru as $mutasi)
                                        <tr>
                                            <td>{{ $mutasi->tanggal_mutasi->format('d/m/Y') }}</td>
                                            <td>
                                                <a href="{{ route('inventaris.show', $mutasi->inventaris_id) }}"
                                                    class="text-primary">
                                                    {{ $mutasi->inventaris->nama_barang }}
                                                </a>
                                            </td>
                                            <td>{{ $mutasi->lokasi_baru }}</td>
                                            <td>{{ $mutasi->user->nama }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data mutasi terbaru</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Baris Ketiga -->
        <div class="row">
            <!-- Inventaris Perlu Perhatian -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4 border-left-danger">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-danger">
                        <h6 class="m-0 font-weight-bold text-white">
                            <i class="fas fa-exclamation-triangle me-1"></i> Inventaris Perlu Perhatian
                        </h6>
                        <span class="badge bg-white text-danger">{{ count($inventarisPerluPerhatian) }}</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="dataTablePerhatian" width="100%"
                                cellspacing="0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Kondisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($inventarisPerluPerhatian as $item)
                                        <tr class="row-{{ strtolower($item->kondisi) }}">
                                            <td>
                                                <a href="{{ route('inventaris.show', $item->id) }}" class="text-primary">
                                                    {{ $item->kode_inventaris }}
                                                </a>
                                            </td>
                                            <td>{{ $item->nama_barang }}</td>
                                            <td>{{ $item->kategori->nama_kategori }}</td>
                                            <td>
                                                <span class="badge bg-{{ strtolower($item->kondisi) }}">
                                                    {{ $item->kondisi }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada inventaris yang perlu perhatian</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Log Aktivitas -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-history me-2"></i>Aktivitas Terakhir
                        </h6>
                        <a href="{{ route('log-aktivitas.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="activity-feed">
                            @forelse($logAktivitas as $log)
                                <div class="activity-item position-relative pb-3">
                                    <div class="activity-badge">
                                        <i class="fas fa-{{ $log->getActionIcon() }} text-{{ $log->getActionColor() }}"></i>
                                    </div>
                                    <div class="activity-content ps-4">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0 fw-bold">{{ $log->user->nama }}</h6>
                                            <small class="text-muted">
                                                <!-- Waktu sudah otomatis dalam timezone Jakarta -->
                                                {{ $log->waktu->format('d M Y, H:i') }} WIB
                                                <span class="d-none d-sm-inline">({{ $log->waktu->diffForHumans() }})</span>
                                            </small>
                                        </div>
                                        <p class="mb-0 text-muted">{{ $log->deskripsi }}</p>
                                        <span class="badge badge-{{ $log->getActionColor() }} badge-sm">
                                            {{ ucfirst($log->tipe_aksi) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 text-muted">
                                    <i class="fas fa-info-circle fa-2x mb-2"></i>
                                    <p>Tidak ada aktivitas terbaru</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom Dashboard Script -->
    <script>
        // Data untuk chart
        window.inventarisBulanan = JSON.parse('@json($inventarisBulanan)');
        window.kondisiStats = JSON.parse('@json($kondisiStats)');
    </script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection