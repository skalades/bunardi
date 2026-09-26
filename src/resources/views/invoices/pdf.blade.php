<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #INV-{{ str_pad($invoice->id ?? $order->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        @page { margin: 40px 50px; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #374151; line-height: 1.5; }
        .header { margin-bottom: 40px; border-bottom: 2px solid #ea580c; padding-bottom: 20px; }
        .header table { width: 100%; border-collapse: collapse; }
        .company-name { font-size: 24px; color: #ea580c; font-weight: bold; text-transform: uppercase; margin: 0 0 5px 0; }
        .company-info { font-size: 12px; color: #6b7280; }
        .invoice-title { font-size: 32px; color: #111827; font-weight: bold; text-align: right; letter-spacing: 2px; margin: 0; }
        .invoice-meta { text-align: right; font-size: 13px; margin-top: 10px; }
        
        .info-section { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .info-section td { vertical-align: top; }
        .bill-to h3 { font-size: 11px; color: #9ca3af; text-transform: uppercase; margin: 0 0 5px 0; letter-spacing: 1px; }
        .bill-to p { margin: 0; font-size: 14px; color: #111827; font-weight: bold; }
        .bill-to .address { font-weight: normal; color: #4b5563; margin-top: 3px; }
        
        .event-info { background: #fff7ed; padding: 15px; border-radius: 8px; border-left: 4px solid #ea580c; }
        .event-info h3 { font-size: 11px; color: #ea580c; text-transform: uppercase; margin: 0 0 5px 0; letter-spacing: 1px; }
        .event-info table { width: 100%; font-size: 13px; }
        .event-info td { padding: 3px 0; }
        .event-info td:first-child { font-weight: bold; width: 100px; color: #4b5563; }

        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background-color: #f3f4f6; color: #374151; font-weight: bold; text-transform: uppercase; font-size: 11px; letter-spacing: 1px; padding: 12px 10px; border-bottom: 2px solid #d1d5db; text-align: left; }
        .items-table td { padding: 12px 10px; border-bottom: 1px solid #e5e7eb; }
        .items-table .text-right { text-align: right; }
        .items-table .text-center { text-align: center; }
        .items-table tbody tr:nth-child(even) { background-color: #f9fafb; }

        .summary-section { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        .summary-spacer { width: 50%; }
        .summary-table { width: 50%; border-collapse: collapse; float: right; }
        .summary-table td { padding: 8px 10px; border-bottom: 1px solid #f3f4f6; }
        .summary-table .label { font-weight: bold; color: #4b5563; }
        .summary-table .amount { text-align: right; font-weight: bold; color: #111827; }
        .summary-table tr.grand-total td { background-color: #ea580c; color: white; font-size: 16px; border-radius: 4px; padding: 12px 10px; }
        .summary-table tr.grand-total td.label { color: white; }
        .summary-table tr.grand-total td.amount { color: white; }
        
        .summary-table tr.payment td { color: #059669; }
        .summary-table tr.balance td { font-size: 15px; border-bottom: 2px solid #111827; }
        .summary-table tr.balance td.amount { color: #dc2626; }

        .status-badge { display: inline-block; padding: 6px 12px; font-weight: bold; font-size: 12px; border-radius: 4px; text-transform: uppercase; letter-spacing: 1px; }
        .status-lunas { background-color: #d1fae5; color: #065f46; border: 1px solid #34d399; }
        .status-draft { background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171; }
        .status-diproses { background-color: #fef3c7; color: #92400e; border: 1px solid #fbbf24; }

        .footer { position: fixed; bottom: -20px; left: 0; right: 0; font-size: 11px; color: #6b7280; text-align: center; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        .payment-info { background: #f9fafb; padding: 15px; border-radius: 8px; border: 1px solid #e5e7eb; width: 45%; float: left; }
        .payment-info h4 { margin: 0 0 10px 0; font-size: 12px; text-transform: uppercase; color: #374151; }
        .payment-info p { margin: 3px 0; font-size: 12px; }
        .clear { clear: both; }

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

    <div class="header">
        <table>
            <tr>
                <td width="60%">
                    <h1 class="company-name">{{ $company->nama_bisnis ?? 'BU NARDI CATERING & N7DECORATION' }}</h1>
                    <div class="company-info">
                        @if($company)
                            {!! nl2br(e($company->alamat)) !!}<br>
                            Kontak: {{ $company->kontak }}
                        @else
                            Jl. Contoh Alamat No. 123, Kota, Provinsi<br>
                            WhatsApp / Telp: 0812-3456-7890<br>
                            Email: info@bunardicatering.com
                        @endif
                    </div>
                </td>
                <td width="40%" class="text-right" style="text-align: right; vertical-align: top;">
                    <h2 class="invoice-title">INVOICE</h2>
                    <div class="invoice-meta">
                        <strong>#INV-{{ str_pad($invoice->id ?? $order->id, 5, '0', STR_PAD_LEFT) }}</strong><br>
                        Tanggal Terbit: {{ \Carbon\Carbon::parse($invoice->tanggal_terbit ?? now())->format('d F Y') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="info-section">
        <tr>
            <td width="50%" class="bill-to">
                <h3>Ditagihkan Kepada:</h3>
                <p>{{ $order->client->nama ?? 'Nama Klien Tidak Tersedia' }}</p>
                <div class="address">
                    {{ $order->client->alamat ?? '-' }}<br>
                    Telp: {{ $order->client->no_telp ?? '-' }}
                </div>
            </td>
            <td width="50%">
                <div class="event-info">
                    <h3>Informasi Acara</h3>
                    <table>
                        <tr>
                            <td>Tanggal</td>
                            <td>: {{ \Carbon\Carbon::parse($order->tanggal_acara)->translatedFormat('l, d F Y') }}</td>
                        </tr>
                        <tr>
                            <td>Lokasi</td>
                            <td>: {{ $order->lokasi ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="40%">Deskripsi Layanan</th>
                <th width="15%" class="text-center">Pax</th>
                <th width="20%" class="text-right">Harga Satuan</th>
                <th width="25%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <!-- Paket Utama -->
            <tr>
                <td>
                    <strong>{{ $order->paket_menu ?? 'Paket Catering (Custom)' }}</strong>
                </td>
                <td class="text-center">{{ number_format($order->jumlah_pax, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($order->harga_pax_deal, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($order->jumlah_pax * $order->harga_pax_deal, 0, ',', '.') }}</td>
            </tr>
            
            <!-- Biaya Tambahan (Jika Ada) -->
            @if($order->biaya_tambahan > 0)
            <tr>
                <td>
                    <strong>Biaya Tambahan</strong><br>
                    <span style="font-size: 11px; color: #6b7280;">Sesuai kesepakatan</span>
                </td>
                <td class="text-center">-</td>
                <td class="text-right">-</td>
                <td class="text-right">Rp {{ number_format($order->biaya_tambahan, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    @php
        $totalPaid = $invoice->payments ? $invoice->payments->sum('nominal') : 0;
        $balance = $order->grand_total - $totalPaid;
        $isLunas = $totalPaid >= $order->grand_total && $order->grand_total > 0;
    @endphp

    <div>
        <div class="payment-info">
            <h4>Informasi Pembayaran</h4>
            @if($company && $company->info_rekening)
                {!! nl2br(e($company->info_rekening)) !!}
            @else
                <p><strong>BCA:</strong> 1234 5678 90 a.n. Bu Nardi</p>
                <p><strong>Mandiri:</strong> 0987 6543 21 a.n. Bu Nardi</p>
            @endif
            <br>
            @if($isLunas)
                <div class="status-badge status-lunas">LUNAS</div>
            @else
                <div class="status-badge status-draft">BELUM LUNAS</div>
            @endif
        </div>

        <table class="summary-table">
            <tr>
                <td class="label">Subtotal</td>
                <td class="amount">Rp {{ number_format(($order->jumlah_pax * $order->harga_pax_deal) + $order->biaya_tambahan, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td class="label">GRAND TOTAL</td>
                <td class="amount">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
            </tr>
            
            @if($invoice->payments && $invoice->payments->count() > 0)
                <tr>
                    <td colspan="2" style="padding-top: 15px; font-weight: bold; color: #6b7280; font-size: 11px; text-transform: uppercase;">Riwayat Pembayaran</td>
                </tr>
                @foreach($invoice->payments as $payment)
                <tr class="payment">
                    <td class="label" style="font-weight: normal; padding-left: 20px;">
                        - {{ $payment->tipe }} ({{ \Carbon\Carbon::parse($payment->tanggal)->format('d/m/Y') }})
                    </td>
                    <td class="amount" style="font-weight: normal;">- Rp {{ number_format($payment->nominal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="payment">
                    <td class="label">Total Dibayar</td>
                    <td class="amount">- Rp {{ number_format($totalPaid, 0, ',', '.') }}</td>
                </tr>
            @endif
            
            <tr class="balance">
                <td class="label">SISA TAGIHAN</td>
                <td class="amount">Rp {{ number_format($balance > 0 ? $balance : 0, 0, ',', '.') }}</td>
            </tr>
        </table>
        <div class="clear"></div>
    </div>

    <div class="footer">
        {{ $company->catatan_invoice ?? 'Invoice ini dihasilkan secara otomatis oleh sistem. Terima kasih atas kepercayaan Anda menggunakan layanan kami.' }}
    </div>
</body>
</html>
