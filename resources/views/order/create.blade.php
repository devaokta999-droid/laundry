@extends('layouts.app')

@section('content')
<style>
    .order-hero {
        background: radial-gradient(circle at top left, #f5f5f7, #ffffff);
        border-radius: 32px;
        padding: 32px 32px;
        box-shadow:
            0 24px 70px rgba(0, 0, 0, 0.09),
            0 0 0 1px rgba(0, 0, 0, 0.03);
    }

    .order-hero-title {
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: -0.03em;
        color: #111827;
        margin-bottom: 8px;
    }

    .order-hero-subtitle {
        font-size: 0.95rem;
        color: #4b5563;
        max-width: 560px;
    }

    .order-brand-title {
        font-size: 5rem;
        font-weight: 700;
        letter-spacing: -0.04em;
        color: #020617;
    }

    .order-brand-subtitle {
        font-size: 1rem;
        font-weight: 500;
        color: #4b5563;
    }

    .order-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 4px 12px;
        border-radius: 999px;
        background: rgba(37, 99, 235, 0.06);
        color: #1d4ed8;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .order-shell {
        margin-top: 28px;
        border-radius: 26px;
        padding: 1px;
        background: linear-gradient(135deg, #e5e7eb, #f9fafb);
    }

    .order-inner {
        background: #ffffff;
        border-radius: 24px;
        padding: 24px;
    }

    .order-section-title {
        font-size: 0.95rem;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        color: #9ca3af;
        font-weight: 600;
        margin-bottom: 18px;
    }

    .order-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 4px;
    }

    .order-input,
    .order-textarea,
    .order-select {
        border-radius: 12px !important;
        border: 1px solid #e5e7eb;
        padding: 10px 12px;
        font-size: 0.9rem;
        transition: all 0.18s ease-out;
        background-color: #f9fafb;
        width: 100%;
    }

    .order-input:focus,
    .order-textarea:focus,
    .order-select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.35);
        background-color: #ffffff;
        outline: none;
    }

    .order-service-row {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 14px 16px;
        margin-bottom: 12px;
        border-radius: 18px;
        background: #ffffff;
        box-shadow: 0 14px 40px rgba(15, 23, 42, 0.04);
    }

    .order-service-checkbox {
        width: 18px;
        height: 18px;
        border-radius: 999px;
        border: 1px solid #d1d5db;
        cursor: pointer;
    }

    .order-service-title {
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: -0.01em;
        color: #111827;
        margin-bottom: 2px;
    }

    .order-service-desc {
        font-size: 0.8rem;
        color: #6b7280;
    }

    .order-qty-input {
        max-width: 88px;
        border-radius: 999px !important;
        text-align: center;
        font-size: 0.85rem;
    }

    .order-submit-btn {
        border-radius: 999px;
        padding: 10px 32px;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        font-size: 0.8rem;
        border: none;
        background: linear-gradient(135deg, #111827, #1f2933);
        color: #f9fafb;
        box-shadow:
            0 18px 40px rgba(0, 0, 0, 0.3),
            0 0 0 1px rgba(148, 163, 184, 0.3);
        transition: all 0.16s ease-out;
    }

    .order-submit-btn:hover {
        transform: translateY(-1px);
        box-shadow:
            0 24px 55px rgba(0, 0, 0, 0.32),
            0 0 0 1px rgba(148, 163, 184, 0.4);
    }

    .order-submit-btn:active {
        transform: translateY(0);
        box-shadow:
            0 12px 26px rgba(0, 0, 0, 0.28),
            0 0 0 1px rgba(148, 163, 184, 0.35);
    }

    @media (min-width: 992px) {
        .order-inner {
            padding: 26px 26px 24px;
        }

        .order-container {
            max-width: 1240px;
        }
    }
</style>

<div class="container-fluid order-container py-4 py-md-5">
    <div class="order-hero mb-4 mb-md-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
            <div>
                <div class="mb-3">
                    <div class="order-brand-title">Deva Laundry</div>
                    <div class="order-brand-subtitle">Kualitas premium, hasil wangi dan rapi.</div>
                </div>
                <div class="order-badge mb-3">
                    <span style="width:8px;height:8px;border-radius:999px;background:#22c55e;display:inline-block;"></span>
                    Layanan Laundry Premium
                </div>
                <h1 class="order-hero-title">
                    Cucian di rumah sudah numpuk?
                </h1>
                <p class="order-hero-subtitle mb-0">
                    Jangan ragu untuk menghubungi kami dan langsung saja drop cucian Anda di
                    <strong>Deva Laundry</strong> dengan mengisi form pemesanan di bawah ini. Tim kami akan membantu
                    menjemput dan merapikan cucian Anda dengan layanan terbaik.
                </p>
            </div>
        </div>

        <div class="order-shell">
            <div class="order-inner order-layout">
                <form method="POST" action="{{ route('order.store') }}" id="orderForm" class="w-100">
                    @csrf

                    <div>
                        <div class="mb-4">
                            <div class="order-section-title">Data Pelanggan</div>

                            <div class="mb-3">
                                <label class="order-label">Nama Lengkap</label>
                                <input
                                    class="form-control order-input"
                                    name="customer_name"
                                    value="{{ auth()->check() ? auth()->user()->name : old('customer_name') }}"
                                    placeholder="Masukkan nama lengkap"
                                    required
                                >
                            </div>

                            <div class="mb-3">
                                <label class="order-label">Nomor WhatsApp</label>
                                <input
                                    class="form-control order-input"
                                    name="customer_phone"
                                    value="{{ auth()->check() ? auth()->user()->phone : old('customer_phone') }}"
                                    placeholder="Contoh: 0812xxxxxxx"
                                    required
                                >
                            </div>

                            <div class="mb-3 mb-md-4">
                                <label class="order-label">Alamat Lengkap</label>
                                <textarea
                                    class="form-control order-textarea"
                                    name="customer_address"
                                    rows="3"
                                    required
                                    placeholder="Masukkan alamat penjemputan / pengantaran secara lengkap"
                                >{{ old('customer_address') }}</textarea>
                            </div>
                        </div>

                        <div class="mb-4 mb-md-0">
                            <div class="order-section-title">Penjadwalan</div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="order-label">Tanggal Jemput ke Rumah</label>
                                    <input
                                        type="date"
                                        class="form-control order-input"
                                        name="pickup_date"
                                        required
                                    >
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="order-label">Waktu Jemput ke Rumah</label>
                                    <input
                                        type="time"
                                        class="form-control order-input"
                                        name="pickup_time"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="order-label">Catatan Tambahan</label>
                                <textarea
                                    class="form-control order-textarea"
                                    name="notes"
                                    rows="2"
                                    placeholder="Contoh: Pisahkan pakaian putih dan berwarna"
                                >{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="order-section-title">Pilih Layanan</div>

                        <div id="servicesList" class="mb-4">
                            @foreach($services as $s)
                                <div class="order-service-row">
                                    <div>
                                        <input
                                            type="checkbox"
                                            class="order-service-checkbox service-checkbox"
                                            value="{{ $s->id }}"
                                            name="service_ids[]"
                                        >
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="order-service-title">{{ $s->title }}</div>
                                        <div class="order-service-desc">{{ $s->description }}</div>
                                    </div>
                                    <div>
                                        <input
                                            type="number"
                                            name="qty[]"
                                            min="1"
                                            value="1"
                                            class="form-control order-input order-qty-input qty-input"
                                        >
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex flex-column align-items-center align-items-md-end gap-2 mt-3">
                            <small class="text-muted">
                                Dengan mengirim pesanan, Anda akan diarahkan untuk chat dengan admin melalui WhatsApp.
                            </small>
                            <button type="submit" class="order-submit-btn">
                                Kirim Pesanan &amp; Chat WA Admin
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
