@extends('layouts.app')

@section('title', 'Tambah Inventaris Baru - Sistem Inventaris Desa')

@section('content')
    <div class="container-fluid">
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="page-title">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Inventaris Baru
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('inventaris.index') }}">Inventaris</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tambah Baru</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-auto">
                    <a href="{{ route('inventaris.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Alert Error/Success -->
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('inventaris.store') }}" enctype="multipart/form-data" id="inventarisForm">
            @csrf

            <!-- Progress Steps -->
            <div class="card mb-4">
                <div class="card-body py-3">
                    <div class="steps-progress">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label d-none d-md-block">Pilih Jenis</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label d-none d-md-block">Informasi Umum</div>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label d-none d-md-block">Detail Spesifik</div>
                        </div>
                        <div class="step" data-step="4">
                            <div class="step-number">4</div>
                            <div class="step-label d-none d-md-block">Selesai</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Informasi Umum -->
            <div class="card card-form mb-4 step-content" id="step-1">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Langkah 1: Pilih Jenis Inventaris
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>Pilih jenis inventaris yang akan ditambahkan. 
                        Pilihan ini akan menentukan informasi detail yang perlu diisi.
                    </div>
                    
                    <div class="row">
                        <!-- Pilihan Kategori -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Jenis Inventaris <span class="text-danger">*</span></label>
                            <div class="kategori-options bg-light p-3 rounded">
                                @foreach($kategori as $kat)
                                    <div class="form-check mb-3 p-2 border-bottom position-relative kategori-item">
                                        <input class="form-check-input" type="radio" name="kategori_id"
                                            id="kategori_{{ $kat->id }}" value="{{ $kat->id }}" 
                                            {{ old('kategori_id') == $kat->id ? 'checked' : '' }} required>
                                        <label class="form-check-label w-100 ps-4" for="kategori_{{ $kat->id }}">
                                            <div class="d-flex align-items-center">
                                                <div class="position-absolute start-0 ms-3">
                                                    <i class="fas {{ $kat->icon ?? 'fa-cube' }} fa-fw text-primary fa-2x"></i>
                                                </div>
                                                <div class="ms-5">
                                                    <div class="fw-bold">{{ $kat->nama_kategori }}</div>
                                                    <small class="text-muted">{{ $kat->deskripsi ?? 'Kategori ' . $kat->nama_kategori }}</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('kategori_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary next-step" data-next="2">
                            Lanjut <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card Informasi Umum -->
            <div class="card card-form mb-4 step-content" id="step-2" style="display: none;">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Langkah 2: Informasi Umum
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
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
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                                id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" required>
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
                            <label for="harga_perolehan" class="form-label">Harga Perolehan (Rp) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="text" class="form-control @error('harga_perolehan') is-invalid @enderror numeric-input" 
                                    id="harga_perolehan_display" name="harga_perolehan_display" value="{{ old('harga_perolehan') ? number_format(old('harga_perolehan'), 0, ',', '.') : '' }}" 
                                    placeholder="0" required>
                                <input type="hidden" id="harga_perolehan" name="harga_perolehan" value="{{ old('harga_perolehan') }}">
                                @error('harga_perolehan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Contoh: 1.500.000</small>
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
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                id="deskripsi" name="deskripsi" rows="3" 
                                placeholder="Deskripsi tambahan mengenai barang/aset (opsional)">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4 d-flex justify-content-between">
                        <button type="button" class="btn btn-outline-secondary prev-step" data-prev="1">
                            <i class="fas fa-arrow-left me-2"></i>Kembali
                        </button>
                        <button type="button" class="btn btn-primary next-step" data-next="3">
                            Lanjut <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Card Detail Spesifik -->
            <div class="card card-form mb-4 step-content" id="step-3" style="display: none;">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-alt me-2"></i>Langkah 3: Detail Spesifik
                    </h5>
                </div>
                <div class="card-body" id="detail-kategori">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Silakan pilih jenis inventaris terlebih dahulu untuk menampilkan detail spesifik
                    </div>
                </div>
                
                <div class="mt-4 card-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="2">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </button>
                    <button type="button" class="btn btn-primary next-step" data-next="4">
                        Lanjut <i class="fas fa-arrow-right ms-2"></i>
                    </button>
                </div>
            </div>

            <!-- Review Data -->
            <div class="card card-form mb-4 step-content" id="step-4" style="display: none;">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-check-circle me-2"></i>Langkah 4: Tinjau Data
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <i class="fas fa-info-circle me-2"></i>Tinjau data yang telah diisi sebelum disimpan. 
                        Pastikan semua informasi sudah benar.
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">Informasi Umum</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Jenis Inventaris</strong></td>
                                    <td id="review-kategori">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama Barang</strong></td>
                                    <td id="review-nama">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Kode Inventaris</strong></td>
                                    <td id="review-kode">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah</strong></td>
                                    <td id="review-jumlah">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Kondisi</strong></td>
                                    <td id="review-kondisi">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="border-bottom pb-2">Informasi Tambahan</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td width="40%"><strong>Lokasi</strong></td>
                                    <td id="review-lokasi">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Masuk</strong></td>
                                    <td id="review-tanggal">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Sumber Dana</strong></td>
                                    <td id="review-sumber">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Harga</strong></td>
                                    <td id="review-harga">-</td>
                                </tr>
                                <tr>
                                    <td><strong>Masa Pakai</strong></td>
                                    <td id="review-masa">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div id="review-detail-specific" class="mt-4">
                        <h6 class="border-bottom pb-2">Detail Spesifik</h6>
                        <div id="review-detail-content" class="bg-light p-3 rounded">
                            <p class="text-muted mb-0">Tidak ada detail spesifik</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-light d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="3">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </button>
                    <button type="submit" class="btn btn-success" id="btnSubmit">
                        <i class="fas fa-save me-2"></i>Simpan Inventaris
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    let isGeneratingKode = false;

    // Inisialisasi steps
    $('.step-content').hide();
    $('#step-1').show();
    updateStepProgress(1);

    // Navigasi step
    $('.next-step').click(function() {
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

            // 1. Ambil nama kategori yang dipilih dari teksnya
            const namaKategori = $('input[name="kategori_id"]:checked')
                                 .closest('.kategori-item')
                                 .find('.fw-bold')
                                 .text();

            // 2. Cari elemen judul di Step 2
            const judulStep2 = $('#step-2 .card-header h5');
            
            // 3. Update teks judulnya jika nama kategori ditemukan
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
    
    $('.prev-step').click(function() {
        const prevStep = $(this).data('prev');
        $(`.step-content`).hide();
        $(`#step-${prevStep}`).show();
        updateStepProgress(prevStep);
    });
    
    function updateStepProgress(activeStep) {
        $('.step').removeClass('active');
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
        const kategoriText = $('input[name="kategori_id"]:checked').closest('.kategori-item').find('.fw-bold').text();
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
            detailHtml = '<table class="table table-sm">';
            detailFields.each(function() {
                const fieldName = $(this).attr('name');
                const fieldLabel = $(this).closest('.col-md-6').find('label').text().replace('*', '').trim();
                let fieldValue = $(this).val();
                
                if ($(this).is('select')) {
                    fieldValue = $(this).find('option:selected').text();
                }
                
                if (fieldValue) {
                    detailHtml += `<tr><td width="40%"><strong>${fieldLabel}</strong></td><td>${fieldValue}</td></tr>`;
                }
            });
            detailHtml += '</table>';
        } else {
            detailHtml = '<p class="text-muted mb-0">Tidak ada detail spesifik</p>';
        }
        
        $('#review-detail-content').html(detailHtml);
    }

    // Auto generate kode saat kategori dipilih
    $('input[name="kategori_id"]').change(function() {
        const kategoriId = $(this).val();
        
        // Reset kode inventaris
        $('#kode_inventaris').val('');
        
        // Load detail spesifik untuk kategori yang dipilih
        loadKategoriDetail(kategoriId);
    });

    // Generate kode inventaris
    $('#generateKode').click(function() {
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
            success: function(response) {
                $('#kode_inventaris').val(response.kode);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Kode inventaris berhasil di-generate',
                    timer: 1500,
                    showConfirmButton: false
                });
            },
            error: function(xhr) {
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
            complete: function() {
                isGeneratingKode = false;
                btnGenerate.prop('disabled', false).html('<i class="fas fa-sync-alt me-1"></i> Generate');
            }
        });
    }

    function loadKategoriDetail(kategoriId) {
        const detailDiv = $('#detail-kategori');
        const cardDetail = $('#step-3');

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
            success: function(data) {
                if (data.error) {
                    detailDiv.html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>${data.error}
                        </div>
                    `);
                    return;
                }

                if (!data.fields || Object.keys(data.fields).length === 0) {
                    detailDiv.html(`
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>Tidak ada detail spesifik untuk kategori ini
                        </div>
                    `);
                    return;
                }

                let html = '<div class="row g-3">';

                // Generate form fields berdasarkan kategori
                $.each(data.fields, function(fieldName, fieldConfig) {
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
                        $.each(fieldConfig.options, function(key, value) {
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
            error: function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat memuat form detail';
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                } else if (xhr.status === 404) {
                    errorMessage = 'Kategori tidak ditemukan';
                }
                
                detailDiv.html(`
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>${errorMessage}
                    </div>
                `);
            }
        });
    }

    // Format input harga dengan thousand separator
    $('#harga_perolehan_display').on('input', function() {
        // Format tampilan
        this.value = formatRupiahInput(this.value);
        
        // Simpan nilai numerik murni di hidden field
        const numericValue = this.value.replace(/\./g, '');
        $('#harga_perolehan').val(numericValue);
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

    // Form validation sebelum submit
    $('#inventarisForm').submit(function(e) {
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
    }
});
</script>
@endsection

@section('styles')
<style>





.steps-progress {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 0 auto;
    max-width: 800px;
    position: relative;
}

.steps-progress::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 4px;
    background-color: #e9ecef;
    z-index: 1;
    transform: translateY(-50%);
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 8px;
    transition: all 0.3s ease;
}

.step.active .step-number {
    background-color: #0d6efd;
    color: white;
}

.step-label {
    font-size: 0.875rem;
    color: #6c757d;
    text-align: center;
}

.step.active .step-label {
    color: #0d6efd;
    font-weight: 500;
}







.kategori-options {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
}

.kategori-options .form-check {
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 0;
    transition: all 0.2s ease-in-out;
    border-radius: 0.375rem;
}

.kategori-options .form-check:last-child {
    border-bottom: none;
}

.kategori-options .form-check:hover {
    background-color: #e7f1ff;
    transform: translateY(-2px);
}

.kategori-options .form-check-input {
    width: 1.25em;
    height: 1.25em;
    margin-top: 0.25em;
}

.kategori-options .form-check-label {
    cursor: pointer;
    width: 100%;
    padding: 0.5rem 0;
}

.kategori-options .form-check-input:checked ~ .form-check-label {
    color: var(--bs-primary);
    font-weight: 500;
}

.kategori-options .form-check:has(.form-check-input:checked) {
    background-color: #e7f1ff;
    border: 1px solid var(--bs-primary);
}

.card-form {
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0.5rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    transition: all 0.15s ease-in-out;
}

.card-form:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.card-header {
    border-radius: 0.5rem 0.5rem 0 0 !important;
}

.text-danger {
    font-size: 0.875em;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-control:focus,
.form-select:focus {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.btn-primary:hover {
    background-color: #0b5ed7;
    border-color: #0a58ca;
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.spinner-border {
    width: 2rem;
    height: 2rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .steps-progress {
        padding: 0 20px;
    }
    
    .step-number {
        width: 35px;
        height: 35px;
        font-size: 0.9rem;
    }
    
    .step-label {
        font-size: 0.75rem;
    }
    
    .kategori-options .form-check-label .fa-2x {
        font-size: 1.5em;
    }
}

/* Loading animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.step-content {
    animation: fadeIn 0.3s ease-in-out;
}

.numeric-input {
    text-align: right;
}
</style>
@endsection