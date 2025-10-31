@extends('layouts.app')
@section('title', 'Riwayat Transaksi - Deva Laundry')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4 fw-bold text-primary">Riwayat Transaksi Laundry</h3>

    {{-- ✅ Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success shadow-sm rounded">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm rounded">{{ session('error') }}</div>
    @endif

    {{-- 🧾 Tabel Transaksi --}}
    <div class="table-responsive bg-white p-3 rounded-4 shadow-sm">
        <table class="table align-middle table-hover">
            <thead class="table-light border-bottom">
                <tr class="text-center">
                    <th style="width:5%">No</th>
                    <th style="width:20%">Nama Pelanggan</th>
                    <th style="width:25%">Layanan</th>
                    <th style="width:15%">Total Harga</th>
                    <th style="width:20%">Tanggal</th>
                    <th style="width:15%">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $key => $order)
                    <tr class="text-center">
                        <td>{{ $orders->firstItem() + $key }}</td>
                        <td class="fw-semibold">
                            {{ $order->customer_name ?? ($order->user->name ?? '-') }}
                        </td>
                        <td>
                            @forelse($order->services as $s)
                                <span class="badge bg-primary-subtle text-primary fw-normal px-3 py-2 m-1">
                                    {{ $s->title }}
                                </span>
                            @empty
                                <span class="text-muted fst-italic">Tidak ada layanan</span>
                            @endforelse
                        </td>
                        <td class="fw-semibold text-success">
                            Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}
                        </td>
                        <td>{{ $order->updated_at ? $order->updated_at->format('d M Y H:i') : '-' }}</td>
                        <td>
                            <span class="badge px-3 py-2 
                                @if($order->status === 'selesai') bg-success 
                                @elseif($order->status === 'proses') bg-info text-dark 
                                @else bg-warning text-dark @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                            Belum ada transaksi yang selesai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 📄 Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
