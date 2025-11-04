<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Laundry - {{ $nota->customer_name }}</title>
    <style>
        @font-face {
            font-family: 'SF Pro Display';
            src: url('https://fonts.cdnfonts.com/css/sf-pro-display');
        }

        body {
            font-family: 'SF Pro Display', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #000;
            background: #fff;
        }

        /* =============================
           KONTAINER UTAMA NOTA
        ============================== */
        .nota-container {
            width: 100%;
            max-width: 10.5cm;
            min-height: auto;
            margin: 0 auto;
            border: 2px solid #007bff;
            border-radius: 10px;
            padding: 18px;
            box-sizing: border-box;
        }

        /* =============================
           HEADER
        ============================== */
        .header {
            text-align: center;
            border-bottom: 2px solid #007bff;
            padding-bottom: 6px;
            margin-bottom: 10px;
        }

        .header img {
            width: 60px;
            margin-bottom: 5px;
        }

        .header h2 {
            margin: 0;
            font-size: 1.2rem;
            color: #007bff;
            font-weight: bold;
        }

        .header p {
            font-size: 0.7rem;
            margin: 0;
            line-height: 1.2;
        }

        /* =============================
           INFO PELANGGAN
        ============================== */
        .info-table {
            width: 100%;
            font-size: 0.75rem;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        /* =============================
           TABEL ITEM
        ============================== */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.7rem;
            margin-top: 5px;
        }

        .items-table th {
            background: #007bff;
            color: #fff;
            text-align: center;
            padding: 4px;
            font-weight: 600;
            border: 1px solid #007bff;
        }

        .items-table td {
            border: 1px solid #007bff;
            padding: 4px;
            text-align: center;
        }

        .items-table td.text-left {
            text-align: left;
            padding-left: 6px;
        }

        /* =============================
           RINGKASAN TOTAL
        ============================== */
        .summary {
            margin-top: 10px;
            width: 100%;
            font-size: 0.75rem;
        }

        .summary td {
            padding: 3px 0;
        }

        .summary td:first-child {
            width: 60%;
        }

        /* =============================
           TANDA TANGAN
        ============================== */
        .signature {
            margin-top: 20px;
            text-align: right;
        }

        .signature p {
            margin: 2px 0;
            font-size: 0.75rem;
        }

        /* =============================
           FOOTER
        ============================== */
        .footer {
            margin-top: 20px;
            border-top: 1px solid #007bff;
            text-align: center;
            font-size: 0.7rem;
            color: #555;
            padding-top: 5px;
        }

        /* =============================
           PRINT & PAGE SETTINGS
        ============================== */
        @page {
            size: auto;
            margin: 0.5cm;
        }

        @media print {
            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .nota-container {
                border: 2px solid #007bff;
                border-radius: 10px;
                box-shadow: none;
                width: 100%;
                max-width: 10.5cm;
                page-break-inside: avoid;
            }
            .footer {
                page-break-after: avoid;
            }
        }
    </style>
</head>
<body>
<div class="nota-container">
    <!-- Header -->
    <div class="header">
        <img src="{{ public_path('images/header.png') }}" alt="Logo Laundry">
        <h2>DEVA LAUNDRY</h2>
        <p>Jl. Wisnu Marga No. Belayu, Pekan, Kec. Marga, Kabupaten Tabanan</p>
        <p>Telp: 085-338-148841 | Est. 2014</p>
    </div>

    <!-- Info Pelanggan -->
    <table class="info-table">
        <tr>
            <td><strong>Nama Pelanggan:</strong> {{ $nota->customer_name }}</td>
            <td><strong>Tgl Masuk:</strong> {{ \Carbon\Carbon::parse($nota->tgl_masuk)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>Alamat:</strong> {{ $nota->customer_address ?? '-' }}</td>
            <td><strong>Tgl Keluar:</strong>
                {{ $nota->tgl_keluar ? \Carbon\Carbon::parse($nota->tgl_keluar)->format('d/m/Y') : '-' }}
            </td>
        </tr>
    </table>

    <!-- Tabel Item -->
    <table class="items-table">
        <thead>
        <tr>
            <th>No</th>
            <th>Pelayanan</th>
            <th>Jumlah</th>
            <th>Harga (Rp)</th>
            <th>Total (Rp)</th>
        </tr>
        </thead>
        <tbody>
        @forelse($nota->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="text-left">{{ $item->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 0, ',', '.') }}</td>
                <td>{{ number_format($item->subtotal, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align:center; color:#666;">Belum ada item pelayanan.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <!-- Ringkasan Total -->
    <table class="summary">
        <tr>
            <td style="text-align: right;"><strong>Jumlah Total:</strong></td>
            <td><strong>Rp {{ number_format($nota->total, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td style="text-align: right;">Uang Muka:</td>
            <td>Rp {{ number_format($nota->uang_muka, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="text-align: right;">Sisa Pembayaran:</td>
            <td>Rp {{ number_format($nota->sisa, 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- Tanda Tangan -->
    <div class="signature">
        <p>Tabanan, {{ now()->format('d M Y') }}</p>
        <br><br>
        <p><strong>{{ auth()->user()->name }}</strong></p>
        <p><em>Kasir</em></p>
    </div>

    <!-- Footer -->
    <div class="footer">
        Terima kasih telah menggunakan layanan <strong>Deva Laundry</strong><br>
        Kami menjaga kualitas, kebersihan, dan ketepatan waktu cucian Anda 💙
    </div>
</div>
</body>
</html>
