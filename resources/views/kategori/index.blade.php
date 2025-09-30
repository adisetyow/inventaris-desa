@extends('layouts.app')

@section('title', 'Manajemen Kategori - Sistem Inventaris Desa')

@section('styles')
    {{-- Load CSS untuk halaman kategori --}}
    <link rel="stylesheet" href="{{ asset('css/kategori-index.css') }}">
    {{-- Google Font Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid page-container">
        {{-- Alert Messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show modern-alert" role="alert">
                <div class="alert-content">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show modern-alert" role="alert">
                <div class="alert-content">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Main Content Card --}}
        <div class="modern-card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>Daftar Kategori
                    </h6>
                    <div class="card-meta">
                        <span class="total-count">{{ $kategoris->total() }} Kategori</span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th class="text-center" style="width: 15%;">Jumlah Inventaris</th>
                                @role('Administrator')
                                <th class="text-center" style="width: 15%;">Aksi</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kategoris as $kategori)
                                <tr class="table-row">
                                    <td class="row-number">{{ $loop->iteration + $kategoris->firstItem() - 1 }}</td>
                                    <td>
                                        <div class="category-name">
                                            <i class="fas {{ $kategori->icon ?? 'fa-cube' }} me-2 category-icon"></i>
                                            <strong>{{ $kategori->nama_kategori }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="category-description">
                                            {{ $kategori->deskripsi ? Str::limit($kategori->deskripsi, 100, '...') : '-' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="count-badge">
                                            {{ $kategori->inventaris_count ?? $kategori->inventaris()->count() }}
                                            <span class="count-label">Item</span>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-buttons">
                                            @role('Administrator')
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-pill"
                                                data-bs-toggle="modal" data-bs-target="#editKategoriModal{{ $kategori->id }}"
                                                title="Edit Deskripsi">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </button>
                                            @endrole
                                        </div>
                                    </td>
                                </tr>

                                {{-- Modal Edit untuk setiap kategori --}}
                                <div class="modal fade" id="editKategoriModal{{ $kategori->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content modern-modal">
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    <i class="fas fa-edit me-2"></i>Edit Deskripsi
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('kategori.update', $kategori) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label">Nama Kategori</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ $kategori->nama_kategori }}" readonly>
                                                        <div class="form-text">Nama kategori tidak dapat diubah.</div>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label for="deskripsi{{ $kategori->id }}"
                                                            class="form-label">Deskripsi</label>
                                                        <textarea class="form-control" id="deskripsi{{ $kategori->id }}"
                                                            name="deskripsi" rows="4"
                                                            placeholder="Masukkan deskripsi untuk kategori ini...">{{ $kategori->deskripsi }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary btn-pill"
                                                        data-bs-dismiss="modal">
                                                        Batal
                                                    </button>
                                                    <button type="submit" class="btn btn-primary btn-pill">
                                                        <i class="fas fa-save me-1"></i>Simpan Perubahan
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-icon">
                                                <i class="fas fa-tags"></i>
                                            </div>
                                            <div class="empty-content">
                                                <h6 class="empty-title">Belum Ada Kategori</h6>
                                                <p class="empty-text">Tidak ada kategori inventaris yang tersedia.</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination --}}
            @if ($kategoris->hasPages())
                <div class="card-footer">
                    <div class="pagination-wrapper">
                        {{ $kategoris->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handle form submission loading states
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function () {
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        const originalContent = submitButton.innerHTML;
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...';

                        // Reset after 3 seconds if form doesn't redirect
                        setTimeout(() => {
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalContent;
                        }, 3000);
                    }
                });
            });

            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const closeButton = alert.querySelector('.btn-close');
                    if (closeButton) {
                        closeButton.click();
                    }
                }, 5000);
            });
        });
    </script>
@endsection