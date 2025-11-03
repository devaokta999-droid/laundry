@extends('layouts.app')

@section('content')
<style>
/* 🌈 Desain Ringan Gaya macOS */
.mac-hero {
    background: linear-gradient(135deg, #007aff, #00b4ff);
    color: #fff;
    text-align: center;
    padding: 80px 20px;
    border-radius: 20px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
}
.mac-hero h1 {
    font-weight: 700;
    font-size: 2.6rem;
    letter-spacing: -0.5px;
}
.mac-btn {
    border: none;
    border-radius: 10px;
    padding: 12px 26px;
    font-size: 1rem;
    font-weight: 600;
    color: white;
    transition: 0.2s;
}
.btn-primary.mac-btn {
    background: linear-gradient(135deg, #007aff, #0062e0);
}
.btn-primary.mac-btn:hover {
    background: #339cff;
    transform: translateY(-2px);
}
.btn-success.mac-btn {
    background: linear-gradient(135deg, #34c759, #30d158);
}
.btn-success.mac-btn:hover {
    background: #5cd87b;
    transform: translateY(-2px);
}
.mac-section {
    padding: 50px 0;
}
.mac-title {
    color: #007aff;
    font-weight: 700;
    margin-bottom: 30px;
    text-align: center;
}
.mac-card {
    background: #ffffff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    border: 1px solid #f0f0f0;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.mac-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 18px rgba(0,0,0,0.1);
}
.mac-footer {
    margin-top: 40px;
    text-align: center;
    color: #666;
    font-size: 0.95rem;
}
.mac-footer a {
    color: #007aff;
    text-decoration: none;
}
.mac-footer a:hover {
    text-decoration: underline;
}
</style>

{{-- 🧺 HERO SECTION --}}
<div class="container my-5">
    <div class="mac-hero">
        <h1>Deva Laundry</h1>
        <p class="lead mt-3">🧼 Cuci Bersih, Wangi, dan Cepat — Solusi Pakaian Kamu!</p>
        <div class="mt-4">
            <a href="{{ route('order.create') }}" class="btn btn-primary mac-btn me-2">🧾 Pesan Sekarang</a>
            <a target="_blank" href="https://wa.me/{{ config('app.admin_whatsapp') }}?text=Halo%20Deva%20Laundry"
               class="btn btn-success mac-btn">
               💬 Hubungi Kami
            </a>
        </div>
    </div>
</div>

{{-- 💧 LAYANAN SECTION --}}
<div class="container mac-section">
    <h3 class="mac-title">Layanan Kami</h3>
    <div class="row">
        @forelse($services as $s)
            <div class="col-md-4 mb-4">
                <div class="mac-card h-100">
                    <h5 class="fw-bold">{{ $s->name }}</h5>
                    <p class="text-muted">{{ $s->description ?? 'Tanpa deskripsi' }}</p>
                    <p class="fw-bold fs-5 text-primary">Rp {{ number_format($s->price, 0, ',', '.') }}</p>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Belum ada layanan yang tersedia.</p>
        @endforelse
    </div>
</div>

{{-- 🎁 PROMO SECTION --}}
@if(!empty($promos))
<div class="container mac-section">
    <h3 class="mac-title">Promo Spesial</h3>
    <div class="row">
        @foreach($promos as $p)
        <div class="col-md-6 mb-4">
            <div class="mac-card p-4">
                <h5 class="fw-semibold">{{ $p['title'] }}</h5>
                <p class="text-muted mb-0">{{ $p['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- 🔗 FOOTER --}}
<div class="mac-footer pb-5">
    <a href="{{ route('about') }}">Tentang Kami</a> | 
    <a href="{{ route('contact') }}">Kontak</a>
    <p class="mt-3 mb-0">© {{ date('Y') }} Deva Laundry — All rights reserved.</p>
</div>
@endsection
