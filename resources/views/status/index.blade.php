@extends('layouts.app')

@section('title', 'Status Laundry - Deva Laundry')

@section('content')
<style>
    .mac-shell-public {
        padding: 48px 0 56px;
        max-width: none;
        margin: 0;
    }

    .mac-shell-public-inner {
        display: grid;
        grid-template-columns: minmax(0, 420px) minmax(0, 1.4fr);
        gap: 32px;
        align-items: flex-start;
    }

    .mac-panel-public {
        border-radius: 32px;
        padding: 26px 26px 24px;
        background: #ffffff;
        border: 1px solid rgba(226,232,240,0.9);
        box-shadow: 0 18px 50px rgba(15,23,42,0.12);
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .mac-panel-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 4px 11px;
        border-radius: 999px;
        background: rgba(15,23,42,0.03);
        border: 1px solid rgba(148,163,184,0.35);
        font-size: 0.78rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #6b7280;
    }

    .mac-panel-pill-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34,197,94,0.28);
    }

    .mac-panel-title {
        font-size: clamp(1.9rem, 3vw, 2.25rem);
        font-weight: 800;
        color: #020617;
        margin: 6px 0 2px;
        letter-spacing: .03em;
    }

    .mac-panel-title span {
        background: none;
        -webkit-background-clip: initial;
        background-clip: initial;
        color: #111827;
    }
    .mac-panel-subtitle {
        color: #4b5563;
        margin: 4px 0 0;
        max-width: 420px;
        font-size: 0.93rem;
    }

    .mac-search-public {
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
    }
    .mac-search-public .mac-input {
        border-radius: 999px;
        border: 1px solid rgba(15,23,42,0.12);
        padding: 11px 18px;
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.9),
            0 0 0 1px rgba(148,163,184,0.18),
            0 18px 40px rgba(15,23,42,0.18);
        width: 100%;
        font-size: 0.95rem;
        background: rgba(255,255,255,0.96);
    }
    .mac-search-actions {
        display: flex;
        gap: 0.6rem;
        min-width: 220px;
    }
    .mac-btn {
        border-radius: 999px;
        padding: 10px 20px;
        font-weight: 600;
    }

    .mac-btn-primary {
        background: linear-gradient(120deg, #2563eb, #1d4ed8);
        border: none;
        color: #f9fafb;
        box-shadow:
            0 14px 30px rgba(37,99,235,0.35),
            0 0 0 1px rgba(59,130,246,0.6);
    }

    .mac-btn-primary:hover {
        background: linear-gradient(120deg, #1d4ed8, #1d4ed8);
        color: #fff;
    }

    .mac-btn-ghost {
        background: transparent;
        border: 1px solid rgba(148,163,184,0.7);
        color: #111827;
    }

    .mac-btn-ghost:hover {
        background: rgba(15,23,42,0.03);
    }

    .mac-panel-card-public {
        border-radius: 30px;
        padding: 0;
        background: #ffffff;
        border: 1px solid rgba(226,232,240,0.9);
        box-shadow: 0 24px 60px rgba(15,23,42,0.12);
        overflow: hidden;
    }

    .mac-panel-card-header {
        padding: 20px 24px 6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #111827;
    }

    .mac-panel-card-title {
        font-size: 0.9rem;
        letter-spacing: .12em;
        text-transform: uppercase;
        color: #6b7280;
    }

    .mac-panel-card-subtitle {
        font-size: 0.9rem;
        color: #4b5563;
        opacity: 0.9;
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
        padding: 8px 20px 20px;
        display: flex;
        flex-direction: column;
        gap: 16px;
        background: #ffffff;
    }
    .mac-order-card {
        border-radius: 22px;
        padding: 16px 18px 14px;
        background: #ffffff;
        border: 1px solid rgba(226,232,240,0.9);
        box-shadow: 0 14px 30px rgba(15,23,42,0.10);
        position: relative;
        overflow: hidden;
    }

    .mac-order-card::before {
        content: "";
        position: absolute;
        inset: 0;
        background: transparent;
        opacity: 0;
        pointer-events: none;
    }

    .mac-order-card-inner {
        position: relative;
        z-index: 1;
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
        opacity: 0.95;
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
        opacity: 0.95;
    }

    /* Stepper progress (Cucian selesai -> Proses pengiriman -> Laundry sudah diterima) */
    .mac-order-stepper {
        margin-top: 12px;
        margin-bottom: 4px;
    }
    .mac-stepper {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 0;
    }
    .mac-stepper-step {
        flex: 1;
        text-align: center;
        position: relative;
        font-size: 0.8rem;
        color: #9ca3af;
    }
    .mac-stepper-step:not(:last-child)::after {
        content: "";
        position: absolute;
        top: 14px;
        left: 50%;
        right: -50%;
        height: 2px;
        background: #e5e7eb;
        z-index: 0;
    }
    .mac-stepper-circle {
        width: 26px;
        height: 26px;
        border-radius: 999px;
        border: 2px solid #e5e7eb;
        background: #f9fafb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        font-weight: 600;
        margin: 0 auto 3px;
        position: relative;
        z-index: 1;
        color: #9ca3af;
    }
    .mac-stepper-label {
        font-size: 0.78rem;
        margin-top: 2px;
    }
    .mac-stepper-step.mac-is-done .mac-stepper-circle {
        background: #22c55e;
        border-color: #22c55e;
        color: #ffffff;
        box-shadow: 0 0 0 2px rgba(187,247,208,0.8);
    }
    .mac-stepper-step.mac-is-done:not(:last-child)::after {
        background: linear-gradient(90deg, #22c55e, #a3e635);
    }
    .mac-stepper-step.mac-is-current .mac-stepper-circle {
        background: #ecfdf5;
        border-color: #22c55e;
        color: #15803d;
        box-shadow: 0 0 0 2px rgba(187,247,208,0.9);
    }
    /* Jika langkah sudah selesai DAN sedang aktif (contoh: Laundry sudah diterima),
       tampilkan penuh hijau seperti langkah selesai lainnya. */
    .mac-stepper-step.mac-is-done.mac-is-current .mac-stepper-circle {
        background: #22c55e;
        border-color: #22c55e;
        color: #ffffff;
        box-shadow: 0 0 0 2px rgba(187,247,208,0.9);
    }
    .mac-stepper-step.mac-is-done .mac-stepper-label,
    .mac-stepper-step.mac-is-current .mac-stepper-label {
        color: #111827;
        font-weight: 600;
    }

    .mac-order-progress {
        margin-top: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .mac-order-progress-bar {
        flex: 1;
        height: 6px;
        border-radius: 999px;
        background: rgba(15,23,42,0.9);
        overflow: hidden;
        box-shadow: inset 0 0 0 1px rgba(15,23,42,1);
    }

    .mac-order-progress-bar-fill {
        height: 100%;
        border-radius: inherit;
        background: linear-gradient(90deg, #22c55e, #a3e635);
        box-shadow: 0 0 0 1px rgba(190,242,100,0.85);
    }

    .mac-order-progress-label {
        font-size: 0.78rem;
        color: #d1d5db;
    }

    @media (min-width: 992px) {
        /* Perlebar area konten status publik agar mendekati sidebar di desktop */
        .mac-shell-public {
            margin: 0 -1.5rem;
        }
    }

    @media (min-width: 576px) {
        .mac-search-actions .mac-btn-primary {
            min-width: 180px;
        }
    }
    @media (max-width: 992px) {
        .mac-shell-public-inner {
            grid-template-columns: minmax(0,1fr);
        }

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
    <div class="mac-shell-public-inner">
        <div class="mac-panel-public mb-4">
            <div>
                <div class="mac-panel-pill">
                    <span class="mac-panel-pill-dot"></span>
                    STATUS LAUNDRY
                </div>
                <h1 class="mac-panel-title">
                    Pantau cucianmu <span>real-time</span>.
                </h1>
                <p class="mac-panel-subtitle">
                    Masukkan nomor WhatsApp / telepon yang kamu gunakan saat melakukan pemesanan untuk melihat progres cucian tanpa perlu login.
                </p>
            </div>
            <form method="GET" class="w-100 mt-3">
                <div class="mac-search-public">
                    <div class="flex-grow-1">
                        <label class="form-label small mb-1">Nomor WhatsApp / Telepon</label>
                        <input type="text"
                               name="phone"
                               value="{{ old('phone', $phone) }}"
                               class="form-control mac-input"
                               placeholder="Contoh: 08123456789">
                    </div>
                    <div class="mac-search-actions align-items-end">
                        <button class="btn mac-btn mac-btn-primary w-100" type="submit">Cari status</button>
                        <a href="{{ route('status.index') }}" class="btn mac-btn mac-btn-ghost w-100">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="mac-panel-card-public">
            <div class="mac-panel-card-header">
                <div>
                    <div class="mac-panel-card-title">DAFTAR PESANAN</div>
                    <div class="mac-panel-card-subtitle">
                        {{ $orders->isEmpty() ? 'Belum ada pesanan yang ditemukan.' : 'Menampilkan ' . $orders->count() . ' pesanan terkait nomor tersebut.' }}
                    </div>
                </div>
            </div>

            @if($orders->isEmpty())
                <div class="p-4 text-center text-muted">
                    Belum ada order yang cocok. Coba gunakan nomor lain atau periksa kembali data pemesanan kamu.
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
                            // Status "Selesai" publik hanya muncul jika laundry sudah diterima (delivered_at terisi)
                            $isDelivered = (bool) $order->delivered_at;
                            $isFinished = $isDelivered;
                            $statusLabel = $isFinished ? 'Selesai' : 'Sedang Diproses';
                            $badgeClass = $isFinished ? 'mac-badge-success' : 'mac-badge-info';
                            $nota = $order->nota;
                            $formattedTotal = '-';
                            $hasNota = (bool) $nota;
                            if ($nota) {
                                $sisa = (int) ($nota->sisa ?? 0);
                                if ($sisa <= 0) {
                                    $formattedTotal = 'Lunas';
                                } else {
                                    $formattedTotal = 'Rp ' . number_format($sisa, 0, ',', '.');
                                }
                            }

                            // Alur publik:
                            // 0) Belum selesai -> cucian masih dikerjakan
                            // 1) Cucian selesai (nota sudah dibuat)
                            // 2) Proses pengiriman (sesudah cucian selesai, sebelum/selama pengantaran)
                            // 3) Laundry sudah diterima customer (setelah tombol "Sudah dikirim" diklik di admin)
                            $currentStep = 0;
                            $progressText = 'Cucian sedang dikerjakan oleh tim kami.';

                            if ($hasNota) {
                                // Langkah 1 dianggap selesai, langkah aktif di tahap pengiriman
                                $currentStep = 2;
                                $progressText = 'Cucian sudah selesai dan sedang / siap dikirim.';
                            }

                            if ($isDelivered) {
                                $currentStep = 3;
                                $progressText = 'Laundry sudah diterima customer. Terima kasih telah menggunakan Deva Laundry.';
                            }

                            $progressPercent = $currentStep > 0
                                ? min(100, intval(($currentStep / 3) * 100))
                                : 10;
                        @endphp
                        <div class="mac-order-card">
                            <div class="mac-order-card-inner">
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
                                        <div class="mac-order-field-label">Status Pembayaran</div>
                                        <div class="mac-order-field-value">{{ $formattedTotal }}</div>
                                    </div>
                                </div>

                                <div class="mac-order-stepper">
                                    <div class="mac-stepper">
                                        <div class="mac-stepper-step {{ $currentStep >= 1 ? 'mac-is-done' : '' }} {{ $currentStep === 1 ? 'mac-is-current' : '' }}">
                                            <div class="mac-stepper-circle">1</div>
                                            <div class="mac-stepper-label">Cucian selesai</div>
                                        </div>
                                        <div class="mac-stepper-step {{ $currentStep >= 2 ? 'mac-is-done' : '' }} {{ $currentStep === 2 ? 'mac-is-current' : '' }}">
                                            <div class="mac-stepper-circle">2</div>
                                            <div class="mac-stepper-label">Proses pengiriman</div>
                                        </div>
                                        <div class="mac-stepper-step {{ $currentStep >= 3 ? 'mac-is-done' : '' }} {{ $currentStep === 3 ? 'mac-is-current' : '' }}">
                                            <div class="mac-stepper-circle">3</div>
                                            <div class="mac-stepper-label">Laundry sudah diterima</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mac-order-progress">
                                    <div class="mac-order-progress-bar">
                                        <div class="mac-order-progress-bar-fill" style="width: {{ $progressPercent }}%;"></div>
                                    </div>
                                    <div class="mac-order-progress-label">
                                        {{ $progressText }}
                                    </div>
                                </div>

                                <div class="mac-order-note">
                                    <span class="mac-order-field-label d-block mb-1">Catatan</span>
                                    {{ \Illuminate\Support\Str::limit($order->notes ?? 'Tidak ada catatan', 120) }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
