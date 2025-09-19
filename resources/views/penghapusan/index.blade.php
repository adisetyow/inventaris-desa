@extends('layouts.app')

@section('title', 'Riwayat Penghapusan - Sistem Inventaris Desa')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Riwayat Penghapusan Inventaris</h1>
            <div>
            </div>
        </div>

        @include('layouts.partials.alerts')


        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Laporan Semua Tindakan Penghapusan</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('penghapusan-inventaris.index') }}" class="mb-4">
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Hapus</th>
                                <th>Nama Barang</th>
                                <th>Kode Inventaris</th>
                                <th>No. Berita Acara</th>
                                <th>Alasan</th>
                                <th>Dihapus Oleh</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penghapusan as $item)
                                <tr>
                                    <td>{{ $loop->iteration + $penghapusan->firstItem() - 1 }}</td>
                                    <td>{{ $item->tanggal_penghapusan->format('d/m/Y') }}</td>
                                    <!-- <td>
                                                {{ $item->inventaris->nama_barang ?? 'Data Inventaris Tidak Ditemukan' }}

                                            </td> -->
                                    <td>{{ $item->nama_barang }}</td>
                                    <td>{{ $item->kode_inventaris }}</td>
                                    <!-- <td>{{ $item->inventaris->kode_inventaris ?? 'Data Inventaris Tidak Ditemukan' }}</td> -->
                                    <td>{{ $item->nomor_berita_acara }}</td>
                                    <td>{{ Str::limit($item->alasan_penghapusan, 50) }}</td>
                                    <td>{{ $item->user->nama ?? 'N/A' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada riwayat penghapusan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    {{ $penghapusan->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection