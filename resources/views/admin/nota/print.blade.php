<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Laundry - {{ $nota->customer_name }}</title>
    <style>
        @font-face {
            font-family: 'SF Pro Display';
            src: url('https://fonts.cdnfonts.com/css/sf-pro-display');
        }

        body {
            font-family: 'SF Pro Display', Arial, sans-serif;
            font-size: 11px;
            width: 80mm;
            margin: 0 auto;
            padding: 0;
            color: #000;
            background: #fff;
        }

        .nota-container {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px dashed #007bff;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #007bff;
            padding-bottom: 5px;
            margin-bottom: 6px;
        }
        .header img {
            width: 150px;
            height: auto;
            object-fit: contain;
            margin-bottom: 3px;
        }
        .header h2 {
            margin: 0;
            color: #007bff;
            font-size: 13px;
            font-weight: 800;
        }
        .header p {
            margin: 0;
            font-size: 10px;
            color: #333;
            line-height: 1.2;
        }

        .info {
            font-size: 10px;
            margin-bottom: 6px;
        }
        .info strong {
            color: #007bff;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        table.items th {
            border-bottom: 1px solid #007bff;
            text-align: left;
            padding: 3px 0;
            color: #007bff;
        }
        table.items td {
            padding: 3px 0;
            vertical-align: top;
        }
        td.qty, td.price, td.total {
            text-align: right;
            white-space: nowrap;
        }

        .total {
            border-top: 1px dashed #007bff;
            margin-top: 6px;
            padding-top: 4px;
            font-size: 10px;
        }
        .total td {
            padding: 2px 0;
        }
        .total .label {
            text-align: left;
        }
        .total .value {
            text-align: right;
        }

        .signature {
            margin-top: 10px;
            text-align: center;
            font-size: 10px;
        }
        .signature p {
            margin: 2px 0;
        }

        .footer {
            margin-top: 10px;
            border-top: 1px dashed #007bff;
            text-align: center;
            font-size: 9px;
            color: #333;
            padding-top: 4px;
            line-height: 1.4;
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }
        @media print {
            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .nota-container {
                border: none;
            }
        }
    </style>
</head>
<body>
<div class="nota-container">
    <div class="header">
        @php
            $logoPath = public_path('images/header.png');
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                $data = file_get_contents($logoPath);
                $logoBase64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
        @endphp
        <img src="{{ $logoBase64 }}" alt="Logo Laundry">
        <h2>DEVA LAUNDRY</h2>
        <p>Jl. Wisnu Marga No. Belayu, Tabanan</p>
        <p>Telp:+62 821-4703-7006</p>
        <p><strong>Est. 2014</strong></p>
    </div>

    <div class="info">
        @php
            $lastPayment = $nota->payments ? $nota->payments->sortByDesc('created_at')->first() : null;
        @endphp
        <p><strong>No. Nota:</strong> {{ $nota->no_nota ?? 'INV-' . str_pad($nota->id, 5, '0', STR_PAD_LEFT) }}</p>
        <p><strong>Nama:</strong> {{ $nota->customer_name }}</p>
        <p><strong>Alamat:</strong> {{ $nota->customer_address ?? '-' }}</p>
        <p><strong>Tgl Keluar:</strong> {{ $nota->tgl_keluar ? \Carbon\Carbon::parse($nota->tgl_keluar)->format('d/m/Y') : '-' }}</p>
        <p><strong>Kasir:</strong> {{ optional($nota->kasir)->name ?? optional($nota->user)->name ?? '-' }}</p>
        <p>
            <strong>Tgl & Jam Pembayaran:</strong>
            @if($lastPayment)
                {{ \Carbon\Carbon::parse($lastPayment->created_at)->format('d/m/Y H:i') }}
            @else
                -
            @endif
        </p>
    </div>

    <table class="items">
        <thead>
        <tr>
            <th style="width:5%;">No</th>
            <th style="width:45%;">Item</th>
            <th style="width:15%;" class="qty">Qty</th>
            <th style="width:15%;" class="price">Harga</th>
            <th style="width:20%;" class="total">Total</th>
        </tr>
        </thead>
        <tbody>
        @forelse($nota->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->item->name ?? '-' }}</td>
                <td class="qty">{{ $item->quantity }}</td>
                <td class="price">{{ number_format($item->price, 0, ',', '.') }}</td>
                <td class="total">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center; color:#777;">Tidak ada item.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    @php
        $diskonTotal = $nota->payments ? $nota->payments->sum('discount_amount') : 0;
        $originalTotal = $nota->items && $nota->items->count()
            ? $nota->items->sum('subtotal')
            : ($nota->total + $diskonTotal);
        $totalDibayar = $nota->payments ? $nota->payments->sum('amount') : 0;
        $kembalian = $nota->payments
            ? $nota->payments->reduce(function ($carry, $p) {
                $cash = (float) ($p->cash_given ?? 0);
                $applied = (float) ($p->amount ?? 0);
                return $carry + max(0, $cash - $applied);
            }, 0)
            : 0;
        $totalQty = $nota->items->sum('quantity');
    @endphp

    <table class="total" width="100%">
        <tr>
            <td class="label">Total Awal</td>
            <td class="value">Rp {{ number_format($originalTotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Diskon</td>
            <td class="value">Rp {{ number_format($diskonTotal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Total Setelah Diskon</td>
            <td class="value">Rp {{ number_format($nota->total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Total Dibayar</td>
            <td class="value">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Kembalian</td>
            <td class="value">Rp {{ number_format($kembalian, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Sisa</td>
            <td class="value">Rp {{ number_format($nota->sisa, 0, ',', '.') }}</td>
        </tr>
        <tr style="border-top:1px dashed #007bff;">
            <td class="label"><strong>Total Item</strong></td>
            <td class="value"><strong>{{ $totalQty }} Pcs</strong></td>
        </tr>
    </table>

    <div class="signature">
        <p>Tabanan, Belayu {{ now()->format('d M Y') }}</p>
        <br>
        <p><strong>DEVA LAUNDRY</strong></p>
    </div>

    <div class="footer">
        Terima kasih telah menggunakan <strong>DEVA LAUNDRY</strong><br>
        Kebersihan & Ketepatan Waktu adalah Prioritas Kami.<br>
        <small>Jangan lupa bawa nota ini saat pengambilan cucian.</small>
    </div>
</div>

@if (!request()->has('pdf'))
<script>
    window.onload = function() {
        window.print();
    };
</script>
@endif

</body>
</html>
