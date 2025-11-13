@extends('layouts.app')

@section('content')
<style>
    /* 🎨 Semua style dibungkus agar hanya berlaku di halaman ini */
    .macos-nota {
        font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        background: linear-gradient(180deg, #f5f6fa, #eef1f4);
        color: #2d3436;
        padding-bottom: 40px;
    }

    .macos-nota h3,
    .macos-nota h5 {
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    .macos-nota .container {
        max-width: 850px;
    }

    /* 💎 Kartu ala macOS (Glassmorphism) */
    .macos-nota .card {
        border: none;
        border-radius: 18px;
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.75);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .macos-nota .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
    }

    .macos-nota .fw-bold.text-primary {
        color: #007aff !important;
    }

    /* 🩵 Table ala macOS */
    .macos-nota table.table {
        border-radius: 12px;
        overflow: hidden;
    }

    .macos-nota table thead {
        background-color: #f9fafc;
    }

    .macos-nota table th {
        font-weight: 600;
        color: #007aff;
        border-bottom: 2px solid #e0e6ef !important;
        font-size: 14px;
    }

    .macos-nota table td {
        vertical-align: middle !important;
        border-color: #f0f2f5 !important;
        font-size: 14px;
    }

    /* 💰 Total Section - jadi tabel */
    .macos-nota .total-table {
        width: 100%;
        margin-top: 25px;
        border-collapse: collapse;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }

    .macos-nota .total-table th,
    .macos-nota .total-table td {
        padding: 12px 16px;
        border: 1px solid #e6e9ef;
        font-size: 15px;
    }

    .macos-nota .total-table th {
        background-color: #f9fafc;
        color: #007aff;
        font-weight: 600;
        text-align: left;
    }

    .macos-nota .total-table td {
        text-align: right;
        color: #333;
    }

    .macos-nota .total-table tr:last-child td {
        font-weight: 700;
        color: #007aff;
    }

    /* 🔘 Tombol ala macOS Premium */
    .macos-nota .btn {
        border-radius: 12px;
        font-weight: 600;
        letter-spacing: -0.2px;
        padding: 10px 20px;
        transition: all 0.25s ease;
        border: none;
        color: #fff;
        box-shadow: 0 4px 14px rgba(0, 122, 255, 0.35);
    }

    /* Semua tombol utama disamakan biru premium */
    .macos-nota .btn-primary,
    .macos-nota .btn-secondary,
    .macos-nota .btn-danger {
        background-color: #007aff;
    }

    .macos-nota .btn-primary:hover,
    .macos-nota .btn-secondary:hover,
    .macos-nota .btn-danger:hover {
        background-color: #0066d6;
        box-shadow: 0 6px 18px rgba(0, 122, 255, 0.45);
        transform: translateY(-2px);
    }

    /* Tombol outline (kembali) */
    .macos-nota .btn-outline-dark {
        border: 1.5px solid #d0d3da;
        color: #333;
        background-color: transparent;
    }

    .macos-nota .btn-outline-dark:hover {
        background-color: #f1f2f6;
        color: #000;
    }

    .macos-nota .text-center {
        margin-top: 1.8rem;
    }

    .macos-nota a.btn {
        transform: translateY(0);
    }

    .macos-nota a.btn:hover {
        transform: translateY(-2px);
    }

    /* ✨ Responsif */
    @media (max-width: 768px) {
        .macos-nota .container {
            padding: 0 15px;
        }

        .macos-nota table th,
        .macos-nota table td,
        .macos-nota .total-table th,
        .macos-nota .total-table td {
            font-size: 13px;
        }

        .macos-nota .btn {
            padding: 8px 14px;
            font-size: 14px;
        }
    }
</style>

<!-- Seluruh halaman dibungkus agar style hanya berlaku di sini -->
<div class="macos-nota">
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

                <!-- 💎 Total Section dalam bentuk tabel -->
                <table class="total-table mt-4">
                    <tr>
                        <th>Total Banyak Pakaian</th>
                        <td>{{ $nota->items->sum('quantity') }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Total (Rp)</th>
                        <td>{{ number_format($nota->total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Uang Muka (Rp)</th>
                        <td>{{ number_format($nota->uang_muka, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Sisa Pembayaran (Rp)</th>
                        <td>{{ number_format($nota->sisa, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="text-center">
            <a href="{{ route('admin.nota.print', $nota->id) }}" class="btn btn-primary" target="_blank">Cetak PDF</a>
            <a href="{{ route('admin.nota.print_direct', $nota->id) }}" class="btn btn-primary" target="_blank">Print Langsung</a>

            <!-- 🔥 Tombol Hapus (warna biru juga) -->
            <form action="{{ route('admin.nota.destroy', $nota->id) }}" method="POST" class="d-inline" 
                  onsubmit="return confirm('Apakah kamu yakin ingin menghapus nota ini? Tindakan ini tidak bisa dibatalkan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-primary">Hapus Nota</button>
            </form>

            <a href="{{ url()->previous() }}" class="btn btn-outline-dark">Kembali</a>
        </div>
    </div>
</div>
@endsection
