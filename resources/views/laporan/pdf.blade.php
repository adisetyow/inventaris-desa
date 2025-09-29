<!DOCTYPE html>
<html>

<head>
    <title>Laporan Inventaris Desa Karangduren</title>
    <style>
        /* Mengatur font untuk seluruh dokumen */
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 25px;
            font-size: 10px;
        }

        /* Mengatur font khusus untuk kop surat jika tersedia di server */
        @font-face {
            font-family: 'Bookman Old Style';
            src: url('path/to/your/font/file.ttf');
            /* Ganti dengan path font jika Anda punya filenya */
            /* Jika tidak, kita akan gunakan fallback di style inline */
        }

        .kop-surat-table {
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
        }

        .logo {
            width: 80px;
        }

        .kop-surat-text {
            text-align: center;
            line-height: 1.2;
        }

        .kop-surat-text .pemkab {
            font-family: 'Bookman Old Style', 'Times New Roman', serif;
            font-size: 14pt;
            font-weight: bold;
        }

        .kop-surat-text .kecamatan {
            font-family: 'Bookman Old Style', 'Times New Roman', serif;
            font-size: 16pt;
            font-weight: bold;
        }

        .kop-surat-text .desa {
            font-family: 'Bookman Old Style', 'Times New Roman', serif;
            font-size: 18pt;
            font-weight: bold;
        }

        .kop-surat-text .alamat {
            font-family: 'Bookman Old Style', 'Times New Roman', serif;
            font-size: 8pt;
            font-weight: bold;
        }

        .report-title {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .report-title h2 {
            margin: 0;
            padding: 0;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .report-title p {
            margin: 0;
            padding: 0;
            font-size: 12px;
            text-transform: uppercase;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            font-size: 9px;
        }

        .table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .signature-section {
            margin-top: 40px;
            width: 100%;
        }

        .signature-box {
            width: 30%;
            float: right;
            text-align: center;
            font-size: 11px;
        }

        .signature-box .signature-space {
            height: 60px;
        }
    </style>
</head>

<body>

    {{-- KOP SURAT --}}
    <table class="kop-surat-table">
        <tr>
            <td style="width: 15%; text-align: center;">
                @php
                    // Menggunakan logo Base64 agar pasti ter-render
                    $logoPath = public_path('img/logo.JPG');
                    $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $logoData = file_get_contents($logoPath);
                    $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
                @endphp
                <img src="{{ $logoBase64 }}" alt="Logo" class="logo">
            </td>
            <td style="width: 85%; text-align: center;" class="kop-surat-text">
                <div class="pemkab">PEMERINTAH KABUPATEN SEMARANG</div>
                <div class="kecamatan">KECAMATAN TENGARAN</div>
                <div class="desa">DESA KARANGDUREN</div>
                <div class="alamat">Alamat : Jln Gemah Ripah 48 Karangduren 50775</div>
            </td>
        </tr>
    </table>

    {{-- JUDUL LAPORAN DINAMIS --}}
    <div class="report-title">
        <h2>Laporan Data Inventaris Aset</h2>
        @if (!empty($filters))
            <p>
                Berdasarkan:
                @if(isset($filters['kondisi']))
                    Kondisi "{{ $filters['kondisi'] }}"
                @elseif(isset($filters['lokasi']))
                    Lokasi "{{ $filters['lokasi'] }}"
                @elseif(isset($filters['bulan']) && isset($filters['tahun']))
                    Bulan {{ \Carbon\Carbon::create()->month($filters['bulan'])->translatedFormat('F') }} Tahun
                    {{ $filters['tahun'] }}
                @elseif(isset($filters['tahun']))
                    Tahun {{ $filters['tahun'] }}
                @else
                    Semua Kriteria
                @endif
            </p>
        @endif
    </div>

    {{-- TABEL DATA --}}
    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th class="text-center">Jumlah</th>
                <th>Kondisi</th>
                <th>Lokasi</th>
                <th>Tgl Masuk</th>
                <th class="text-end">Harga Satuan</th>
                <th class="text-end">Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventaris as $item)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $item->kode_inventaris }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                    <td class="text-center">{{ $item->jumlah }}</td>
                    <td>{{ $item->kondisi }}</td>
                    <td>{{ $item->lokasi_penempatan }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('d-m-Y') }}</td>
                    <td class="text-end">Rp {{ number_format($item->harga_perolehan, 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($item->total_nilai, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data untuk ditampilkan.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="9" class="text-end">Total Keseluruhan Nilai Aset:</th>
                <th class="text-end">Rp {{ number_format($totalNilaiAset, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    {{-- BAGIAN TANDA TANGAN --}}
    <div class="signature-section">
        <div class="signature-box">
            <div>Karangduren, {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</div>
            <div>Petugas Pencatatan</div>
            <div class="signature-space"></div>
            <div>(___________________)</div>
        </div>
    </div>

</body>

</html>