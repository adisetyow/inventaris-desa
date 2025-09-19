@php
    $detailAset = $detailAset ?? null;
    $kategori = $kategori ?? null;
@endphp

@if($kategori)
    <div class="row g-3">
        @switch($kategori->nama_kategori)
            @case('Peralatan Kantor')
                <div class="col-md-6">
                    <label for="merk" class="form-label">Merk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('merk') is-invalid @enderror" 
                        id="merk" name="merk" value="{{ old('merk', $detailAset->merk ?? '') }}" required>
                    @error('merk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="bahan" class="form-label">Bahan</label>
                    <input type="text" class="form-control @error('bahan') is-invalid @enderror" 
                        id="bahan" name="bahan" value="{{ old('bahan', $detailAset->bahan ?? '') }}">
                    @error('bahan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="tahun_perolehan" class="form-label">Tahun Perolehan</label>
                    <input type="number" class="form-control @error('tahun_perolehan') is-invalid @enderror" 
                        id="tahun_perolehan" name="tahun_perolehan" 
                        value="{{ old('tahun_perolehan', $detailAset->tahun_perolehan ?? '') }}" 
                        min="1900" max="{{ date('Y') }}">
                    @error('tahun_perolehan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="warna" class="form-label">Warna</label>
                    <input type="text" class="form-control @error('warna') is-invalid @enderror" 
                        id="warna" name="warna" value="{{ old('warna', $detailAset->warna ?? '') }}">
                    @error('warna')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="nomor_inventaris_internal" class="form-label">Nomor Inventaris Internal</label>
                    <input type="text" class="form-control @error('nomor_inventaris_internal') is-invalid @enderror" 
                        id="nomor_inventaris_internal" name="nomor_inventaris_internal" 
                        value="{{ old('nomor_inventaris_internal', $detailAset->nomor_inventaris_internal ?? '') }}">
                    @error('nomor_inventaris_internal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @break

            @case('Peralatan Komunikasi')
                <div class="col-md-6">
                    <label for="merk" class="form-label">Merk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('merk') is-invalid @enderror" 
                        id="merk" name="merk" value="{{ old('merk', $detailAset->merk ?? '') }}" required>
                    @error('merk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="jenis_peralatan" class="form-label">Jenis Peralatan <span class="text-danger">*</span></label>
                    <select class="form-select @error('jenis_peralatan') is-invalid @enderror" 
                        id="jenis_peralatan" name="jenis_peralatan" required>
                        <option value="">Pilih Jenis Peralatan</option>
                        <option value="Handy Talkie" {{ old('jenis_peralatan', $detailAset->jenis_peralatan ?? '') == 'Handy Talkie' ? 'selected' : '' }}>Handy Talkie</option>
                        <option value="Radio" {{ old('jenis_peralatan', $detailAset->jenis_peralatan ?? '') == 'Radio' ? 'selected' : '' }}>Radio</option>
                        <option value="Telepon" {{ old('jenis_peralatan', $detailAset->jenis_peralatan ?? '') == 'Telepon' ? 'selected' : '' }}>Telepon</option>
                        <option value="Lainnya" {{ old('jenis_peralatan', $detailAset->jenis_peralatan ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis_peralatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="frekuensi" class="form-label">Frekuensi (MHz)</label>
                    <input type="text" class="form-control @error('frekuensi') is-invalid @enderror" 
                        id="frekuensi" name="frekuensi" 
                        value="{{ old('frekuensi', $detailAset->frekuensi ?? '') }}">
                    @error('frekuensi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="serial_number" class="form-label">Serial Number</label>
                    <input type="text" class="form-control @error('serial_number') is-invalid @enderror" 
                        id="serial_number" name="serial_number" 
                        value="{{ old('serial_number', $detailAset->serial_number ?? '') }}">
                    @error('serial_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="tahun_perolehan" class="form-label">Tahun Perolehan</label>
                    <input type="number" class="form-control @error('tahun_perolehan') is-invalid @enderror" 
                        id="tahun_perolehan" name="tahun_perolehan" 
                        value="{{ old('tahun_perolehan', $detailAset->tahun_perolehan ?? '') }}" 
                        min="1900" max="{{ date('Y') }}">
                    @error('tahun_perolehan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @break

            @case('Kendaraan')
                <div class="col-md-6">
                    <label for="merk" class="form-label">Merk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('merk') is-invalid @enderror" 
                        id="merk" name="merk" value="{{ old('merk', $detailAset->merk ?? '') }}" required>
                    @error('merk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="jenis_kendaraan" class="form-label">Jenis Kendaraan <span class="text-danger">*</span></label>
                    <select class="form-select @error('jenis_kendaraan') is-invalid @enderror" 
                        id="jenis_kendaraan" name="jenis_kendaraan" required>
                        <option value="">Pilih Jenis Kendaraan</option>
                        <option value="Sepeda Motor" {{ old('jenis_kendaraan', $detailAset->jenis_kendaraan ?? '') == 'Sepeda Motor' ? 'selected' : '' }}>Sepeda Motor</option>
                        <option value="Mobil" {{ old('jenis_kendaraan', $detailAset->jenis_kendaraan ?? '') == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                        <option value="Truk" {{ old('jenis_kendaraan', $detailAset->jenis_kendaraan ?? '') == 'Truk' ? 'selected' : '' }}>Truk</option>
                        <option value="Lainnya" {{ old('jenis_kendaraan', $detailAset->jenis_kendaraan ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis_kendaraan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="nomor_polisi" class="form-label">Nomor Polisi</label>
                    <input type="text" class="form-control @error('nomor_polisi') is-invalid @enderror" 
                        id="nomor_polisi" name="nomor_polisi" 
                        value="{{ old('nomor_polisi', $detailAset->nomor_polisi ?? '') }}">
                    @error('nomor_polisi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="tahun_pembuatan" class="form-label">Tahun Pembuatan</label>
                    <input type="number" class="form-control @error('tahun_pembuatan') is-invalid @enderror" 
                        id="tahun_pembuatan" name="tahun_pembuatan" 
                        value="{{ old('tahun_pembuatan', $detailAset->tahun_pembuatan ?? '') }}" 
                        min="1900" max="{{ date('Y') }}">
                    @error('tahun_pembuatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="tahun_perolehan" class="form-label">Tahun Perolehan</label>
                    <input type="number" class="form-control @error('tahun_perolehan') is-invalid @enderror" 
                        id="tahun_perolehan" name="tahun_perolehan" 
                        value="{{ old('tahun_perolehan', $detailAset->tahun_perolehan ?? '') }}" 
                        min="1900" max="{{ date('Y') }}">
                    @error('tahun_perolehan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nomor_rangka" class="form-label">Nomor Rangka</label>
                    <input type="text" class="form-control @error('nomor_rangka') is-invalid @enderror" 
                        id="nomor_rangka" name="nomor_rangka" 
                        value="{{ old('nomor_rangka', $detailAset->nomor_rangka ?? '') }}">
                    @error('nomor_rangka')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nomor_mesin" class="form-label">Nomor Mesin</label>
                    <input type="text" class="form-control @error('nomor_mesin') is-invalid @enderror" 
                        id="nomor_mesin" name="nomor_mesin" 
                        value="{{ old('nomor_mesin', $detailAset->nomor_mesin ?? '') }}">
                    @error('nomor_mesin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @break

            @case('Bangunan')
                <div class="col-md-6">
                    <label for="jenis_bangunan" class="form-label">Jenis Bangunan <span class="text-danger">*</span></label>
                    <select class="form-select @error('jenis_bangunan') is-invalid @enderror" 
                        id="jenis_bangunan" name="jenis_bangunan" required>
                        <option value="">Pilih Jenis Bangunan</option>
                        <option value="Rumah" {{ old('jenis_bangunan', $detailAset->jenis_bangunan ?? '') == 'Rumah' ? 'selected' : '' }}>Rumah</option>
                        <option value="Kantor" {{ old('jenis_bangunan', $detailAset->jenis_bangunan ?? '') == 'Kantor' ? 'selected' : '' }}>Kantor</option>
                        <option value="Gudang" {{ old('jenis_bangunan', $detailAset->jenis_bangunan ?? '') == 'Gudang' ? 'selected' : '' }}>Gudang</option>
                        <option value="Lainnya" {{ old('jenis_bangunan', $detailAset->jenis_bangunan ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis_bangunan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="luas_bangunan" class="form-label">Luas Bangunan (m²) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('luas_bangunan') is-invalid @enderror" 
                        id="luas_bangunan" name="luas_bangunan" 
                        value="{{ old('luas_bangunan', $detailAset->luas_bangunan ?? '') }}" 
                        min="1" step="0.01" required>
                    @error('luas_bangunan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="tahun_dibangun" class="form-label">Tahun Dibangun</label>
                    <input type="number" class="form-control @error('tahun_dibangun') is-invalid @enderror" 
                        id="tahun_dibangun" name="tahun_dibangun" 
                        value="{{ old('tahun_dibangun', $detailAset->tahun_dibangun ?? '') }}" 
                        min="1900" max="{{ date('Y') }}">
                    @error('tahun_dibangun')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="tahun_perolehan" class="form-label">Tahun Perolehan</label>
                    <input type="number" class="form-control @error('tahun_perolehan') is-invalid @enderror" 
                        id="tahun_perolehan" name="tahun_perolehan" 
                        value="{{ old('tahun_perolehan', $detailAset->tahun_perolehan ?? '') }}" 
                        min="1900" max="{{ date('Y') }}">
                    @error('tahun_perolehan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="jumlah_lantai" class="form-label">Jumlah Lantai</label>
                    <input type="number" class="form-control @error('jumlah_lantai') is-invalid @enderror" 
                        id="jumlah_lantai" name="jumlah_lantai" 
                        value="{{ old('jumlah_lantai', $detailAset->jumlah_lantai ?? '') }}" 
                        min="1">
                    @error('jumlah_lantai')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="alamat_bangunan" class="form-label">Alamat Bangunan</label>
                    <textarea class="form-control @error('alamat_bangunan') is-invalid @enderror" 
                        id="alamat_bangunan" name="alamat_bangunan" rows="2">{{ old('alamat_bangunan', $detailAset->alamat_bangunan ?? '') }}</textarea>
                    @error('alamat_bangunan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @break

            @case('Tanah')
                <div class="col-md-6">
                    <label for="luas_tanah" class="form-label">Luas Tanah (m²) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('luas_tanah') is-invalid @enderror" 
                        id="luas_tanah" name="luas_tanah" 
                        value="{{ old('luas_tanah', $detailAset->luas_tanah ?? '') }}" 
                        min="1" step="0.01" required>
                    @error('luas_tanah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="jenis_tanah" class="form-label">Jenis Tanah <span class="text-danger">*</span></label>
                    <select class="form-select @error('jenis_tanah') is-invalid @enderror" 
                        id="jenis_tanah" name="jenis_tanah" required>
                        <option value="">Pilih Jenis Tanah</option>
                        <option value="Tanah Darat" {{ old('jenis_tanah', $detailAset->jenis_tanah ?? '') == 'Tanah Darat' ? 'selected' : '' }}>Tanah Darat</option>
                        <option value="Tanah Sawah" {{ old('jenis_tanah', $detailAset->jenis_tanah ?? '') == 'Tanah Sawah' ? 'selected' : '' }}>Tanah Sawah</option>
                        <option value="Tanah Perkebunan" {{ old('jenis_tanah', $detailAset->jenis_tanah ?? '') == 'Tanah Perkebunan' ? 'selected' : '' }}>Tanah Perkebunan</option>
                        <option value="Lainnya" {{ old('jenis_tanah', $detailAset->jenis_tanah ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('jenis_tanah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="nomor_sertifikat" class="form-label">Nomor Sertifikat</label>
                    <input type="text" class="form-control @error('nomor_sertifikat') is-invalid @enderror" 
                        id="nomor_sertifikat" name="nomor_sertifikat" 
                        value="{{ old('nomor_sertifikat', $detailAset->nomor_sertifikat ?? '') }}">
                    @error('nomor_sertifikat')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="tahun_perolehan" class="form-label">Tahun Perolehan</label>
                    <input type="number" class="form-control @error('tahun_perolehan') is-invalid @enderror" 
                        id="tahun_perolehan" name="tahun_perolehan" 
                        value="{{ old('tahun_perolehan', $detailAset->tahun_perolehan ?? '') }}" 
                        min="1900" max="{{ date('Y') }}">
                    @error('tahun_perolehan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="alamat_tanah" class="form-label">Alamat Tanah</label>
                    <textarea class="form-control @error('alamat_tanah') is-invalid @enderror" 
                        id="alamat_tanah" name="alamat_tanah" rows="2">{{ old('alamat_tanah', $detailAset->alamat_tanah ?? '') }}</textarea>
                    @error('alamat_tanah')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                @break

            @default
                <!-- Default fields for other categories -->
                <div class="col-md-6">
                    <label for="nama_aset" class="form-label">Nama Aset <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama_aset') is-invalid @enderror" 
                        id="nama_aset" name="nama_aset" 
                        value="{{ old('nama_aset', $detailAset->nama_aset ?? '') }}" required>
                    @error('nama_aset')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="merk" class="form-label">Merk</label>
                    <input type="text" class="form-control @error('merk') is-invalid @enderror" 
                        id="merk" name="merk" value="{{ old('merk', $detailAset->merk ?? '') }}">
                    @error('merk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="tahun_perolehan" class="form-label">Tahun Perolehan</label>
                    <input type="number" class="form-control @error('tahun_perolehan') is-invalid @enderror" 
                        id="tahun_perolehan" name="tahun_perolehan" 
                        value="{{ old('tahun_perolehan', $detailAset->tahun_perolehan ?? '') }}" 
                        min="1900" max="{{ date('Y') }}">
                    @error('tahun_perolehan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                        id="deskripsi" name="deskripsi" rows="2">{{ old('deskripsi', $detailAset->deskripsi ?? '') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
        @endswitch
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>Tidak ada detail spesifik untuk kategori ini
    </div>
@endif