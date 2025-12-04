@extends('layouts.app')

@section('title', 'Status Laundry - Admin')

@section('content')
<style>
    .mac-shell {
        padding: 60px 0;
        max-width: 1750px;
        margin: 0 auto;
    }
    .mac-panel {
        border-radius: 30px;
        padding: 32px;
        background: rgba(255,255,255,0.9);
        border: 1px solid rgba(255,255,255,0.5);
        box-shadow: 0 35px 80px rgba(15,23,42,0.15);
    }
    .mac-panel-gradient {
        background: linear-gradient(145deg, #f5f7ff, #e0e7ff);
    }
    .mac-panel-title {
        font-size: 2rem;
        font-weight: 800;
        color: #0e1c4a;
        margin: 0;
    }
    .mac-panel-subtitle {
        color: #5b6480;
        margin-bottom: 1rem;
    }
    .mac-search {
        margin-top: 1rem;
        display: flex;
        gap: 0.8rem;
    }
    .mac-select {
        min-width: 150px;
        max-width: 170px;
        padding: 8px 38px 8px 16px;
        font-size: 0.85rem;
        border-radius: 999px;
        border: 1px solid rgba(37, 99, 235, 0.15);
        background: radial-gradient(circle at 0 0, #ffffff, #eef2ff);
        box-shadow:
            0 10px 25px rgba(15,23,42,0.12),
            inset 0 1px 0 rgba(255,255,255,0.9);
        color: #0f172a;
        font-weight: 500;
        outline: none;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg width='16' height='16' viewBox='0 0 20 20' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='%23586588' d='M5.23 7.21a.75.75 0 011.06.02L10 11.177l3.71-3.946a.75.75 0 111.1 1.022l-4.25 4.52a.75.75 0 01-1.1 0l-4.25-4.52a.75.75 0 01.02-1.06z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: calc(100% - 14px) 50%;
        background-size: 14px;
    }
    .mac-select:hover {
        border-color: rgba(37, 99, 235, 0.3);
        box-shadow:
            0 14px 30px rgba(15,23,42,0.16),
            inset 0 1px 0 rgba(255,255,255,0.95);
    }
    .mac-select:focus {
        border-color: rgba(37, 99, 235, 0.7);
        box-shadow:
            0 0 0 1px rgba(191, 219, 254, 0.9),
            0 18px 40px rgba(15,23,42,0.22);
    }
    .mac-search-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .mac-btn {
        border-radius: 999px;
        padding: 10px 24px;
        font-weight: 600;
        transition: transform 0.2s ease;
    }
    .mac-btn-sm {
        border-radius: 999px;
        padding: 4px 14px;
    }
    .mac-input {
        border-radius: 999px;
        border: 1px solid rgba(15,23,42,0.1);
        padding: 10px 18px;
        box-shadow: inset 0 6px 18px rgba(15,23,42,0.06);
    }
    .mac-panel-card {
        margin-top: 0;
    }
    .mac-table {
        min-width: 900px;
    }
    .mac-table-header th {
        background: #f3f4f7;
    }
    .mac-badge {
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .mac-badge-success {
        background: #1d976c;
        color: #fff;
    }
    .mac-badge-info {
        background: #36b3ff;
        color: #0b172d;
    }
    .mac-service {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
    }
    .mac-total-lunas {
        color: #16a34a;
        font-weight: 700;
    }
</style>
<div class="mac-shell">
        <div class="mac-panel mac-panel-gradient mb-4">
        <div>
            <h1 class="mac-panel-title">Status Laundry Customer</h1>
            <p class="mac-panel-subtitle">Pantau semua order Status mengikuti kolom dari database.</p>
        </div>
        <div class="mac-search">
            <input
                type="text"
                name="keyword"
                form="statusSearch"
                class="form-control mac-input"
                placeholder="Nama atau nomor telepon"
                value="{{ request('keyword') }}">

            <select
                name="status"
                form="statusSearch"
                class="form-select mac-input mac-select">
                <option value="">Semua status</option>
                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="proses" {{ request('status') === 'proses' ? 'selected' : '' }}>Sedang diproses</option>
            </select>

            <div class="mac-search-actions">
                <button form="statusSearch" type="submit" class="btn btn-primary mac-btn"> Cari </button>
                <a href="{{ route('admin.orders.status') }}" class="btn btn-outline-secondary mac-btn">Reset</a>
            </div>
        </div>
        <form id="statusSearch" method="GET" class="d-none"></form>
    </div>

    <div class="mac-panel mac-panel-card shadow-sm">
        <div class="table-responsive">
            <table class="table mac-table align-middle mb-0">
                <thead class="mac-table-header text-uppercase small text-muted">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th>Layanan</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        @php
                            $isFinished = $order->status === 'selesai';
                            $statusLabel = $isFinished ? 'Selesai' : 'Sedang Diproses';
                            $badgeClass = $isFinished ? 'mac-badge-success' : 'mac-badge-info';
                            $serviceItems = collect(json_decode($order->items ?? '[]', true) ?: []);
                            $nota = $order->nota;
                            $formattedTotal = '-';
                            $amountLine = null;
                            $totalClass = '';
                            if ($nota) {
                                $sisa = (int) ($nota->sisa ?? 0);
                                if ($sisa <= 0) {
                                    $formattedTotal = 'Lunas';
                                    $amountLine = 'Status pembayaran: *LUNAS*';
                                    $totalClass = 'mac-total-lunas';
                                } else {
                                    $formattedTotal = 'Rp ' . number_format($sisa, 0, ',', '.');
                                    $amountLine = 'Total yang perlu dibayar: ' . $formattedTotal;
                                }
                            }
                            $cleanPhone = preg_replace('/\D+/', '', $order->customer_phone ?? '');
                            $waNumber = $cleanPhone;
                            if ($waNumber !== '') {
                                if (str_starts_with($waNumber, '0')) {
                                    $waNumber = '62' . substr($waNumber, 1);
                                } elseif (str_starts_with($waNumber, '8')) {
                                    $waNumber = '62' . $waNumber;
                                }
                            }

                            // Susun ringkas layanan untuk pesan WhatsApp
                            $serviceSummaryLines = [];
                            foreach ($serviceItems as $item) {
                                $title = $item['title'] ?? 'Layanan';
                                $desc = trim($item['description'] ?? '');
                                $serviceSummaryLines[] = $desc !== ''
                                    ? "- {$title} ({$desc})"
                                    : "- {$title}";
                            }
                            $serviceBlock = !empty($serviceSummaryLines)
                                ? implode("\n", $serviceSummaryLines)
                                : '- Tidak ada informasi layanan.';

                            $safePhone = $order->customer_phone ?? '-';
                            $safeAddress = $order->customer_address ?? '-';

                            $messageLines = [
                                'Deva Laundry - Status Pesanan',
                                '============================',
                                '',
                                'Nama   : ' . $order->customer_name,
                                'Kontak : ' . $safePhone,
                                'Alamat : ' . $safeAddress,
                                'Status cucian : ' . $statusLabel,
                            ];
                            if ($amountLine) {
                                $messageLines[] = "";
                                $messageLines[] = $amountLine;
                            }
                            $messageLines[] = "";
                            $messageLines[] = "Mau dicari ke OUTLET atau kami antar ke alamat tujuan Anda?";
                            $messageLines[] = "Terima kasih telah menggunakan jasa Deva Laundry.";
                            $message = implode("\n", array_filter($messageLines, function ($line) {
                                return $line !== '';
                            }));
                        @endphp
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->customer_phone ?? '-' }}</td>
                            <td>{{ $order->customer_address ?? '-' }}</td>
                                <td>
                                    @forelse($serviceItems as $item)
                                        <div class="mac-service flex-column align-items-start">
                                            <span class="fw-semibold">{{ $item['title'] ?? 'Layanan' }}</span>
                                            @if(!empty($item['description']))
                                                <small class="text-muted">{{ $item['description'] }}</small>
                                            @endif
                                        </div>
                                    @empty
                                        <span class="mac-service text-muted">Belum ada layanan</span>
                                    @endforelse
                                </td>
                            <td><span class="mac-badge {{ $badgeClass }}">{{ $statusLabel }}</span></td>
                            <td><span class="{{ $totalClass }}">{{ $formattedTotal }}</span></td>
                            <td>{{ \Illuminate\Support\Str::limit($order->notes ?? 'Tidak ada catatan', 60) }}</td>
                            <td>
                                @if($order->nota_id)
                                    <a href="{{ route('admin.nota.show', $order->nota_id) }}" class="btn btn-sm btn-outline-secondary mac-btn-sm mb-1">Buka Nota</a>
                                    @if($waNumber !== '')
                                        <a href="https://wa.me/{{ $waNumber }}?text={{ urlencode($message) }}" class="btn btn-sm btn-success mac-btn-sm">Kirim WA</a>
                                    @endif
                                @else
                                    <a href="{{ route('admin.orders.status.create_nota', $order->id) }}" class="btn btn-sm btn-primary mac-btn-sm">Buat Nota</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">Belum ada order.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 text-center">
        {{ $orders->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var statusSelect = document.querySelector('select[name="status"][form="statusSearch"]');
        var searchForm = document.getElementById('statusSearch');
        if (!statusSelect || !searchForm) return;

        statusSelect.addEventListener('change', function () {
            searchForm.submit();
        });
    });
</script>
@endsection
