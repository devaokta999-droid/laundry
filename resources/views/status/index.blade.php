@extends('layouts.app')

@section('title', 'Status Laundry - Deva Laundry')

@section('content')
<style>
    .mac-shell-public {
        padding: 60px 0;
        max-width: 1750px;
        margin: 0 auto;
    }
    .mac-panel-public {
        border-radius: 30px;
        padding: 28px 30px;
        background: linear-gradient(145deg, #f5f7ff, #e0e7ff);
        border: 1px solid rgba(255,255,255,0.7);
        box-shadow: 0 32px 70px rgba(15,23,42,0.16);
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .mac-panel-title {
        font-size: 2rem;
        font-weight: 800;
        color: #0e1c4a;
        margin: 0;
    }
    .mac-panel-subtitle {
        color: #5b6480;
        margin: 4px 0 0;
    }
    .mac-search-public {
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
    }
    .mac-search-public .mac-input {
        border-radius: 999px;
        border: 1px solid rgba(15,23,42,0.12);
        padding: 10px 18px;
        box-shadow: inset 0 6px 18px rgba(15,23,42,0.06);
        width: 100%;
    }
    .mac-search-actions {
        display: flex;
        gap: 0.6rem;
        min-width: 220px;
    }
    .mac-btn {
        border-radius: 999px;
        padding: 10px 24px;
        font-weight: 600;
    }
    .mac-panel-card-public {
        margin-top: 22px;
        border-radius: 26px;
        padding: 0;
        background: rgba(255,255,255,0.97);
        border: 1px solid rgba(226,232,240,0.9);
        box-shadow: 0 26px 70px rgba(15,23,42,0.12);
    }
    .mac-table-public {
        min-width: 900px;
    }
    .mac-table-public thead th {
        background: #f3f4f7;
        font-size: 0.78rem;
        letter-spacing: .08em;
    }
    .mac-badge-status {
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
    .mac-cards-wrapper {
        padding: 20px 22px 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .mac-order-card {
        border-radius: 22px;
        padding: 18px 20px;
        background: radial-gradient(circle at top left, rgba(255,255,255,0.96), rgba(241,245,255,0.98));
        border: 1px solid rgba(226,232,240,0.9);
        box-shadow: 0 18px 40px rgba(15,23,42,0.12);
    }
    .mac-order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }
    .mac-order-id {
        font-size: 0.8rem;
        letter-spacing: .16em;
        text-transform: uppercase;
        color: #9ca3af;
    }
    .mac-order-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #111827;
    }
    .mac-order-meta {
        font-size: 0.8rem;
        color: #6b7280;
    }
    .mac-order-body {
        display: grid;
        grid-template-columns: repeat(4, minmax(0,1fr));
        gap: 10px 22px;
        margin-top: 10px;
    }
    .mac-order-field-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: #9ca3af;
        margin-bottom: 2px;
    }
    .mac-order-field-value {
        font-size: 0.9rem;
        color: #111827;
    }
    .mac-order-note {
        margin-top: 14px;
        padding-top: 10px;
        border-top: 1px dashed rgba(209,213,219,0.8);
        font-size: 0.85rem;
        color: #4b5563;
    }
    @media (max-width: 992px) {
        .mac-order-body {
            grid-template-columns: repeat(2, minmax(0,1fr));
        }
    }
    @media (max-width: 576px) {
        .mac-order-body {
            grid-template-columns: 1fr;
        }
    }
</style>
<div class="mac-shell-public">
    <div class="mac-panel-public mb-4">
        <div>
            <h1 class="mac-panel-title">Status Laundry Saya</h1>
            <p class="mac-panel-subtitle">Masukkan nomor WhatsApp / telepon yang digunakan saat order untuk melihat status cucian.</p>
        </div>
        <form method="GET" class="w-100">
            <div class="mac-search-public">
                <div class="flex-grow-1">
                    <label class="form-label small mb-1">Nomor WhatsApp / Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $phone) }}" class="form-control mac-input" placeholder="Contoh: 08123456789">
                </div>
                <div class="mac-search-actions align-items-end">
                    <button class="btn btn-primary mac-btn w-100" type="submit"> Cari </button>
                    <a href="{{ route('status.index') }}" class="btn btn-outline-secondary mac-btn w-100">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="mac-panel-card-public">
        @if($orders->isEmpty())
            <div class="p-4 text-center text-muted">
                Belum ada order yang cocok. Coba gunakan nomor lain atau periksa kembali data pemesanan Anda.
            </div>
        @else
            <div class="mac-cards-wrapper">
                @foreach($orders as $order)
                    @php
                        $items = json_decode($order->items ?? '[]', true);
                        $serviceItems = collect($items ?: []);
                        $serviceSummary = $serviceItems->map(function ($item) {
                            $title = $item['title'] ?? 'Layanan';
                            $desc = trim($item['description'] ?? '');
                            return $desc !== '' ? "{$title} ({$desc})" : $title;
                        })->filter();
                        $serviceText = $serviceSummary->isNotEmpty()
                            ? $serviceSummary->implode(', ')
                            : 'Tidak ada informasi layanan.';
                        $isFinished = $order->status === 'selesai';
                        $statusLabel = $isFinished ? 'Selesai' : 'Sedang Diproses';
                        $badgeClass = $isFinished ? 'mac-badge-success' : 'mac-badge-info';
                        $nota = $order->nota;
                        $formattedTotal = '-';
                        if ($nota) {
                            $sisa = (int) ($nota->sisa ?? 0);
                            if ($sisa <= 0) {
                                $formattedTotal = 'Lunas';
                            } else {
                                $formattedTotal = 'Rp ' . number_format($sisa, 0, ',', '.');
                            }
                        }
                    @endphp
                    <div class="mac-order-card">
                        <div class="mac-order-header">
                            <div>
                                <div class="mac-order-id">ORDER #{{ $order->id }}</div>
                                <div class="mac-order-name">{{ $order->customer_name }}</div>
                                <div class="mac-order-meta">Dibuat {{ $order->created_at->format('d M Y H:i') }}</div>
                            </div>
                            <div>
                                <span class="mac-badge-status {{ $badgeClass }}">{{ $statusLabel }}</span>
                            </div>
                        </div>
                        <div class="mac-order-body">
                            <div>
                                <div class="mac-order-field-label">Kontak</div>
                                <div class="mac-order-field-value">{{ $order->customer_phone ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="mac-order-field-label">Alamat</div>
                                <div class="mac-order-field-value">{{ $order->customer_address ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="mac-order-field-label">Layanan</div>
                                <div class="mac-order-field-value">{{ $serviceText }}</div>
                            </div>
                            <div>
                                <div class="mac-order-field-label">Total</div>
                                <div class="mac-order-field-value">{{ $formattedTotal }}</div>
                            </div>
                        </div>
                        <div class="mac-order-note">
                            <span class="mac-order-field-label d-block mb-1">Catatan</span>
                            {{ \Illuminate\Support\Str::limit($order->notes ?? 'Tidak ada catatan', 120) }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
