<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Perintah Kerja (SPK) #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page { margin: 40px 50px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #1f2937; line-height: 1.5; }
        
        /* Header */
        .header-table { width: 100%; border-bottom: 2px solid #ea580c; padding-bottom: 15px; margin-bottom: 25px; }
        .company-name { font-size: 22px; font-weight: bold; color: #ea580c; text-transform: uppercase; margin: 0; }
        .doc-title { font-size: 20px; font-weight: bold; color: #111827; letter-spacing: 1px; margin-bottom: 2px; text-transform: uppercase; }
        .doc-no { font-size: 13px; color: #6b7280; font-weight: bold; }
        
        /* Box / Panel style */
        .panel { background-color: #fff; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 20px; }
        .panel-header { background-color: #f9fafb; border-bottom: 1px solid #e5e7eb; padding: 10px 15px; font-weight: bold; color: #374151; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        .panel-body { padding: 15px; }
        
        /* Two columns */
        .row-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .row-table td { vertical-align: top; }
        .col-left { width: 48%; padding-right: 2%; }
        .col-right { width: 48%; padding-left: 2%; }
        
        /* Info Tables (Key-Value) */
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 6px 0; border-bottom: 1px dashed #f3f4f6; }
        .info-table tr:last-child td { border-bottom: none; }
        .info-table .key { width: 40%; color: #6b7280; font-size: 12px; }
        .info-table .val { width: 60%; font-weight: bold; color: #111827; }
        
        /* Data Tables */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table th, .data-table td { padding: 10px 15px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .data-table th { background-color: #f9fafb; font-size: 11px; text-transform: uppercase; color: #6b7280; letter-spacing: 0.5px; border-bottom: 2px solid #e5e7eb; }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table .text-center { text-align: center; }
        
        /* Highlight items */
        .highlight-pax { font-size: 16px; color: #ea580c; font-weight: bold; }
        .pic-badge { display: inline-block; background-color: #ea580c; color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; margin-left: 5px; font-weight: normal; }
        
        /* Signatures */
        .signatures { width: 100%; margin-top: 50px; border-collapse: collapse; text-align: center; }
        .signatures td { width: 33.33%; padding-bottom: 80px; vertical-align: top; font-size: 12px; color: #4b5563; }
        .sig-line { border-bottom: 1px solid #111827; width: 70%; margin: 60px auto 5px auto; }
        
        #watermark {
            position: fixed;
            top: 25%;
            left: 15%;
            width: 70%;
            opacity: 0.12;
            z-index: 100;
            text-align: center;
        }
        #watermark img {
            max-width: 100%;
            max-height: 500px;
        }
    </style>
</head>
<body>
    @php
        $company = \App\Models\CompanyProfile::first();
        $logoPath = null;
        if ($company && $company->logo) {
            $publicPath = storage_path('app/public/' . $company->logo);
            $privatePath = storage_path('app/private/' . $company->logo);
            if (file_exists($publicPath)) {
                $logoPath = $publicPath;
            } elseif (file_exists($privatePath)) {
                $logoPath = $privatePath;
            }
        }
        $logoBase64 = '';
        if ($logoPath && file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($logoData);
        }
    @endphp

    @if($logoBase64)
        <div id="watermark">
            <img src="{{ $logoBase64 }}" alt="Watermark">
        </div>
    @endif

    <table class="header-table">
        <tr>
            <td width="60%">
                <h1 class="company-name">{{ $company->nama_bisnis ?? 'BU NARDI CATERING' }}</h1>
            </td>
            <td width="40%" style="text-align: right; vertical-align: bottom;">
                <div class="doc-title">Surat Perintah Kerja</div>
                <div class="doc-no">No: SPK-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }} / {{ \Carbon\Carbon::parse($order->created_at)->format('m/Y') }}</div>
            </td>
        </tr>
    </table>

    <table class="row-table">
        <tr>
            <!-- Kolom Kiri: Klien & Acara -->
            <td class="col-left">
                <div class="panel">
                    <div class="panel-header">Informasi Acara</div>
                    <div class="panel-body">
                        <table class="info-table">
                            <tr>
                                <td class="key">Klien</td>
                                <td class="val">{{ $order->client->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="key">Kontak (Telp/WA)</td>
                                <td class="val">{{ $order->client->no_telp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="key">Tanggal Acara</td>
                                <td class="val" style="color: #ea580c;">{{ \Carbon\Carbon::parse($order->tanggal_acara)->translatedFormat('l, d F Y') }}</td>
                            </tr>
                            <tr>
                                <td class="key">Lokasi</td>
                                <td class="val">{{ $order->lokasi ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
            
            <!-- Kolom Kanan: Detail Pesanan -->
            <td class="col-right">
                <div class="panel">
                    <div class="panel-header">Detail Pesanan Utama</div>
                    <div class="panel-body">
                        <table class="info-table">
                            <tr>
                                <td class="key">Paket Menu</td>
                                <td class="val">{{ $order->paket_menu ?? 'Paket Kustom' }}</td>
                            </tr>
                            <tr>
                                <td class="key">Jumlah Porsi</td>
                                <td class="val highlight-pax">{{ number_format($order->jumlah_pax, 0, ',', '.') }} Pax</td>
                            </tr>
                            <tr>
                                <td class="key">Status Order</td>
                                <td class="val">
                                    <span style="background-color: #f3f4f6; padding: 2px 8px; border-radius: 4px;">{{ $order->status }}</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <!-- Tim Bertugas -->
    <div class="panel">
        <div class="panel-header">Tim Bertugas & Penanggung Jawab</div>
        @if($order->assignments && $order->assignments->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Kru / Karyawan</th>
                        <th>Divisi / Peran</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->assignments as $assignment)
                    <tr>
                        <td style="font-weight: bold; color: {{ $assignment->is_pic ? '#ea580c' : '#1f2937' }};">
                            {{ $assignment->user->name ?? 'Anonim' }}
                            @if($assignment->is_pic)
                                <span class="pic-badge">PIC Utama</span>
                            @endif
                        </td>
                        <td>{{ $assignment->divisi }}</td>
                        <td class="text-center" style="color: {{ $assignment->is_pic ? '#ea580c' : '#6b7280' }}; font-weight: {{ $assignment->is_pic ? 'bold' : 'normal' }};">
                            {{ $assignment->is_pic ? 'Koordinator' : 'Anggota' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding: 15px; color: #9ca3af; font-style: italic; font-size: 12px;">
                Belum ada tim yang ditugaskan untuk acara ini.
            </div>
        @endif
    </div>

    <!-- Kebutuhan Peralatan -->
    <div class="panel">
        <div class="panel-header">Daftar Barang Bawaan (Peralatan)</div>
        @if($order->items && $order->items->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="75%">Nama Barang / Spesifikasi</th>
                        <th width="25%" class="text-center">Jumlah (Qty)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $orderItem)
                    <tr>
                        <td>{{ $orderItem->item->nama ?? 'Barang tidak diketahui' }}</td>
                        <td class="text-center" style="font-weight: bold; font-size: 14px;">{{ $orderItem->qty_rencana }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div style="padding: 15px; color: #9ca3af; font-style: italic; font-size: 12px;">
                Tidak ada daftar peralatan khusus yang dilampirkan.
            </div>
        @endif
    </div>

    <!-- Catatan Tambahan -->
    @if($order->catatan)
    <div class="panel">
        <div class="panel-header" style="background-color: #fff7ed; color: #c2410c; border-bottom-color: #ffedd5;">Catatan Tambahan Khusus</div>
        <div class="panel-body" style="font-style: italic;">
            {!! nl2br(e($order->catatan)) !!}
        </div>
    </div>
    @endif

    <div style="font-size: 11px; color: #9ca3af; margin-top: 10px;">
        Dokumen ini valid dan dicetak otomatis dari sistem manajemen Bu Nardi Catering pada {{ now()->format('d M Y, H:i') }}.
    </div>

    <table class="signatures">
        <tr>
            <td>
                Dibuat Oleh (Admin),
                <div class="sig-line"></div>
                <span style="font-size: 10px;">(Nama & Tanda Tangan)</span>
            </td>
            <td>
                Diperiksa Oleh (SPV),
                <div class="sig-line"></div>
                <span style="font-size: 10px;">(Nama & Tanda Tangan)</span>
            </td>
            <td>
                Diterima Oleh (PIC Lapangan),
                <div class="sig-line"></div>
                <span style="font-size: 10px;">(Nama & Tanda Tangan)</span>
            </td>
        </tr>
    </table>

</body>
</html>
