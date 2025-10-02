@extends('layouts.app')

@section('title', 'Proses Penghapusan Inventaris - Sistem Inventaris Desa')

@section('styles')
    {{-- PENTING: Load CSS index terlebih dahulu untuk mendapatkan variabel --}}
    <link rel="stylesheet" href="{{ asset('css/inventaris-index.css') }}">
    {{-- CSS SPESIFIK UNTUK HALAMAN CREATE & EDIT --}}
    <link rel="stylesheet" href="{{ asset('css/inventaris-create.css') }}">
    {{-- Google Font Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
    <div class="container-fluid page-container">
        {{-- Header Halaman yang Konsisten --}}
        <div class="page-header mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h6 class="page-title mb-0">
                    <i class="fas fa-trash-alt me-2"></i>Proses Penghapusan Inventaris
                </h6>
                <a href="{{ route('inventaris.trashed') }}" class="btn btn-outline-secondary btn-pill">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>

            {{-- Progress Steps dengan desain konsisten - Untuk Delete, fokus pada konfirmasi --}}
            <div class="progress-container">
                <div class="steps-progress">
                    <div class="step completed" data-step="1">
                        <div class="step-circle">
                            <span class="step-number">1</span>
                            <div class="step-check">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <div class="step-label">
                            <span class="step-title">Aset Dipilih</span>
                            <span class="step-subtitle d-none d-md-block">{{ $inventaris->nama_barang }}</span>
                        </div>
                    </div>
                    <div class="step-line"></div>
                    <div class="step active" data-step="2">
                        <div class="step-circle">
                            <span class="step-number">2</span>
                            <div class="step-check">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <div class="step-label">
                            <span class="step-title">Data Penghapusan</span>
                            <span class="step-subtitle d-none d-md-block">Lengkapi dokumen</span>
                        </div>
                    </div>
                    <div class="step-line"></div>
                    <div class="step" data-step="3">
                        <div class="step-circle">
                            <span class="step-number">3</span>
                            <div class="step-check">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <div class="step-label">
                            <span class="step-title">Konfirmasi</span>
                            <span class="step-subtitle d-none d-md-block">Tinjau & hapus</span>
                        </div>
                    </div>
                    <div class="step-line"></div>
                    <div class="step" data-step="4">
                        <div class="step-circle">
                            <span class="step-number">4</span>
                            <div class="step-check">
                                <i class="fas fa-check"></i>
                            </div>
                        </div>
                        <div class="step-label">
                            <span class="step-title">Selesai</span>
                            <span class="step-subtitle d-none d-md-block">Aset dihapus</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert dengan gaya modern konsisten dengan create --}}
        @if($errors->any())
            <div class="modern-card mb-4">
                <div class="alert alert-danger modern-alert mb-0" role="alert">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <div class="alert-title">Terjadi kesalahan:</div>
                        <ul class="alert-list">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="modern-card mb-4">
                <div class="alert alert-danger modern-alert mb-0" role="alert">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        {{-- Warning Alert untuk Penghapusan --}}
        <div class="modern-card mb-4">
            <div class="alert alert-warning modern-alert mb-0" role="alert">
                <div class="alert-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="alert-content">
                    <div class="alert-title">Peringatan Penting!</div>
                    <p class="mb-0">Proses penghapusan ini bersifat permanen dan tidak dapat dibatalkan. Pastikan Anda telah
                        mempertimbangkan keputusan ini dengan matang dan memiliki dokumen pendukung yang valid.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('penghapusan.store') }}" enctype="multipart/form-data" id="penghapusanForm">
            @csrf
            <input type="hidden" name="inventaris_id" value="{{ $inventaris->id }}">

            {{-- STEP 1: KONFIRMASI ASET --}}
            <div class="modern-card step-content mb-4" id="step-1">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Konfirmasi Aset yang Akan Dihapus
                    </h6>
                </div>
                <div class="card-body">
                    <div class="info-box mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Pastikan data aset berikut adalah benar dan akan dihapus secara permanen dari sistem.
                    </div>

                    <div class="aset-confirmation-card">
                        <div class="aset-header">
                            <div class="aset-icon">
                                <i class="fas {{ $inventaris->kategori->icon ?? 'fa-cube' }}"></i>
                            </div>
                            <div class="aset-basic-info">
                                <h5 class="aset-name">{{ $inventaris->nama_barang }}</h5>
                                <p class="aset-code">{{ $inventaris->kode_inventaris }}</p>
                            </div>
                            <div class="aset-status">
                                <span class="badge badge-danger">Akan Dihapus</span>
                            </div>
                        </div>

                        <div class="aset-details">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <span class="detail-label">Kategori</span>
                                        <span class="detail-value">{{ $inventaris->kategori->nama_kategori }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Kondisi</span>
                                        <span class="detail-value">
                                            <span class="badge badge-kondisi badge-{{ strtolower($inventaris->kondisi) }}">
                                                {{ $inventaris->kondisi }}
                                            </span>
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Lokasi</span>
                                        <span class="detail-value">{{ $inventaris->lokasi_penempatan }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="detail-item">
                                        <span class="detail-label">Tanggal Masuk</span>
                                        <span class="detail-value">{{ $inventaris->tanggal_masuk->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Jumlah</span>
                                        <span class="detail-value">{{ $inventaris->jumlah }} unit</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Nilai Aset</span>
                                        <span class="detail-value">
                                            @if($inventaris->harga_perolehan)
                                                Rp
                                                {{ number_format($inventaris->harga_perolehan * $inventaris->jumlah, 0, ',', '.') }}
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="step-navigation">
                        <div></div>
                        <button type="button" class="btn btn-primary btn-pill next-step" data-next="2">
                            Lanjut ke Data Penghapusan <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- STEP 2: DATA PENGHAPUSAN --}}
            <div class="modern-card step-content mb-4" id="step-2" style="display: none;">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-file-alt me-2"></i>Data Penghapusan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="info-box mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Lengkapi data penghapusan berikut. Semua field wajib diisi untuk proses dokumentasi yang valid.
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="tanggal_penghapusan" class="form-label">Tanggal Penghapusan <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_penghapusan') is-invalid @enderror"
                                id="tanggal_penghapusan" name="tanggal_penghapusan"
                                value="{{ old('tanggal_penghapusan', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                            <div class="form-text">Tanggal tidak boleh lebih dari hari ini</div>
                            @error('tanggal_penghapusan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="nomor_berita_acara" class="form-label">Nomor Berita Acara <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nomor_berita_acara') is-invalid @enderror"
                                id="nomor_berita_acara" name="nomor_berita_acara" value="{{ old('nomor_berita_acara') }}"
                                placeholder="Contoh: BA/PENGHAPUSAN/001/2024" required>
                            <div class="form-text">Format: BA/PENGHAPUSAN/XXX/YYYY</div>
                            @error('nomor_berita_acara')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="alasan_penghapusan" class="form-label">Alasan Penghapusan <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('alasan_penghapusan') is-invalid @enderror"
                                id="alasan_penghapusan" name="alasan_penghapusan" rows="4"
                                placeholder="Jelaskan dengan detail alasan aset ini dihapus dari inventaris (contoh: Rusak berat tidak dapat diperbaiki, hilang/dicuri, habis masa pakai, dll.)"
                                required>{{ old('alasan_penghapusan') }}</textarea>
                            <div class="form-text">Berikan penjelasan yang jelas dan detail</div>
                            @error('alasan_penghapusan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="file_berita_acara" class="form-label">Upload File Berita Acara <span
                                    class="text-danger">*</span></label>
                            <div class="file-upload-area">
                                <input type="file" class="form-control @error('file_berita_acara') is-invalid @enderror"
                                    id="file_berita_acara" name="file_berita_acara" accept=".pdf" required>
                                <div class="file-upload-info">
                                    <i class="fas fa-file-pdf me-2"></i>
                                    <div class="file-info">
                                        <div class="file-type">Format: PDF</div>
                                        <div class="file-size">Maksimal: 5MB</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-text">Upload berita acara penghapusan yang telah ditandatangani dalam format
                                PDF</div>
                            @error('file_berita_acara')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="step-navigation">
                        <button type="button" class="btn btn-outline-secondary btn-pill prev-step" data-prev="1">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </button>
                        <button type="button" class="btn btn-primary btn-pill next-step" data-next="3">
                            Lanjut ke Konfirmasi <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- STEP 3: KONFIRMASI FINAL --}}
            <div class="modern-card step-content mb-4" id="step-3" style="display: none;">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>Konfirmasi Penghapusan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger modern-alert mb-4">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="alert-content">
                            <div class="alert-title">Konfirmasi Terakhir</div>
                            <p class="mb-0">Tinjau sekali lagi data di bawah ini. Setelah dikonfirmasi, aset akan dihapus
                                permanen dan tidak dapat dikembalikan.</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="review-section">
                                <h6 class="review-title">Data Aset</h6>
                                <div class="review-table">
                                    <div class="review-row">
                                        <div class="review-label">Nama Barang</div>
                                        <div class="review-value">{{ $inventaris->nama_barang }}</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">Kode Inventaris</div>
                                        <div class="review-value font-monospace">{{ $inventaris->kode_inventaris }}</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">Kategori</div>
                                        <div class="review-value">{{ $inventaris->kategori->nama_kategori }}</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">Jumlah</div>
                                        <div class="review-value">{{ $inventaris->jumlah }} unit</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="review-section">
                                <h6 class="review-title">Data Penghapusan</h6>
                                <div class="review-table">
                                    <div class="review-row">
                                        <div class="review-label">Tanggal</div>
                                        <div class="review-value" id="review-tanggal">-</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">No. Berita Acara</div>
                                        <div class="review-value font-monospace" id="review-nomor">-</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">File Upload</div>
                                        <div class="review-value" id="review-file">-</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">Alasan</div>
                                        <div class="review-value" id="review-alasan">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="final-confirmation mt-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="konfirmasi_final" required>
                            <label class="form-check-label" for="konfirmasi_final">
                                <strong>Saya memahami bahwa tindakan ini tidak dapat dibatalkan</strong> dan akan menghapus
                                aset secara permanen dari sistem inventaris.
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="step-navigation">
                        <button type="button" class="btn btn-outline-secondary btn-pill prev-step" data-prev="2">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </button>
                        <button type="submit" class="btn btn-danger btn-pill" id="btnSubmit" disabled>
                            <i class="fas fa-trash-alt me-2"></i>Hapus Permanen Aset
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            // Inisialisasi steps
            $('.step-content').hide();
            $('#step-1').show();
            updateStepProgress(1);

            // Navigasi step
            $('.next-step').click(function () {
                const nextStep = $(this).data('next');
                const currentStep = nextStep - 1;

                // Validasi sebelum pindah step
                if (currentStep === 2) {
                    if (!validateStep2()) {
                        return;
                    }
                }

                // Pindah ke step berikutnya
                $(`.step-content`).hide();
                $(`#step-${nextStep}`).show();
                updateStepProgress(nextStep);

                // Jika pindah ke step 3, update review data
                if (nextStep === 3) {
                    updateReviewData();
                }
            });

            $('.prev-step').click(function () {
                const prevStep = $(this).data('prev');
                $(`.step-content`).hide();
                $(`#step-${prevStep}`).show();
                updateStepProgress(prevStep);
            });

            function updateStepProgress(activeStep) {
                $('.step').removeClass('active');

                // Mark completed steps
                for (let i = 1; i < activeStep; i++) {
                    $(`.step[data-step="${i}"]`).addClass('completed');
                }

                // Mark active step
                $(`.step[data-step="${activeStep}"]`).addClass('active');
            }

            function validateStep2() {
                const requiredFields = [
                    { id: '#tanggal_penghapusan', message: 'Tanggal penghapusan harus diisi' },
                    { id: '#nomor_berita_acara', message: 'Nomor berita acara harus diisi' },
                    { id: '#alasan_penghapusan', message: 'Alasan penghapusan harus diisi' },
                    { id: '#file_berita_acara', message: 'File berita acara harus diupload' }
                ];

                for (const field of requiredFields) {
                    const value = $(field.id).val();
                    if (!value || value.trim() === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: field.message
                        });
                        $(field.id).focus();
                        return false;
                    }
                }

                // Validasi tanggal tidak boleh di masa depan
                const tanggal = new Date($('#tanggal_penghapusan').val());
                const today = new Date();
                tanggal.setHours(0, 0, 0, 0);
                today.setHours(0, 0, 0, 0);

                if (tanggal > today) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Tanggal penghapusan tidak boleh lebih dari hari ini'
                    });
                    $('#tanggal_penghapusan').focus();
                    return false;
                }

                // Validasi file PDF
                const file = $('#file_berita_acara')[0].files[0];
                if (file) {
                    if (file.type !== 'application/pdf') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'File harus dalam format PDF'
                        });
                        $('#file_berita_acara').focus();
                        return false;
                    }

                    if (file.size > 5 * 1024 * 1024) { // 5MB
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Ukuran file tidak boleh lebih dari 5MB'
                        });
                        $('#file_berita_acara').focus();
                        return false;
                    }
                }

                return true;
            }

            function updateReviewData() {
                // Update review data
                $('#review-tanggal').text($('#tanggal_penghapusan').val());
                $('#review-nomor').text($('#nomor_berita_acara').val());
                $('#review-alasan').text($('#alasan_penghapusan').val().substring(0, 100) + '...');

                const fileName = $('#file_berita_acara')[0].files[0]?.name || 'Tidak ada file';
                $('#review-file').text(fileName);
            }

            // Enable/disable submit button based on final confirmation
            $('#konfirmasi_final').change(function () {
                $('#btnSubmit').prop('disabled', !this.checked);
            });

            // Form submission with confirmation
            $('#penghapusanForm').submit(function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi Terakhir',
                    text: 'Apakah Anda yakin akan menghapus aset ini secara permanen?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus Permanen',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state
                        $('#btnSubmit').prop('disabled', true)
                            .html('<i class="fas fa-spinner fa-spin me-2"></i>Memproses...');

                        // Submit form
                        this.submit();
                    }
                });
            });

            // File upload preview
            $('#file_berita_acara').change(function () {
                const file = this.files[0];
                if (file) {
                    const fileName = file.name;
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);

                    $(this).next('.file-upload-info').find('.file-type').text(`File: ${fileName}`);
                    $(this).next('.file-upload-info').find('.file-size').text(`Ukuran: ${fileSize} MB`);
                }
            });

            // Auto-format nomor berita acara
            $('#nomor_berita_acara').on('input', function () {
                let value = $(this).val().toUpperCase();

                // Auto-add current year if not present
                if (!value.includes('/2024') && !value.includes('/2025')) {
                    const currentYear = new Date().getFullYear();
                    if (value.endsWith('/')) {
                        value += currentYear;
                    }
                }

                $(this).val(value);
            });

            // Keyboard shortcuts
            $(document).on('keydown', function (e) {
                // Escape to go back
                if (e.key === 'Escape') {
                    const activeStep = $('.step.active').data('step');
                    if (activeStep > 1) {
                        const prevBtn = $(`.step-content#step-${activeStep} .prev-step`);
                        if (prevBtn.length) {
                            prevBtn.click();
                        }
                    }
                }
            });
        });
    </script>
@endsection