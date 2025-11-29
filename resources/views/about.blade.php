@extends('layouts.app')

@section('title', 'Tentang Kami - Deva Laundry')

@section('content')
<style>
/* 🌈 macOS Modern Clean Style */
body {
    background: linear-gradient(135deg, #eaf3ff, #ffffff);
    font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    color: #333;
}

/* 🧊 Container utama */
.mac-container {
    max-width: 1260px;
    margin: 80px auto;
    padding: 20px;
}

/* 🪞 Header */
.mac-header {
    text-align: center;
    background: rgba(255,255,255,0.75);
    backdrop-filter: blur(14px);
    border-radius: 22px;
    padding: 50px 20px;
    border: 1px solid rgba(255,255,255,0.4);
    box-shadow: 0 8px 30px rgba(0,0,0,0.05);
    margin-bottom: 50px;
    transition: all 0.3s ease;
}
.mac-header:hover {
    box-shadow: 0 10px 36px rgba(0,0,0,0.08);
}
.mac-header img {
    width: 500px;
    margin-bottom: 20px;
    transition: transform 0.4s ease;
}
.mac-header img:hover {
    transform: scale(1.08);
}
.mac-header h1 {
    font-size: 2.8rem;
    font-weight: 800;
    color: #007aff;
}
.mac-header p {
    color: #666;
    font-size: 1.1rem;
}

/* 🧩 Dua kolom utama */
.mac-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
    gap: 36px;
}

/* 📜 Card */
.mac-card {
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    padding: 35px 30px;
    border: 1px solid rgba(255,255,255,0.5);
    box-shadow: 0 4px 18px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
}
.mac-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
.mac-card h2 {
    font-size: 1.6rem;
    color: #007aff;
    margin-bottom: 18px;
}
.mac-card ul {
    padding-left: 20px;
}
.mac-card li {
    margin-bottom: 8px;
    color: #444;
}

/* 💎 Tombol */
.mac-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    border-radius: 50px;
    padding: 0.8rem 1.6rem;
    font-weight: 600;
    font-size: 1rem;
    color: white;
    background: linear-gradient(135deg, #007aff, #0071e3);
    transition: 0.25s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}
.mac-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
}

/* 👥 Tim Kami */
.mac-team {
    margin-top: 80px;
    text-align: center;
}
.mac-team h2 {
    font-size: 2rem;
    font-weight: 800;
    color: #007aff;
    margin-bottom: 35px;
}
.mac-team-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 25px;
}
.mac-member {
    background: rgba(255,255,255,0.85);
    border-radius: 18px;
    padding: 20px;
    width: 210px;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.5);
    transition: all 0.3s ease;
    box-shadow: 0 4px 16px rgba(0,0,0,0.05);
}
.mac-member:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.08);
}
.mac-member img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.8);
    margin-bottom: 10px;
}
.mac-member h4 {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
}
.mac-member p {
    font-size: 0.9rem;
    color: #555;
}

/* 📱 Responsif */
@media (max-width: 768px) {
    .mac-header h1 {
        font-size: 2.2rem;
    }
    .mac-card {
        padding: 25px 20px;
    }
}
</style>

<div class="mac-container">
    {{-- 🪞 Header --}}
    <div class="mac-header">
        @php
            $aboutLogo = \App\Models\SiteSetting::getValue('logo_path', 'header.png');
        @endphp
        <img src="{{ asset('images/' . $aboutLogo) }}" alt="Logo Deva Laundry">
        <h1>Tentang Deva Laundry</h1>
        <p>Bersih • Rapi • Wangi • Tepat Waktu</p>
    </div>

    {{-- 🧩 Dua Kolom --}}
    <div class="mac-grid">
        {{-- Kolom Kiri --}}
        <div class="mac-card">
            <h2>Visi Kami</h2>
            <p>{{ $vision }}</p>

            <h2 class="mt-4">Misi Kami</h2>
            <ul>
                @foreach($missions as $misi)
                    <li>{{ $misi }}</li>
                @endforeach
            </ul>

            <div style="margin-top:25px; border-top:1px solid #eee; padding-top:15px;">
                <h3 style="color:#007aff; font-weight:600;">📍 Lokasi Kami</h3>
                <p>{{ $locationText }}</p>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="mac-card">
            <h2>{{ $whyTitle }}</h2>
            @foreach($whyParagraphs as $p)
                <p>{{ $p }}</p>
            @endforeach

            <div class="mt-4">
                <a href="{{ route('contact') }}" class="mac-btn">
                    <i class="fa-solid fa-phone"></i> Hubungi Kami
                </a>
            </div>

            <div style="margin-top:24px; padding-top:16px; border-top:1px solid #e5e7eb;">
                <h3 style="font-size:1.1rem; font-weight:700; color:#007aff; margin-bottom:10px;">Jam Operasional</h3>
                @foreach($hoursLines as $idx => $line)
                    <p style="margin:{{ $idx === 0 ? '0' : '2px 0 0' }}; color:#374151; font-size:0.95rem;">
                        {{ $line }}
                    </p>
                @endforeach
                <p style="margin-top:6px; color:#6b7280; font-size:0.85rem;">
                    Silakan datang pada jam operasional tersebut untuk pelayanan antar-jemput maupun drop-off langsung di outlet.
                </p>
            </div>
        </div>
    </div>

    {{-- 👥 Tim Kami --}}
    <div class="mac-team">
        <h2>Tim Profesional Kami</h2>
        <div class="mac-team-grid">
            @forelse($teams as $tim)
                <div class="mac-member">
                    @if($tim->photo)
                        <img src="{{ asset('images/' . $tim->photo) }}" alt="{{ $tim->name }}">
                    @else
                        <img src="{{ asset('images/' . $aboutLogo) }}" alt="{{ $tim->name }}">
                    @endif
                    <h4>{{ $tim->name }}</h4>
                    <p style="color:#007aff; font-weight:500;">{{ $tim->position }}</p>
                    <p style="font-size:0.85rem;">{{ $tim->description }}</p>
                </div>
            @empty
                {{-- fallback jika belum ada data tim di database --}}
                <div class="mac-member">
                    <img src="{{ asset('images/' . $aboutLogo) }}" alt="Deva Laundry">
                    <h4>Tim Deva Laundry</h4>
                    <p style="color:#007aff; font-weight:500;">Profesional & Berpengalaman</p>
                    <p style="font-size:0.85rem;">Tim kami siap memberikan layanan terbaik untuk setiap pelanggan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- 🔗 FontAwesome Icons --}}
@push('scripts')
<script src="https://kit.fontawesome.com/a2b1f3b59d.js" crossorigin="anonymous"></script>
@endpush
@endsection
