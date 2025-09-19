@if($kategori && $detailAset)
    <div class="row g-3">
        @foreach($detailAset as $field => $value)
            @php
                $fieldConfig = [
                    'Peralatan Kantor' => [
                        'merk' => ['label' => 'Merk', 'type' => 'text'],
                        'bahan' => ['label' => 'Bahan', 'type' => 'text'],
                        'tahun_perolehan' => ['label' => 'Tahun Perolehan', 'type' => 'number'],
                        'warna' => ['label' => 'Warna', 'type' => 'text'],
                        'nomor_inventaris_internal' => ['label' => 'Nomor Inventaris Internal', 'type' => 'text']
                    ],
                // Tambahkan konfigurasi untuk kategori lainnya
                ][$kategori->nama_kategori][$field] ?? ['label' => ucfirst(str_replace('_', ' ', $field)), 'type' => 'text'];
            @endphp

            <div class="col-md-6">
                <label for="{{ $field }}" class="form-label">{{ $fieldConfig['label'] }}</label>
                @if($fieldConfig['type'] === 'textarea')
                    <textarea class="form-control" id="{{ $field }}" name="{{ $field }}"
                        rows="3">{{ old($field, $value) }}</textarea>
                @else
                    <input type="{{ $fieldConfig['type'] }}" class="form-control" id="{{ $field }}" name="{{ $field }}"
                        value="{{ old($field, $value) }}" @if(isset($fieldConfig['step'])) step="{{ $fieldConfig['step'] }}" @endif>
                @endif
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>Tidak ada detail spesifik untuk kategori ini
    </div>
@endif