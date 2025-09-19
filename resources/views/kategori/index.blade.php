@extends('layouts.app')

@section('title', 'Manajemen Kategori - Sistem Inventaris Desa')

@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="page-title"><i class="fas fa-tags me-2"></i>Manajemen Kategori Inventaris</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Kategori</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Daftar Kategori</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th class="text-center" style="width: 15%;">Jumlah Inventaris</th>
                                <th class="text-center" style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kategoris as $kategori)
                                <tr>
                                    <td class="text-muted">{{ $loop->iteration + $kategoris->firstItem() - 1 }}</td>
                                    <td><strong>{{ $kategori->nama_kategori }}</strong></td>
                                    <td>{{ $kategori->deskripsi ? Str::limit($kategori->deskripsi, 100, '...') : '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">
                                            {{ $kategori->inventaris_count ?? $kategori->inventaris()->count() }} Item
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#editKategoriModal{{ $kategori->id }}">
                                            <i class="fas fa-edit me-1"></i> Edit Deskripsi
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal untuk setiap kategori -->
                                <div class="modal fade" id="editKategoriModal{{ $kategori->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-edit me-2"></i>Edit Deskripsi:
                                                    {{ $kategori->nama_kategori }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('kategori.update', $kategori) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Kategori</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $kategori->nama_kategori }}" readonly>
                                                        <div class="form-text">Nama kategori tidak dapat diubah.</div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="deskripsi{{ $kategori->id }}"
                                                            class="form-label">Deskripsi</label>
                                                        <textarea class="form-control" id="deskripsi{{ $kategori->id }}"
                                                            name="deskripsi" rows="5"
                                                            placeholder="Masukkan deskripsi untuk kategori ini...">{{ $kategori->deskripsi }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <p class="text-muted mb-0">Tidak ada data kategori ditemukan.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($kategoris->hasPages())
                <div class="card-footer bg-light">
                    {{ $kategoris->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Edit Kategori -->
    <div class="modal fade" id="editKategoriModal" tabindex="-1" aria-labelledby="editKategoriModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKategoriModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Deskripsi Kategori
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editKategoriForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_nama_kategori" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="edit_nama_kategori" readonly>
                            <div class="form-text">Nama kategori tidak dapat diubah.</div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="5"
                                placeholder="Masukkan deskripsi untuk kategori ini..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .page-title {
            font-weight: 600;
        }

        .table th {
            font-weight: 500;
            text-transform: uppercase;
            font-size: 0.8rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editKategoriModal = document.getElementById('editKategoriModal');

            if (editKategoriModal) {
                editKategoriModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;

                    // Mengambil data dari atribut data
                    const id = button.getAttribute('data-id');
                    const nama = button.getAttribute('data-nama');
                    const deskripsi = button.getAttribute('data-deskripsi');

                    console.log('Data dari button:', { id, nama, deskripsi }); // Debug

                    // Mengisi form modal
                    const form = document.getElementById('editKategoriForm');
                    const inputNama = document.getElementById('edit_nama_kategori');
                    const textareaDeskripsi = document.getElementById('edit_deskripsi');

                    // Set action URL untuk form - PERBAIKAN DI SINI
                    // Gunakan route named route untuk memastikan URL benar
                    form.action = "{{ route('kategori.update', ['kategori' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', id);

                    console.log('Form action:', form.action); // Debug

                    // Isi nilai form
                    inputNama.value = nama || '';
                    textareaDeskripsi.value = deskripsi || '';

                    // Update judul modal
                    const modalTitle = document.getElementById('editKategoriModalLabel');
                    modalTitle.textContent = 'Edit Deskripsi: ' + nama;
                });

                // Menangani submit form
                const editForm = document.getElementById('editKategoriForm');
                editForm.addEventListener('submit', function () {
                    const submitButton = editForm.querySelector('button[type="submit"]');
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                });
            }
        });
    </script>
@endpush