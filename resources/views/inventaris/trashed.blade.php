@extends('layouts.app')

@section('title', 'Arsip Inventaris - Sistem Inventaris Desa')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Arsip Inventaris</h1>
            <a href="{{ route('inventaris.index') }}" class="btn btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali ke Daftar Aktif ({{ $activeCount }})
            </a>
        </div>

        @include('layouts.partials.alerts')

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Daftar Inventaris yang Diarsipkan</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode & Nama Barang</th>
                                <th>Kategori</th>
                                <th>Tanggal Diarsipkan</th>
                                <th width="25%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inventaris as $item)
                                <tr>
                                    <td>{{ $loop->iteration + $inventaris->firstItem() - 1 }}</td>
                                    <td>
                                        {{ $item->nama_barang }}
                                        <small class="d-block text-muted">{{ $item->kode_inventaris }}</small>
                                    </td>
                                    <td>{{ $item->kategori->nama_kategori }}</td>
                                    <td>{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                                    {{-- KODE BARU YANG BENAR --}}
                                    <td>
                                        {{-- Tombol Kembalikan (Restore) tetap menggunakan modal, ini sudah benar --}}
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#restoreModal" data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama_barang }}">
                                            <i class="fas fa-undo"></i> Kembalikan
                                        </button>

                                        {{-- Tombol Hapus diubah menjadi LINK ke form berita acara, BUKAN modal --}}
                                        <a href="{{ route('penghapusan.create', ['inventaris_id' => $item->id]) }}"
                                            class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Proses Penghapusan
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Arsip kosong.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end">
                    {{ $inventaris->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="restoreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-undo me-2"></i>Konfirmasi Pengembalian</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Kembalikan inventaris <strong id="restoreItemName"></strong> ke daftar aktif?</p>
                </div>
                <div class="modal-footer">
                    <form id="restoreForm" method="POST">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Ya, Kembalikan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="modal fade" id="permanentDeleteModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Hapus Permanen Inventaris</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="permanentDeleteForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('DELETE')
                            <div class="modal-body">
                                <div class="alert alert-danger">
                                    Anda akan menghapus inventaris <strong id="deleteItemName"></strong> secara permanen. Tindakan
                                    ini **tidak dapat dibatalkan**.
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <label for="nomor_berita_acara" class="form-label">Nomor Berita Acara <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nomor_berita_acara" required>
                                </div>
                                <div class="mb-3">
                                    <label for="file_berita_acara" class="form-label">File Berita Acara (PDF) <span
                                            class="text-danger">*</span></label>
                                    <input type="file" class="form-control" name="file_berita_acara" accept=".pdf" required>
                                </div>
                                <div class="mb-3">
                                    <label for="alasan_penghapusan" class="form-label">Alasan Penghapusan <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" name="alasan_penghapusan" rows="3" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Ya, Hapus Permanen</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> -->
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Modal untuk Restore
            const restoreModal = document.getElementById('restoreModal');
            if (restoreModal) {
                restoreModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const nama = button.getAttribute('data-nama');

                    // Menggunakan route helper Laravel untuk keamanan dan konsistensi
                    const restoreUrl = "{{ route('inventaris.restore', ['inventaris' => ':id']) }}".replace(':id', id);

                    restoreModal.querySelector('#restoreItemName').textContent = nama;
                    restoreModal.querySelector('#restoreForm').action = restoreUrl;
                });
            }

            // Modal untuk Hapus Permanen
            const permanentDeleteModal = document.getElementById('permanentDeleteModal');
            if (permanentDeleteModal) {
                permanentDeleteModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const id = button.getAttribute('data-id');
                    const nama = button.getAttribute('data-nama');

                    // Menggunakan route helper Laravel
                    const forceDeleteUrl = "{{ route('inventaris.forceDelete', ['inventaris' => ':id']) }}".replace(':id', id);

                    permanentDeleteModal.querySelector('#deleteItemName').textContent = nama;
                    permanentDeleteModal.querySelector('#permanentDeleteForm').action = forceDeleteUrl;
                });
            }
        });
    </script>
@endsection