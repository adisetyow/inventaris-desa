@extends('layouts.app')

@section('title', 'Riwayat Penghapusan - Sistem Inventaris Desa')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/inventaris-index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/inventaris-create.css') }}">
@endsection

@section('content')
    <div class="container-fluid page-container">
        {{-- Menggunakan modern-card untuk kartu utama --}}
        <div class="card modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="fas fa-history me-1"></i>Daftar Riwayat Penghapusan
                </h6>
                <div class="card-header-actions d-flex align-items-center gap-4">
                    <form method="GET" action="{{ route('penghapusan.index') }}" class="search-form" id="searchForm">
                        <div class="input-group-icon position-relative">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" id="searchInput" class="form-control"
                                value="{{ request('search') }}" placeholder="Cari...">

                            {{-- Tombol X untuk reset --}}
                            @if(request('search'))
                                <button type="button" id="clearSearch"
                                    class="btn btn-clear position-absolute top-50 end-0 translate-middle-y me-2">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th class="text-center column-no">No</th>
                            <th>Nama Barang & Kode</th>
                            <th>No. Berita Acara</th>
                            <th class="text-center">Tgl. Hapus</th>
                            <th>Alasan</th>
                            <th>Dihapus Oleh</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($penghapusan as $item)
                            <tr>
                                <td class="text-center column-no">
                                    {{ $loop->iteration + ($penghapusan->currentPage() - 1) * $penghapusan->perPage() }}
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="link-item">{{ $item->nama_barang }}</span>
                                        <small class="font-monospace text-slate-400">{{ $item->kode_inventaris }}</small>
                                    </div>
                                </td>
                                <td>
                                    {{-- Tombol untuk membuka PDF di modal --}}
                                    <button type="button" class="btn btn-link p-0 text-start kode-inventaris-link"
                                        data-bs-toggle="modal" data-bs-target="#pdfViewerModal"
                                        data-pdf-url="{{ Storage::url($item->file_berita_acara) }}"
                                        data-nomor-ba="{{ $item->nomor_berita_acara }}">
                                        <i class="fas fa-file-pdf me-1"></i>
                                        {{ $item->nomor_berita_acara }}
                                    </button>
                                </td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_penghapusan)->format('d/m/Y') }}
                                </td>
                                <td>{{ Str::limit($item->alasan_penghapusan, 50) }}</td>
                                <td>{{ $item->user->nama ?? 'N/A' }}</td>
                                <td class="text-center">
                                    {{-- Tombol untuk menghapus riwayat secara permanen --}}
                                    <button type="button" class="btn btn-icon action-edit" data-bs-toggle="modal"
                                        data-bs-target="#deletePermanenModal" data-id="{{ $item->id }}"
                                        data-nama="{{ $item->nama_barang }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="text-center py-5">
                                        <i class="fas fa-box-open fa-3x text-slate-300 mb-3"></i>
                                        <h5 class="text-slate-600">Riwayat Kosong</h5>
                                        <p class="text-slate-400">Belum ada data inventaris yang dihapus permanen.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($penghapusan->hasPages())
                <div class="card-footer pagination-container">
                    <div class="text-slate-500 small">
                        Menampilkan **{{ $penghapusan->firstItem() }}** - **{{ $penghapusan->lastItem() }}** dari
                        **{{ $penghapusan->total() }}** riwayat
                    </div>
                    <div>{{ $penghapusan->links() }}</div>
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL UNTUK MENAMPILKAN PDF --}}
    <div class="modal fade" id="pdfViewerModal" tabindex="-1" aria-labelledby="pdfViewerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pdfViewerModalLabel">
                        <i class="fas fa-file-pdf me-2"></i>Berita Acara - <span id="nomorBaText"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="height: 80vh;">
                    <iframe id="pdfFrame" src="" width="100%" height="100%" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL UNTUK KONFIRMASI HAPUS PERMANEN --}}
    <div class="modal fade" id="deletePermanenModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                        Permanen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus permanen riwayat untuk barang <strong id="deleteItemName"
                            class="text-danger"></strong>? Tindakan ini **tidak dapat** dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <form id="deletePermanenForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-secondary btn-pill"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger btn-pill">Ya, Hapus Permanen</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Logika untuk Modal PDF Viewer
            const pdfViewerModal = document.getElementById('pdfViewerModal');
            if (pdfViewerModal) {
                pdfViewerModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const pdfUrl = button.getAttribute('data-pdf-url');
                    const nomorBa = button.getAttribute('data-nomor-ba');

                    const iframe = pdfViewerModal.querySelector('#pdfFrame');
                    const nomorBaText = pdfViewerModal.querySelector('#nomorBaText');

                    nomorBaText.textContent = nomorBa;
                    iframe.setAttribute('src', pdfUrl);
                });
                // Kosongkan src iframe saat modal ditutup agar tidak membebani browser
                pdfViewerModal.addEventListener('hidden.bs.modal', function () {
                    pdfViewerModal.querySelector('#pdfFrame').setAttribute('src', '');
                });
            }

            // Logika untuk Modal Konfirmasi Hapus Permanen
            const deletePermanenModal = document.getElementById('deletePermanenModal');
            if (deletePermanenModal) {
                deletePermanenModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const nama = button.getAttribute('data-nama');

                    const deleteUrl = "{{ route('penghapusan.destroy', ['penghapusan' => ':id']) }}".replace(':id', id);

                    deletePermanenModal.querySelector('#deleteItemName').textContent = nama;
                    deletePermanenModal.querySelector('#deletePermanenForm').action = deleteUrl;
                });
            }
        });
    </script>
@endsection