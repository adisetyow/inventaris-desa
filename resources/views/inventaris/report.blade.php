@extends('layouts.app')

@section('title', 'Laporan Inventaris - Sistem Inventaris Desa')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Inventaris</h1>
        <div>
            <a href="{{ route('export.inventaris') }}" class="btn btn-success shadow-sm">
                <i class="fas fa-file-csv fa-sm text-white-50"></i> Export CSV
            </a>
            <a href="{{ route('inventaris.exportPdf') }}" class="btn btn-danger shadow-sm">
                <i class="fas fa-file-pdf fa-sm text-white-50"></i> Export PDF
            </a>
            <a href="{{ route('inventaris.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Laporan</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('inventaris.report') }}" class="form-row">
                <div class="form-group col-md-3">
                    <label for="kategori_id">Kategori</label>
                    <select name="kategori_id" id="kategori_id" class="form-control">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label for="kondisi">Kondisi</label>
                    <select name="kondisi" id="kondisi" class="form-control">
                        <option value="">Semua Kondisi</option>
                        <option value="Baik" {{ request('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak" {{ request('kondisi') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="Perlu Perbaikan" {{ request('kondisi') == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                        <option value="Hilang" {{ request('kondisi') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <label for="tanggal_dari">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" id="tanggal_dari" class="form-control" 
                           value="{{ request('tanggal_dari') }}">
                </div>
                <div class="form-group col-md-3">
                    <label for="tanggal_sampai">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" id="tanggal_sampai" class="form-control" 
                           value="{{ request('tanggal_sampai') }}">
                </div>
                <div class="form-group col-md-1 align-self-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Inventaris</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalInventaris }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Nilai Inventaris</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp {{ number_format($totalNilai, 0, ',', '.') }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Kondisi Baik</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kondisiStats['Baik'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Perlu Perhatian</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ ($kondisiStats['Rusak'] ?? 0) + ($kondisiStats['Perlu Perbaikan'] ?? 0) + ($kondisiStats['Hilang'] ?? 0) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Laporan -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Inventaris</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Kondisi</th>
                            <th>Lokasi</th>
                            <th>Tanggal Masuk</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inventaris as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kode_inventaris }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->kategori->nama_kategori }}</td>
                                <td>{{ $item->jumlah }}</td>
                                <td>
                                    <span class="badge badge-{{ strtolower($item->kondisi) }}">
                                        {{ $item->kondisi }}
                                    </span>
                                </td>
                                <td>{{ $item->lokasi_penempatan }}</td>
                                <td>{{ $item->tanggal_masuk->format('d/m/Y') }}</td>
                                <td>Rp {{ number_format($item->harga_perolehan, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Kondisi Inventaris</h6>
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
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribusi Kategori Inventaris</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="kategoriChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small" id="kategori-legend">
                        {{-- Legend akan di-generate oleh JavaScript --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden data untuk JavaScript --}}
<div id="chart-data" style="display: none;">
    <script type="application/json" id="kondisi-data">{!! json_encode($kondisiStats) !!}</script>
    <script type="application/json" id="kategori-data">{!! json_encode($kategoriStats) !!}</script>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ambil data dari hidden script tags
    const kondisiData = JSON.parse(document.getElementById('kondisi-data').textContent);
    const kategoriData = JSON.parse(document.getElementById('kategori-data').textContent);
    
    // Warna untuk charts
    const chartColors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6610f2', '#6f42c1', '#fd7e14'];

    // Chart Kondisi
    const kondisiCtx = document.getElementById('kondisiChart');
    if (kondisiCtx) {
        const kondisiChart = new Chart(kondisiCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(kondisiData),
                datasets: [{
                    data: Object.values(kondisiData),
                    backgroundColor: [
                        '#1cc88a', // Baik
                        '#e74a3b', // Rusak
                        '#f6c23e', // Perlu Perbaikan
                        '#858796'  // Hilang
                    ],
                    hoverBackgroundColor: [
                        '#17a673',
                        '#be2617',
                        '#dda20a',
                        '#6b6d7d'
                    ],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%',
            },
        });
    }

    // Chart Kategori
    const kategoriCtx = document.getElementById('kategoriChart');
    if (kategoriCtx) {
        // Generate colors untuk kategori
        const kategoriColors = Object.keys(kategoriData).map((key, index) => {
            return chartColors[index % chartColors.length];
        });
        
        const kategoriChart = new Chart(kategoriCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(kategoriData),
                datasets: [{
                    data: Object.values(kategoriData),
                    backgroundColor: kategoriColors,
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                cutout: '70%',
            },
        });
    }
});
</script>
@endsection