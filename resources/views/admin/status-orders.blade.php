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
            <p class="mac-panel-subtitle">Pantau semua order dengan gaya Apple Pro Premium. Status mengikuti kolom database.</p>
        </div>
        <div class="mac-search">
            <input type="text" name="keyword" form="statusSearch" class="form-control mac-input" placeholder="Nama atau nomor telepon" value="{{ request('keyword') }}">
            <div class="mac-search-actions">
                <button form="statusSearch" type="submit" class="btn btn-primary mac-btn"> Cari </button>
                <a href="{{ route('admin.orders.status') }}" class="btn btn-outline-secondary mac-btn ms-2">Reset</a>
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
                                '==============================',
                                '',
                                'Nama   : ' . $order->customer_name,
                                'Kontak : ' . $safePhone,
                                'Alamat : ' . $safeAddress,
                                '',
                                'Layanan:',
                                $serviceBlock,
                                '',
                                'Status cucian : ' . $statusLabel,
                            ];
                            if ($amountLine) {
                                $messageLines[] = "";
                                $messageLines[] = $amountLine;
                            }
                            $messageLines[] = "";
                            $messageLines[] = "Terima kasih telah menggunakan Deva Laundry.";
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
@endsection
