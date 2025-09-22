@extends('layouts.app')

@section('title', 'Tambah Inventaris Baru - Sistem Inventaris Desa')

@section('styles')
    {{-- PENTING: Load CSS index terlebih dahulu untuk mendapatkan variabel --}}
    <link rel="stylesheet" href="{{ asset('css/inventaris-index.css') }}">
    {{-- CSS SPESIFIK UNTUK HALAMAN CREATE --}}
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
                    <i class="fas fa-plus-circle me-2"></i>Tambah Inventaris Baru
                </h6>
            </div>
            
                {{-- Progress Steps dengan desain konsisten --}}
                <div class="progress-container"> 
                    <div class="steps-progress">
                        <div class="step active" data-step="1">
                            <div class="step-circle">
                                <span class="step-number">1</span>
                                <div class="step-check">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="step-label">
                                <span class="step-title">Pilih Jenis</span>
                                <span class="step-subtitle d-none d-md-block">Kategori inventaris</span>
                            </div>
                        </div>
                        <div class="step-line"></div>
                        <div class="step" data-step="2">
                            <div class="step-circle">
                                <span class="step-number">2</span>
                                <div class="step-check">
                                    <i class="fas fa-check"></i>
                                </div>
                            </div>
                            <div class="step-label">
                                <span class="step-title">Informasi Umum</span>
                                <span class="step-subtitle d-none d-md-block">Data dasar barang</span>
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
                                <span class="step-title">Detail Spesifik</span>
                                <span class="step-subtitle d-none d-md-block">Info kategori</span>
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
                            <span class="step-subtitle d-none d-md-block">Tinjau & simpan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert dengan gaya modern konsisten dengan index --}}
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

        <form method="POST" action="{{ route('inventaris.store') }}" enctype="multipart/form-data" id="inventarisForm">
            @csrf 

            {{-- STEP 1: PILIH JENIS INVENTARIS --}}
            <div class="modern-card step-content mb-4" id="step-1">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Langkah 1: Pilih Jenis Inventaris
                    </h6>
                </div>
                <div class="card-body">
                    <div class="info-box mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Pilih jenis inventaris yang akan ditambahkan. Pilihan ini akan menentukan informasi detail yang perlu diisi.
                    </div>

                    <div class="kategori-selection">
                        <label class="form-label fw-bold">Jenis Inventaris <span class="text-danger">*</span></label>
                        <div class="row g-3">
                            @foreach($kategori as $kat)
                                <div class="col-md-6">
                                    <div class="kategori-card">
                                        <input class="form-check-input" type="radio" name="kategori_id"
                                            id="kategori_{{ $kat->id }}" value="{{ $kat->id }}" 
                                            {{ old('kategori_id') == $kat->id ? 'checked' : '' }} required>
                                        <label class="kategori-label" for="kategori_{{ $kat->id }}">
                                            <div class="kategori-icon">
                                                <i class="fas {{ $kat->icon ?? 'fa-cube' }}"></i>
                                            </div>
                                            <div class="kategori-content">
                                                <div class="kategori-name">{{ $kat->nama_kategori }}</div>
                                                <div class="kategori-desc">{{ $kat->deskripsi ?? 'Kategori ' . $kat->nama_kategori }}</div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @error('kategori_id')
                            <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <div class="step-navigation">
                        <div></div>
                        <button type="button" class="btn btn-primary btn-pill next-step" data-next="2">
                            Lanjut <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- STEP 2: INFORMASI UMUM --}}
            <div class="modern-card step-content mb-4" id="step-2" style="display: none;">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Langkah 2: Informasi Umum
                    </h6>
                </div>
                <div class="card-body">
                    <div class="info-box mb-4">
                        <i class="fas fa-info-circle me-2"></i>Isi informasi dasar inventaris.
                        Field dengan tanda (<span class="text-danger">*</span>) wajib diisi.
                    </div>

                    <div class="row g-3">
                        <!-- Field Umum -->
                        <div class="col-md-6">
                            <label for="nama_barang" class="form-label">Nama Barang/Aset <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                id="nama_barang" name="nama_barang" value="{{ old('nama_barang') }}"
                                placeholder="Contoh: Meja Kerja, Komputer, Kursi" required>
                            <div class="form-text">Masukkan nama barang yang mudah dikenali</div>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="kode_inventaris" class="form-label">Kode Inventaris <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('kode_inventaris') is-invalid @enderror"
                                    id="kode_inventaris" name="kode_inventaris" value="{{ old('kode_inventaris') }}"
                                    placeholder="Klik generate untuk membuat kode" required readonly>
                                <button class="btn btn-outline-primary" type="button" id="generateKode">
                                    <i class="fas fa-sync-alt me-1"></i> Generate
                                </button>
                                @error('kode_inventaris')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Pilih jenis inventaris terlebih dahulu lalu klik Generate</small>
                        </div>

                        <div class="col-md-3">
                            <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah"
                                name="jumlah" value="{{ old('jumlah', 1) }}" min="1" required>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="kondisi" class="form-label">Kondisi <span class="text-danger">*</span></label>
                            <select class="form-select @error('kondisi') is-invalid @enderror" id="kondisi" name="kondisi" required>
                                <option value="">Pilih Kondisi</option>
                                <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                <option value="Rusak" {{ old('kondisi') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                <option value="Perlu Perbaikan" {{ old('kondisi') == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                                <option value="Hilang" {{ old('kondisi') == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                            </select>
                            @error('kondisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="lokasi_penempatan" class="form-label">Lokasi Penempatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('lokasi_penempatan') is-invalid @enderror"
                                id="lokasi_penempatan" name="lokasi_penempatan" value="{{ old('lokasi_penempatan') }}"
                                placeholder="Contoh: Ruang Kepala Desa, Gudang A, Lapangan Desa" required>
                            @error('lokasi_penempatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror"
                                id="tanggal_masuk" name="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                            @error('tanggal_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="sumber_dana" class="form-label">Sumber Dana <span class="text-danger">*</span></label>
                            <select class="form-select @error('sumber_dana') is-invalid @enderror" id="sumber_dana" name="sumber_dana" required>
                                <option value="">Pilih Sumber Dana</option>
                                <option value="APBN" {{ old('sumber_dana') == 'APBN' ? 'selected' : '' }}>APBN</option>
                                <option value="APBD" {{ old('sumber_dana') == 'APBD' ? 'selected' : '' }}>APBD</option>
                                <option value="ADD" {{ old('sumber_dana') == 'ADD' ? 'selected' : '' }}>ADD (Alokasi Dana Desa)</option>
                                <option value="DD" {{ old('sumber_dana') == 'DD' ? 'selected' : '' }}>DD (Dana Desa)</option>
                                <option value="Swadaya" {{ old('sumber_dana') == 'Swadaya' ? 'selected' : '' }}>Swadaya</option>
                                <option value="Bantuan" {{ old('sumber_dana') == 'Bantuan' ? 'selected' : '' }}>Bantuan</option>
                                <option value="Lainnya" {{ old('sumber_dana') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('sumber_dana')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="harga_perolehan_display" class="form-label">Harga Perolehan (Satuan)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control @error('harga_perolehan') is-invalid @enderror"
                                    id="harga_perolehan_display" placeholder="Contoh: 1.500.000">
                            </div>
                            <input type="hidden" id="harga_perolehan" name="harga_perolehan" value="{{ old('harga_perolehan') }}">
                            @error('harga_perolehan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="total_nilai" class="form-label">Total Nilai Aset</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control" id="total_nilai" readonly disabled>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="masa_pakai_tahun" class="form-label">Masa Pakai (Tahun)</label>
                            <input type="number" class="form-control @error('masa_pakai_tahun') is-invalid @enderror"
                                id="masa_pakai_tahun" name="masa_pakai_tahun" value="{{ old('masa_pakai_tahun') }}" 
                                min="1" placeholder="Contoh: 5">
                            <small class="form-text text-muted">Opsional - Perkiraan masa pakai dalam tahun</small>
                            @error('masa_pakai_tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-8">
                            <label for="deskripsi" class="form-label">Deskripsi/Keterangan</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                name="deskripsi" rows="3" placeholder="Deskripsi tambahan mengenai barang/aset (opsional)">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
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
                            Lanjut <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- STEP 3: DETAIL SPESIFIK --}}
            <div class="modern-card step-content mb-4" id="step-3" style="display: none;">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-list-alt me-2"></i>Langkah 3: Detail Spesifik
                    </h6>
                </div>
                <div class="card-body" id="detail-kategori">
                    <div class="info-box">
                        <i class="fas fa-info-circle me-2"></i>Silakan pilih jenis inventaris terlebih dahulu untuk menampilkan detail spesifik
                    </div>
                </div>

                <div class="card-footer">
                    <div class="step-navigation">
                        <button type="button" class="btn btn-outline-secondary btn-pill prev-step" data-prev="2">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </button>
                        <button type="button" class="btn btn-primary btn-pill next-step" data-next="4">
                            Lanjut <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- STEP 4: REVIEW DATA --}}
            <div class="modern-card step-content mb-4" id="step-4" style="display: none;">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>Langkah 4: Tinjau Data
                    </h6>
                </div>
                <div class="card-body">
                    <div class="info-box mb-4">
                        <i class="fas fa-info-circle me-2"></i>Tinjau data yang telah diisi sebelum disimpan.
                        Pastikan semua informasi sudah benar.
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="review-section">
                                <h6 class="review-title">Informasi Umum</h6>
                                <div class="review-table">
                                    <div class="review-row">
                                        <div class="review-label">Jenis Inventaris</div>
                                        <div class="review-value" id="review-kategori">-</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">Nama Barang</div>
                                        <div class="review-value" id="review-nama">-</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">Kode Inventaris</div>
                                        <div class="review-value font-monospace" id="review-kode">-</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">Jumlah</div>
                                        <div class="review-value" id="review-jumlah">-</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">Kondisi</div>
                                        <div class="review-value" id="review-kondisi">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="review-section">
                                <h6 class="review-title">Informasi Tambahan</h6>
                                <div class="review-table">
                                    <div class="review-row">
                                        <div class="review-label">Lokasi</div>
                                        <div class="review-value" id="review-lokasi">-</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">Tanggal Masuk</div>
                                        <div class="review-value" id="review-tanggal">-</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">Sumber Dana</div>
                                        <div class="review-value" id="review-sumber">-</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">Harga</div>
                                        <div class="review-value font-monospace" id="review-harga">-</div>
                                    </div>
                                    <div class="review-row">
                                        <div class="review-label">Masa Pakai</div>
                                        <div class="review-value" id="review-masa">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="review-section" id="review-detail-specific">
                        <h6 class="review-title">Detail Spesifik</h6>
                        <div class="review-detail-content" id="review-detail-content">
                            <p class="text-muted mb-0">Tidak ada detail spesifik</p>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="step-navigation">
                        <button type="button" class="btn btn-outline-secondary btn-pill prev-step" data-prev="3">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </button>
                        <button type="submit" class="btn btn-success btn-pill" id="btnSubmit">
                            <i class="fas fa-save me-2"></i>Simpan Inventaris
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
            let isGeneratingKode = false;

            // Inisialisasi steps
            $('.step-content').hide();
            $('#step-1').show();
            updateStepProgress(1);

            // Navigasi step
            $('.next-step').click(function () {
                const nextStep = $(this).data('next');
                const currentStep = nextStep - 1;

                // Validasi sebelum pindah step
                if (currentStep === 1) {
                    const kategoriId = $('input[name="kategori_id"]:checked').val();
                    if (!kategoriId) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Silakan pilih kategori inventaris terlebih dahulu'
                        });
                        return;
                    }

                    // Update judul Step 2
                    const namaKategori = $('input[name="kategori_id"]:checked')
                        .closest('.kategori-card')
                        .find('.kategori-name')
                        .text();

                    const judulStep2 = $('#step-2 .card-header h6');
                    if (namaKategori) {
                        judulStep2.html(`<i class="fas fa-info-circle me-2"></i>Langkah 2: Informasi Umum - <strong>${namaKategori}</strong>`);
                    }

                } else if (currentStep === 2) {
                    // Validasi form step 2
                    if (!validateStep2()) {
                        return;
                    }
                }

                // Pindah ke step berikutnya
                $(`.step-content`).hide();
                $(`#step-${nextStep}`).show();
                updateStepProgress(nextStep);

                // Jika pindah ke step 4, update review data
                if (nextStep === 4) {
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
                $('.step').removeClass('active completed');
                
                // Mark completed steps
                for (let i = 1; i < activeStep; i++) {
                    $(`.step[data-step="${i}"]`).addClass('completed');
                }
                
                // Mark active step
                $(`.step[data-step="${activeStep}"]`).addClass('active');
            }

            function validateStep2() {
                const requiredFields = [
                    { id: '#nama_barang', message: 'Nama barang tidak boleh kosong' },
                    { id: '#kode_inventaris', message: 'Kode inventaris tidak boleh kosong' },
                    { id: '#jumlah', message: 'Jumlah tidak boleh kosong' },
                    { id: '#kondisi', message: 'Kondisi harus dipilih' },
                    { id: '#lokasi_penempatan', message: 'Lokasi penempatan tidak boleh kosong' },
                    { id: '#tanggal_masuk', message: 'Tanggal masuk tidak boleh kosong' },
                    { id: '#sumber_dana', message: 'Sumber dana harus dipilih' },
                    { id: '#harga_perolehan_display', message: 'Harga perolehan tidak boleh kosong' }
                ];

                for (const field of requiredFields) {
                    const value = $(field.id).val().trim();
                    if (!value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: field.message
                        });
                        $(field.id).focus();
                        return false;
                    }
                }

                return true;
            }

            function updateReviewData() {
                // Informasi umum
                const kategoriText = $('input[name="kategori_id"]:checked').closest('.kategori-card').find('.kategori-name').text();
                $('#review-kategori').text(kategoriText);
                $('#review-nama').text($('#nama_barang').val());
                $('#review-kode').text($('#kode_inventaris').val());
                $('#review-jumlah').text($('#jumlah').val());
                $('#review-kondisi').text($('#kondisi option:selected').text());
                $('#review-lokasi').text($('#lokasi_penempatan').val());
                $('#review-tanggal').text($('#tanggal_masuk').val());
                $('#review-sumber').text($('#sumber_dana option:selected').text());

                // Format harga
                const harga = $('#harga_perolehan').val();
                $('#review-harga').text(formatRupiah(harga));

                // Masa pakai
                const masaPakai = $('#masa_pakai_tahun').val();
                $('#review-masa').text(masaPakai ? masaPakai + ' tahun' : '-');

                // Deskripsi
                const deskripsi = $('#deskripsi').val();
                if (deskripsi) {
                    $('#review-deskripsi').text(deskripsi);
                }

                // Detail spesifik
                const detailFields = $('#detail-kategori').find('input, select, textarea');
                let detailHtml = '';

                if (detailFields.length > 0) {
                    detailHtml = '<div class="review-table">';
                    detailFields.each(function () {
                        const fieldName = $(this).attr('name');
                        const fieldLabel = $(this).closest('.col-md-6').find('label').text().replace('*', '').trim();
                        let fieldValue = $(this).val();

                        if ($(this).is('select')) {
                            fieldValue = $(this).find('option:selected').text();
                        }

                        if (fieldValue) {
                            detailHtml += `
                                <div class="review-row">
                                    <div class="review-label">${fieldLabel}</div>
                                    <div class="review-value">${fieldValue}</div>
                                </div>
                            `;
                        }
                    });
                    detailHtml += '</div>';
                } else {
                    detailHtml = '<p class="text-muted mb-0">Tidak ada detail spesifik</p>';
                }

                $('#review-detail-content').html(detailHtml);
            }

            // Auto generate kode saat kategori dipilih
            $('input[name="kategori_id"]').change(function () {
                const kategoriId = $(this).val();

                // Reset kode inventaris
                $('#kode_inventaris').val('');

                // Load detail spesifik untuk kategori yang dipilih
                loadKategoriDetail(kategoriId);
            });

            // Generate kode inventaris
            $('#generateKode').click(function () {
                generateKodeInventaris();
            });

            function generateKodeInventaris() {
                const kategoriId = $('input[name="kategori_id"]:checked').val();

                if (!kategoriId) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Silakan pilih kategori inventaris terlebih dahulu'
                    });
                    return;
                }

                if (isGeneratingKode) {
                    return;
                }

                isGeneratingKode = true;
                const btnGenerate = $('#generateKode');
                const originalText = btnGenerate.html();

                btnGenerate.prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin"></i> Loading...');

                $.ajax({
                    url: '{{ route("inventaris.generate-kode") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        kategori_id: kategoriId
                    },
                    success: function (response) {
                        $('#kode_inventaris').val(response.kode);

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Kode inventaris berhasil di-generate',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        let errorMessage = 'Gagal generate kode inventaris';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: errorMessage
                        });
                    },
                    complete: function () {
                        isGeneratingKode = false;
                        btnGenerate.prop('disabled', false).html('<i class="fas fa-sync-alt me-1"></i> Generate');
                    }
                });
            }

            function loadKategoriDetail(kategoriId) {
                const detailDiv = $('#detail-kategori');

                if (!kategoriId) {
                    return;
                }

                // Tampilkan loading
                detailDiv.html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 mb-0">Memuat form detail kategori...</p>
                    </div>
                `);

                $.ajax({
                    url: `/inventaris/get-kategori-detail/${kategoriId}`,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        if (data.error) {
                            detailDiv.html(`
                                <div class="alert alert-danger modern-alert">
                                    <div class="alert-icon">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>
                                    <div class="alert-content">${data.error}</div>
                                </div>
                            `);
                            return;
                        }

                        if (!data.fields || Object.keys(data.fields).length === 0) {
                            detailDiv.html(`
                                <div class="info-box">
                                    <i class="fas fa-info-circle me-2"></i>Tidak ada detail spesifik untuk kategori ini
                                </div>
                            `);
                            return;
                        }

                        let html = '<div class="row g-3">';

                        // Generate form fields berdasarkan kategori
                        $.each(data.fields, function (fieldName, fieldConfig) {
                            const colClass = fieldConfig.col ? `col-md-${fieldConfig.col}` : 'col-md-6';
                            const required = fieldConfig.required ? 'required' : '';
                            const requiredMark = fieldConfig.required ? ' <span class="text-danger">*</span>' : '';
                            const oldValue = "{{ old('" + fieldName + "') }}";

                            html += `<div class="${colClass}">`;
                            html += `<label for="${fieldName}" class="form-label">${fieldConfig.label}${requiredMark}</label>`;

                            if (fieldConfig.type === 'textarea') {
                                html += `<textarea class="form-control" id="${fieldName}" name="${fieldName}" 
                                    rows="3" placeholder="${fieldConfig.placeholder || ''}" ${required}>${oldValue}</textarea>`;
                            } else if (fieldConfig.type === 'select' && fieldConfig.options) {
                                html += `<select class="form-select" id="${fieldName}" name="${fieldName}" ${required}>`;
                                html += `<option value="">Pilih ${fieldConfig.label}</option>`;
                                $.each(fieldConfig.options, function (key, value) {
                                    const selected = oldValue === key ? 'selected' : '';
                                    html += `<option value="${key}" ${selected}>${value}</option>`;
                                });
                                html += `</select>`;
                            } else {
                                const step = fieldConfig.step ? `step="${fieldConfig.step}"` : '';
                                const min = fieldConfig.min ? `min="${fieldConfig.min}"` : '';
                                const max = fieldConfig.max ? `max="${fieldConfig.max}"` : '';
                                const placeholder = fieldConfig.placeholder ? `placeholder="${fieldConfig.placeholder}"` : '';

                                html += `<input type="${fieldConfig.type}" class="form-control" 
                                    id="${fieldName}" name="${fieldName}" value="${oldValue}"
                                    ${step} ${min} ${max} ${placeholder} ${required}>`;
                            }

                            if (fieldConfig.help) {
                                html += `<small class="form-text text-muted">${fieldConfig.help}</small>`;
                            }

                            html += `</div>`;
                        });

                        html += '</div>';
                        detailDiv.html(html);
                    },
                    error: function (xhr) {
                        let errorMessage = 'Terjadi kesalahan saat memuat form detail';
                        if (xhr.responseJSON && xhr.responseJSON.error) {
                            errorMessage = xhr.responseJSON.error;
                        } else if (xhr.status === 404) {
                            errorMessage = 'Kategori tidak ditemukan';
                        }

                        detailDiv.html(`
                            <div class="alert alert-danger modern-alert">
                                <div class="alert-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="alert-content">${errorMessage}</div>
                            </div>
                        `);
                    }
                });
            }

            // Format input harga dengan thousand separator
            $('#harga_perolehan_display').on('input', function () {
                // Format tampilan
                this.value = formatRupiahInput(this.value);

                // Simpan nilai numerik murni di hidden field
                const numericValue = this.value.replace(/\./g, '');
                $('#harga_perolehan').val(numericValue);

                // Hitung total nilai
                calculateTotal();
            });

            function formatRupiahInput(angka) {
                // Hapus semua karakter selain angka
                let number_string = angka.replace(/[^,\d]/g, '').toString();

                // Jika kosong, return empty string
                if (number_string === '') return '';

                // Konversi ke number dan format ke Rupiah
                let number = parseInt(number_string);
                return new Intl.NumberFormat('id-ID').format(number);
            }

            function formatRupiah(angka) {
                if (!angka) return 'Rp 0';
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
            }

            // Fungsi untuk menghitung total nilai
            function calculateTotal() {
                const jumlah = parseInt($('#jumlah').val()) || 0;
                const harga = parseInt($('#harga_perolehan').val()) || 0;
                const total = jumlah * harga;

                $('#total_nilai').val(formatRupiahInput(total.toString()));
            }

            // Hitung total saat jumlah berubah
            $('#jumlah').on('input', calculateTotal);

            // Form validation sebelum submit
            $('#inventarisForm').submit(function (e) {
                // Validasi akhir sebelum submit
                if (!validateStep2()) {
                    e.preventDefault();
                    // Kembali ke step 2 jika validasi gagal
                    $('.step-content').hide();
                    $('#step-2').show();
                    updateStepProgress(2);
                    return false;
                }

                // Pastikan nilai harga perolehan adalah numerik
                const hargaValue = $('#harga_perolehan').val();
                if (!hargaValue || isNaN(hargaValue)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Harga perolehan harus berupa angka'
                    });
                    $('#harga_perolehan_display').focus();
                    return false;
                }

                // Disable submit button to prevent double submission
                $('#btnSubmit').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');

                return true;
            });

            // Load detail jika ada old input kategori_id (untuk handle validation error)
            const oldKategoriId = "{{ old('kategori_id') }}";
            if (oldKategoriId) {
                loadKategoriDetail(oldKategoriId);
                // Jika ada error, langsung tampilkan step 2
                $('.step-content').hide();
                $('#step-2').show();
                updateStepProgress(2);
            }

            // Inisialisasi nilai harga jika ada old input
            const oldHarga = "{{ old('harga_perolehan') }}";
            if (oldHarga) {
                $('#harga_perolehan_display').val(formatRupiahInput(oldHarga));
                $('#harga_perolehan').val(oldHarga);
                calculateTotal();
            }
        });
    </script>
@endsection