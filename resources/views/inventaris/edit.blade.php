@extends('layouts.app')

@section('title', 'Edit Inventaris - Sistem Inventaris Desa')

@section('content')
    <div class="container-fluid" data-detail-aset="{{ json_encode($detailDataForView ?? []) }}"
        data-inventaris-kategori="{{ $inventaris->kategori_id }}">
        <!-- Header dengan Breadcrumb dan Tombol Kembali -->
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h1 class="page-title">
                        <i class="fas fa-edit me-2"></i>Edit Inventaris
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('inventaris.index') }}">Inventaris</a></li>
                            <li class="breadcrumb-itemactive" aria-current="page">Edit</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-auto">
                    <a href="{{ route('inventaris.show', $inventaris->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Alert untuk Error/Success -->
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

        <!-- Form Edit Inventaris -->
        <form method="POST" action="{{ route('inventaris.update', $inventaris->id) }}" enctype="multipart/form-data" id="inventarisForm">
            @csrf
            @method('PUT')

            <!-- Card Informasi Umum -->
            <div class="card card-form mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Umum
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Informasi Kategori (Hanya Tampilan) -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Jenis Inventaris</label>
                            <div class="bg-light p-3 rounded">
                                @php
                                    $currentKategori = $kategoris->firstWhere('id', $inventaris->kategori_id);
                                @endphp
                                @if($currentKategori)
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas {{ $currentKategori->icon ?? 'fa-cube' }} fa-fw text-primary fa-2x"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $currentKategori->nama_kategori }}</div>
                                            <small class="text-muted">{{ $currentKategori->deskripsi ?? 'Kategori ' . $currentKategori->nama_kategori }}</small>
                                        </div>
                                    </div>
                                    <input type="hidden" name="kategori_id" value="{{ $inventaris->kategori_id }}">
                                @else
                                    <div class="text-muted">Kategori tidak ditemukan</div>
                                @endif
                            </div>
                        </div>

                        <!-- Field Umum -->
                        <div class="col-md-6">
                            <label for="nama_barang" class="form-label">Nama Barang/Aset <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_barang') is-invalid @enderror" 
                                id="nama_barang" name="nama_barang"
                                value="{{ old('nama_barang', $inventaris->nama_barang) }}" required>
                            @error('nama_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="kode_inventaris" class="form-label">Kode Inventaris</label>
                            <div class="input-group">
                                <input type="text" class="form-control bg-light @error('kode_inventaris') is-invalid @enderror" 
                                    id="kode_inventaris" name="kode_inventaris"
                                    value="{{ old('kode_inventaris', $inventaris->kode_inventaris) }}" readonly>
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-lock text-muted" title="Kode inventaris tidak dapat diubah"></i>
                                </span>
                                @error('kode_inventaris')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Kode inventaris tidak dapat diubah</small>
                        </div>

                        <div class="col-md-4">
                            <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                                id="jumlah" name="jumlah" min="1"
                                value="{{ old('jumlah', $inventaris->jumlah) }}" required>
                            @error('jumlah')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="kondisi" class="form-label">Kondisi <span class="text-danger">*</span></label>
                            <select class="form-select @error('kondisi') is-invalid @enderror" id="kondisi" name="kondisi" required>
                                <option value="">Pilih Kondisi</option>
                                <option value="Baik" {{ old('kondisi', $inventaris->kondisi) == 'Baik' ? 'selected' : '' }}>Baik</option>
                                <option value="Rusak" {{ old('kondisi', $inventaris->kondisi) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                <option value="Perlu Perbaikan" {{ old('kondisi', $inventaris->kondisi) == 'Perlu Perbaikan' ? 'selected' : '' }}>Perlu Perbaikan</option>
                                <option value="Hilang" {{ old('kondisi', $inventaris->kondisi) == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                            </select>
                            @error('kondisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="lokasi_penempatan" class="form-label">Lokasi Penempatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('lokasi_penempatan') is-invalid @enderror" 
                                id="lokasi_penempatan" name="lokasi_penempatan"
                                value="{{ old('lokasi_penempatan', $inventaris->lokasi_penempatan) }}" required>
                            @error('lokasi_penempatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="tanggal_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                                id="tanggal_masuk" name="tanggal_masuk" max="{{ date('Y-m-d') }}"
                                value="{{ old('tanggal_masuk', $inventaris->tanggal_masuk->format('Y-m-d')) }}" required>
                            @error('tanggal_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="sumber_dana" class="form-label">Sumber Dana <span class="text-danger">*</span></label>
                            <select class="form-select @error('sumber_dana') is-invalid @enderror" id="sumber_dana" name="sumber_dana" required>
                                <option value="">Pilih Sumber Dana</option>
                                <option value="APBN" {{ old('sumber_dana', $inventaris->sumber_dana) == 'APBN' ? 'selected' : '' }}>APBN</option>
                                <option value="APBD" {{ old('sumber_dana', $inventaris->sumber_dana) == 'APBD' ? 'selected' : '' }}>APBD</option>
                                <option value="ADD" {{ old('sumber_dana', $inventaris->sumber_dana) == 'ADD' ? 'selected' : '' }}>ADD (Alokasi Dana Desa)</option>
                                <option value="DD" {{ old('sumber_dana', $inventaris->sumber_dana) == 'DD' ? 'selected' : '' }}>DD (Dana Desa)</option>
                                <option value="Swadaya" {{ old('sumber_dana', $inventaris->sumber_dana) == 'Swadaya' ? 'selected' : '' }}>Swadaya</option>
                                <option value="Bantuan" {{ old('sumber_dana', $inventaris->sumber_dana) == 'Bantuan' ? 'selected' : '' }}>Bantuan</option>
                                <option value="Lainnya" {{ old('sumber_dana', $inventaris->sumber_dana) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
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
                                    id="harga_perolehan_display" name="harga_perolehan_display" 
                                    value="{{ old('harga_perolehan', $inventaris->harga_perolehan) ? number_format(old('harga_perolehan', $inventaris->harga_perolehan), 0, ',', '.') : '' }}" 
                                    required>
                                <input type="hidden" id="harga_perolehan" name="harga_perolehan" value="{{ old('harga_perolehan', $inventaris->harga_perolehan) }}">
                                @error('harga_perolehan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Contoh: 1.500.000</small>
                        </div>

                        <div class="col-md-4">
                            <label for="masa_pakai_tahun" class="form-label">Masa Pakai (Tahun)</label>
                            <input type="number" class="form-control @error('masa_pakai_tahun') is-invalid @enderror" 
                                id="masa_pakai_tahun" name="masa_pakai_tahun" min="1"
                                value="{{ old('masa_pakai_tahun', $inventaris->masa_pakai_tahun) }}">
                            @error('masa_pakai_tahun')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Opsional - Perkiraan masa pakai dalam tahun</small>
                        </div>

                        <div class="col-md-8">
                            <label for="deskripsi" class="form-label">Deskripsi/Keterangan</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                id="deskripsi" name="deskripsi" rows="2">{{ old('deskripsi', $inventaris->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Detail Spesifik -->
            <div class="card card-form mb-4" id="card-detail-spesifik">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list-alt me-2"></i>Detail Spesifik
                    </h5>
                </div>
                <div class="card-body" id="detail-kategori">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Memuat detail spesifik inventaris...
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('inventaris.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary" id="btnSubmit">
                    <i class="fas fa-save me-2"></i>Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
    $(document).ready(function() {
        // Ambil data dari data attribute
        const container = $('.container-fluid');
        const detailAsetData = JSON.parse(container.attr('data-detail-aset') || '{}');
        const kategoriId = container.attr('data-inventaris-kategori');

        // Fungsi untuk memuat detail kategori saat halaman dimuat
        function loadInitialDetail() {
            if (kategoriId) {
                loadKategoriDetail(kategoriId, function() {
                    // Populate detail spesifik fields with existing data
                    populateDetailFields();
                });
            }
        }

        // Fungsi untuk mengisi nilai field detail
        function populateDetailFields() {
            for (const fieldName in detailAsetData) {
                if (detailAsetData.hasOwnProperty(fieldName)) {
                    const value = detailAsetData[fieldName];
                    const inputField = $('#' + fieldName);
                    if (inputField.length) {
                        if (inputField.is('select')) {
                            inputField.val(value);
                        } else if (inputField.is('textarea')) {
                            inputField.val(value);
                        } else {
                            inputField.val(value);
                        }
                    }
                }
            }
        }

        // Panggil saat halaman siap
        loadInitialDetail();

        function loadKategoriDetail(kategoriId, callback = null) {
            const detailDiv = $('#detail-kategori');
            const cardDetail = $('#card-detail-spesifik');

            if (!kategoriId) {
                detailDiv.html(`
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>Tidak ada detail spesifik untuk inventaris ini
                    </div>
                `);
                return;
            }

            detailDiv.html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 mb-0">Memuat detail kategori...</p>
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
                        if (typeof callback === 'function') setTimeout(callback, 0);
                        return;
                    }

                    if (!data.fields || Object.keys(data.fields).length === 0) {
                        detailDiv.html(`
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>Tidak ada detail spesifik untuk kategori ini
                            </div>
                        `);
                        if (typeof callback === 'function') setTimeout(callback, 0);
                        return;
                    }

                    let html = '<div class="row g-3">';

                    // Generate form fields berdasarkan kategori
                    $.each(data.fields, function(fieldName, fieldConfig) {
                        const colClass = fieldConfig.col ? `col-md-${fieldConfig.col}` : 'col-md-6';
                        const required = fieldConfig.required ? 'required' : '';
                        const requiredMark = fieldConfig.required ? ' <span class="text-danger">*</span>' : '';

                        html += `<div class="${colClass}">`;
                        html += `<label for="${fieldName}" class="form-label">${fieldConfig.label}${requiredMark}</label>`;

                        if (fieldConfig.type === 'textarea') {
                            const value = detailAsetData[fieldName] || '';
                            html += `<textarea class="form-control" id="${fieldName}" name="${fieldName}"
                                    rows="3" placeholder="${fieldConfig.placeholder || ''}" ${required}>${value}</textarea>`;
                        } else if (fieldConfig.type === 'select' && fieldConfig.options) {
                            html += `<select class="form-select" id="${fieldName}" name="${fieldName}" ${required}>`;
                            html += `<option value="">Pilih ${fieldConfig.label}</option>`;

                            for (const key in fieldConfig.options) {
                                if (fieldConfig.options.hasOwnProperty(key)) {
                                    const value = fieldConfig.options[key];
                                    const selectedValue = detailAsetData[fieldName] || '';
                                    const isSelected = (String(selectedValue) === String(key)) ? ' selected' : '';
                                    html += '<option value="' + key + '"' + isSelected + '>' + value + '</option>';
                                }
                            }
                            html += `</select>`;
                        } else {
                            const step = fieldConfig.step ? `step="${fieldConfig.step}"` : '';
                            const min = fieldConfig.min ? `min="${fieldConfig.min}"` : '';
                            const max = fieldConfig.max ? `max="${fieldConfig.max}"` : '';
                            const placeholder = fieldConfig.placeholder ? `placeholder="${fieldConfig.placeholder}"` : '';
                            const value = detailAsetData[fieldName] || '';

                            html += '<input type="' + fieldConfig.type + '" class="form-control" id="' + fieldName + '" name="' + fieldName + '" value="' + value + '" ' + step + ' ' + min + ' ' + max + ' ' + placeholder + ' ' + required + '>';
                        }

                        if (fieldConfig.help) {
                            html += `<small class="form-text text-muted">${fieldConfig.help}</small>`;
                        }

                        html += `</div>`;
                    });

                    html += '</div>';
                    detailDiv.html(html);

                    if (typeof callback === 'function') {
                        setTimeout(callback, 0);
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan saat memuat detail kategori';
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

                    if (typeof callback === 'function') setTimeout(callback, 0);
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
            const tanggalMasuk = $('#tanggal_masuk').val();
            const today = new Date().toISOString().split('T')[0];

            // Validasi tanggal tidak boleh lebih besar dari hari ini
            if (tanggalMasuk > today) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Tanggal masuk tidak boleh lebih besar dari hari ini'
                });
                $('#tanggal_masuk').focus();
                return false;
            }

            const namaBarang = $('#nama_barang').val().trim();

            if (!namaBarang) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Nama barang tidak boleh kosong'
                });
                $('#nama_barang').focus();
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

            // Disable submit button untuk mencegah double submission
            $('#btnSubmit').prop('disabled', true)
                           .html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');
            return true;
        });

        // Inisialisasi nilai harga jika ada old input
        const oldHarga = "{{ old('harga_perolehan', $inventaris->harga_perolehan) }}";
        if (oldHarga) {
            $('#harga_perolehan_display').val(formatRupiahInput(oldHarga));
            $('#harga_perolehan').val(oldHarga);
        }
    });
    </script>
@endsection

@section('styles')
<style> 
/* Gaya untuk Informasi Kategori */
.bg-light.rounded {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    padding: 1rem;
}

/* Gaya untuk input yang readonly */
.bg-light.input-group-text {
    background-color: #f8f9fa !important;
}

/* Gaya untuk Kategori Options */
.kategori-options {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
}
.kategori-options .form-check {
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 0;
    transition: all 0.2s ease-in-out;
}
.kategori-options .form-check:last-child {
    border-bottom: none;
}
.kategori-options .form-check:hover {
    background-color: #e7f1ff;
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
    border-color: var(--bs-primary);
}

/* Gaya untuk Card Form */
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

/* Gaya Umum */
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

/* Animasi untuk Card Detail */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
#card-detail-spesifik {
    animation: fadeIn 0.3s ease-in-out;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-header .row {
        flex-direction: column;
        gap: 1rem;
    }
    .page-header .col-auto {
        width: 100%;
    }
    .page-header .btn {
        width: 100%;
    }
}
</style>