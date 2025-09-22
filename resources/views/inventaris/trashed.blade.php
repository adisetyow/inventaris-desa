@extends('layouts.app')

@section('title', 'Arsip Inventaris - Sistem Inventaris Desa')

{{-- Menambahkan stylesheet yang diperlukan --}}
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
                    <i class="fas fa-archive me-1"></i>Daftar Inventaris yang Diarsipkan
                </h6>
                <div class="card-header-actions d-flex align-items-center gap-4">
                    <form method="GET" action="{{ route('inventaris.trashed') }}" class="search-form" id="searchForm">
                        <div class="input-group-icon position-relative">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" name="search" id="searchInput" class="form-control"
                                value="{{ request('search') }}" placeholder="Cari otomatis...">

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
            <div class="card-body px-0 py-0"> {{-- Padding diatur di tabel untuk konsistensi --}}
                <div class="table-responsive">
                    {{-- Menggunakan style tabel dari halaman index --}}
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th class="text-center column-no">No</th>
                                <th>Nama Barang & Kode</th>
                                <th>Kategori</th>
                                <th class="text-center">Tanggal Diarsipkan</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventaris as $item)
                                <tr>
                                    <td class="text-center column-no">
                                        {{ $loop->iteration + ($inventaris->currentPage() - 1) * $inventaris->perPage() }}
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="link-item">{{ $item->nama_barang }}</span>
                                            <small class="font-monospace text-slate-400">{{ $item->kode_inventaris }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-hilang">{{ $item->kategori->nama_kategori }}</span>
                                    </td>
                                    <td class="text-center">
                                        {{ $item->deleted_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="text-center">
                                        <div class="action-buttons-arsip justify-content-center">
                                            {{-- Tombol Kembalikan (Restore) dengan gaya baru --}}
                                            <button type="button" class="btn btn-action btn-action-restore"
                                                data-bs-toggle="modal" data-bs-target="#restoreModal" data-id="{{ $item->id }}"
                                                data-nama="{{ $item->nama_barang }}">
                                                <i class="fas fa-undo"></i> Kembalikan
                                            </button>

                                            {{-- Tombol Proses Penghapusan dengan gaya baru --}}
                                            <a href="{{ route('penghapusan.create', ['inventaris_id' => $item->id]) }}"
                                                class="btn btn-action btn-action-delete">
                                                <i class="fas fa-file-signature"></i> Proses Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="text-center py-5">
                                            <i class="fas fa-box-open fa-3x text-slate-300 mb-3"></i>
                                            <h5 class="text-slate-600">Arsip Kosong</h5>
                                            <p class="text-slate-400">Tidak ada data inventaris yang diarsipkan saat ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination dengan container yang konsisten --}}
                @if ($inventaris->hasPages())
                    <div class="card-footer pagination-container">
                        <div class="text-slate-500 small">
                            Menampilkan **{{ $inventaris->firstItem() }}** - **{{ $inventaris->lastItem() }}** dari
                            **{{ $inventaris->total() }}** item
                        </div>
                        <div>
                            {{ $inventaris->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal Restore dengan gaya yang sudah disesuaikan oleh CSS baru --}}
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success"> {{-- Kelas bg-success kini hanya sebagai penanda --}}
                    <h5 class="modal-title"><i class="fas fa-undo me-2"></i>Konfirmasi Pengembalian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin mengembalikan inventaris <strong id="restoreItemName" class="text-primary"></strong>
                        ke daftar aktif?</p>
                </div>
                <div class="modal-footer">
                    <form id="restoreForm" method="POST" class="d-flex gap-2">
                        @csrf
                        <button type="button" class="btn btn-outline-secondary btn-pill"
                            data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success btn-pill">Ya, Kembalikan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("searchInput");
            const searchForm = document.getElementById("searchForm");
            const clearBtn = document.getElementById("clearSearch");

            let debounceTimer;
            searchInput.addEventListener("input", function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    searchForm.submit(); // auto submit
                }, 500); // delay 0.5 detik setelah berhenti mengetik
            });

            if (clearBtn) {
                clearBtn.addEventListener("click", function () {
                    searchInput.value = "";
                    searchForm.submit();
                });
            }
        });
    </script>
    {{-- Script Anda sudah bagus dan tidak perlu diubah --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const restoreModal = document.getElementById('restoreModal');
            if (restoreModal) {
                restoreModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const nama = button.getAttribute('data-nama');
                    const restoreUrl = "{{ route('inventaris.restore', ['inventaris' => ':id']) }}".replace(':id', id);
                    restoreModal.querySelector('#restoreItemName').textContent = nama;
                    restoreModal.querySelector('#restoreForm').action = restoreUrl;
                });
            }
        });
    </script>
@endsection