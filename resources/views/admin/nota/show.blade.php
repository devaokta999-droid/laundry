@extends('layouts.app')

@section('content')
<style>
    /* 🌈 Font & Layout ala macOS */
    @import url('https://fonts.cdnfonts.com/css/sf-pro-display');

    body {
        font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        background: linear-gradient(180deg, #f5f6fa, #eef1f4);
        color: #2d3436;
    }

    h3, h5 {
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    .container {
        max-width: 850px;
    }

    .card {
        border: none;
        border-radius: 20px;
        backdrop-filter: blur(8px);
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .fw-bold.text-primary {
        color: #007aff !important;
    }

    /* 🩵 Table ala macOS */
    table.table {
        border-radius: 12px;
        overflow: hidden;
    }

    table thead {
        background-color: #f9fafc;
    }

    table th {
        font-weight: 600;
        color: #007aff;
        border-bottom: 2px solid #e0e6ef !important;
    }

    table td {
        vertical-align: middle !important;
        border-color: #f0f2f5 !important;
    }

    /* 💰 Total Section */
    .text-end p {
        margin: 4px 0;
        font-size: 15px;
    }

    .text-end strong {
        color: #007aff;
    }

    /* 🔘 Tombol ala macOS */
    .btn {
        border-radius: 10px;
        font-weight: 600;
        letter-spacing: -0.2px;
        padding: 10px 18px;
        transition: all 0.2s ease;
    }

    .btn-primary {
        background-color: #007aff;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 122, 255, 0.3);
    }

    .btn-primary:hover {
        background-color: #0066d6;
        box-shadow: 0 6px 14px rgba(0, 122, 255, 0.4);
    }

    .btn-secondary {
        background-color: #5856d6;
        border: none;
        box-shadow: 0 4px 12px rgba(88, 86, 214, 0.3);
    }

    .btn-secondary:hover {
        background-color: #4a48c6;
        box-shadow: 0 6px 14px rgba(88, 86, 214, 0.4);
    }

    .btn-outline-dark {
        border: 1.5px solid #ccc;
        color: #333;
    }

    .btn-outline-dark:hover {
        background-color: #f1f2f6;
        color: #000;
    }

    /* ❤️ Tombol Hapus */
    .btn-danger {
        background-color: #ff3b30;
        border: none;
        box-shadow: 0 4px 12px rgba(255, 59, 48, 0.3);
    }

    .btn-danger:hover {
        background-color: #e62e23;
        box-shadow: 0 6px 14px rgba(255, 59, 48, 0.4);
    }

    .text-center {
        margin-top: 1.5rem;
    }

    a.btn {
        transform: translateY(0);
    }

    a.btn:hover {
        transform: translateY(-2px);
    }
</style>

<div class="container mt-4">
    <h3 class="fw-bold text-primary mb-4 text-center">Detail Nota Laundry</h3>

    <!-- Informasi Pelanggan -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3 text-secondary">Informasi Pelanggan</h5>
            <p><strong>Nama:</strong> {{ $nota->customer_name }}</p>
            <p><strong>Alamat:</strong> {{ $nota->customer_address ?? '-' }}</p>
            <p><strong>Tanggal Keluar:</strong> 
                {{ $nota->tgl_keluar ? \Carbon\Carbon::parse($nota->tgl_keluar)->format('d/m/Y') : '-' }}
            </p>
            <p><strong>Dibuat Oleh:</strong> {{ $nota->user->name ?? '-' }}</p>
        </div>
    </div>

    <!-- Daftar Item -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3 text-secondary">Daftar Item</h5>
            <table class="table table-bordered align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Jenis Pakaian</th>
                        <th>Harga (Rp)</th>
                        <th>Jumlah</th>
                        <th>Subtotal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($nota->items as $i)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $i->item->name ?? '-' }}</td>
                            <td>{{ number_format($i->price, 0, ',', '.') }}</td>
                            <td>{{ $i->quantity }}</td>
                            <td>{{ number_format($i->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">Belum ada item pada nota ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="text-end mt-4">
                <p><strong>Total Banyak Pakaian:</strong> {{ $nota->items->sum('quantity') }}</p>
                <p><strong>Jumlah Total (Rp):</strong> {{ number_format($nota->total, 0, ',', '.') }}</p>
                <p><strong>Uang Muka (Rp):</strong> {{ number_format($nota->uang_muka, 0, ',', '.') }}</p>
                <p><strong>Sisa Pembayaran (Rp):</strong> {{ number_format($nota->sisa, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="text-center">
        <a href="{{ route('admin.nota.print', $nota->id) }}" class="btn btn-primary" target="_blank">Cetak PDF</a>
        <a href="{{ route('admin.nota.print_direct', $nota->id) }}" class="btn btn-secondary" target="_blank">Print Langsung</a>

        <!-- 🔥 Tombol Hapus dengan Konfirmasi -->
        <form action="{{ route('admin.nota.destroy', $nota->id) }}" method="POST" class="d-inline" 
              onsubmit="return confirm('Apakah kamu yakin ingin menghapus nota ini? Tindakan ini tidak bisa dibatalkan.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Hapus Nota</button>
        </form>

        <a href="{{ url()->previous() }}" class="btn btn-outline-dark">Kembali</a>
    </div>
</div>
@endsection
