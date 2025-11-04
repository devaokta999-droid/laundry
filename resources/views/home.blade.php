@extends('layouts.app')

@section('title', 'Deva Laundry - Cuci Bersih, Wangi, dan Cepat')

@section('content')
<style>
/* ---------- Global / Base ---------- */
body {
    background: linear-gradient(135deg, #eaf3ff, #ffffff);
    font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    color: #333;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* ---------- HERO ---------- */
.container { max-width: 1140px; }
.mac-hero {
    background: linear-gradient(135deg, #007aff 0%, #00b4ff 100%);
    color: #fff;
    text-align: center;
    padding: 88px 24px;
    border-radius: 24px;
    box-shadow: 0 12px 40px rgba(3,30,66,0.12);
    margin-top: 48px;
}
.mac-hero h1 {
    font-weight: 800;
    font-size: 7rem;
    margin: 0;
    letter-spacing: -0.6px;
}
.mac-hero p.lead {
    font-size: 1.20rem;
    margin-top: 12px;
    opacity: 0.95;
}
.mac-hero .cta {
    margin-top: 26px;
    display: inline-flex;
    gap: 12px;
}
.mac-btn {
    border: none;
    border-radius: 999px;
    padding: 12px 22px;
    font-size: 1rem;
    font-weight: 700;
    color: white;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 8px 20px rgba(3,30,66,0.12);
    transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
}
.btn-primary.mac-btn {
    background: linear-gradient(135deg, #005ef7 0%, #007aff 100%);
}
.btn-primary.mac-btn:hover { transform: translateY(-3px); box-shadow: 0 18px 40px rgba(0,122,255,0.18); }
.btn-ghost.mac-btn {
    background: rgba(255,255,255,0.12);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.12);
}
.btn-ghost.mac-btn:hover { transform: translateY(-3px); background: rgba(255,255,255,0.18); }

/* ---------- SECTIONS ---------- */
.mac-section {
    padding: 56px 0;
}
.mac-title {
    color: #007aff;
    text-align: center;
    font-weight: 800;
    margin-bottom: 28px;
    font-size: 1.6rem;
}

/* ---------- CARDS ---------- */
.mac-row { display: flex; flex-wrap: wrap; gap: 24px; justify-content: center; }
.mac-col { flex: 1 1 320px; max-width: 360px; }
.mac-card {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 10px 24px rgba(6,30,66,0.06);
    border: 1px solid #f1f5fb;
    transition: transform .22s ease, box-shadow .22s ease;
}
.mac-card:hover { transform: translateY(-6px); box-shadow: 0 18px 40px rgba(6,30,66,0.08); }

/* ---------- FOOTER (Glass Dock macOS style) ---------- */
.mac-footer-pro {
    background: linear-gradient(135deg, rgba(0,122,255,0.85) 0%, rgba(0,180,255,0.85) 100%);
    color: #fff;
    text-align: center;
    padding: 60px 24px 40px;
    border-radius: 24px;
    margin-top: 48px;
    border: 1px solid rgba(255,255,255,0.15);
    backdrop-filter: blur(12px) saturate(140%);
    -webkit-backdrop-filter: blur(12px) saturate(140%);
    box-shadow: 0 -18px 50px rgba(3,30,66,0.18), 0 6px 24px rgba(3,30,66,0.06);
    position: relative;
    overflow: hidden;
}

/* Glow lembut di atas dock */
.mac-footer-pro::before {
    content: "";
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    top: -28px;
    width: 94%;
    height: 40px;
    border-radius: 20px;
    background: linear-gradient(90deg, rgba(0,122,255,0.4), rgba(0,180,255,0.3), rgba(0,122,255,0.4));
    filter: blur(20px);
    z-index: 0;
    opacity: 0.9;
}

/* Ikon sosial */
.mac-footer-pro .social-icons {
    position: relative;
    z-index: 2;
    display: flex;
    gap: 18px;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.mac-footer-pro .social-icons a {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.15);
    text-decoration: none;
    transition: all 0.25s ease;
    box-shadow: 0 8px 18px rgba(3,30,66,0.1), inset 0 -2px 6px rgba(0,0,0,0.12);
}
.mac-footer-pro .social-icons a:hover {
    transform: translateY(-8px) scale(1.08);
    background: #ffffff;
    box-shadow: 0 18px 40px rgba(0,122,255,0.22);
}

/* Gambar logo SVG */
.social-logo {
    width: 28px;
    height: 28px;
    filter: invert(1) drop-shadow(0 0 4px rgba(255,255,255,0.6));
    transition: transform 0.25s ease, filter 0.25s ease;
}
.mac-footer-pro .social-icons a:hover .social-logo {
    filter: invert(0) drop-shadow(0 0 6px rgba(0,122,255,0.5));
    transform: scale(1.15);
}

/* Animasi lembut */
@keyframes floatLight {
    0%,100% { transform: translateY(0); }
    50% { transform: translateY(-6px); }
}
.mac-footer-pro .social-icons a:nth-child(odd) { animation: floatLight 4s ease-in-out infinite; }
.mac-footer-pro .social-icons a:nth-child(even) { animation: floatLight 4s ease-in-out infinite 1.8s; }

/* copyright */
.mac-footer-pro .copyright {
    position: relative;
    z-index: 2;
    color: rgba(255,255,255,0.95);
    margin-top: 8px;
    font-size: 0.95rem;
}

/* Responsive */
@media (max-width: 900px) {
    .mac-hero { padding: 56px 18px; border-radius: 18px; }
    .mac-hero h1 { font-size: 2.2rem; }
}
@media (max-width: 520px) {
    .mac-hero { padding: 44px 16px; }
    .mac-footer-pro .social-icons a { width: 48px; height: 48px; }
    .social-logo { width: 22px; height: 22px; }
    .mac-footer-pro::before { width: 95%; top: -22px; height: 32px; }
}
</style>

{{-- ---------- HERO ---------- --}}
<div class="container">
    <div class="mac-hero">
        <h1>DEVA LAUNDRY</h1>
        <p class="lead">Cuci Bersih, Wangi,Cepat dan Rapi — Solusi Pakaian Kamu!</p>
        <div class="cta">
            <a href="{{ route('order.create') }}" class="mac-btn btn-primary mac-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Pesan Sekarang
            </a>
            <a target="_blank" href="https://wa.me/{{ preg_replace('/\D/', '', config('app.admin_whatsapp', '6285333634884')) }}?text=Halo%20Deva%20Laundry%20Aku%20Mau%20Pesan" class="mac-btn btn-ghost mac-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M21 12a9 9 0 10-9 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 22l-4.35-1.74" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                Hubungi Kami
            </a>
        </div>
    </div>
</div>

{{-- ---------- SERVICES ---------- --}}
<div class="container mac-section">
    <h3 class="mac-title">Layanan Kami</h3>
    <div class="mac-row">
        @forelse($services as $s)
        <div class="mac-col">
            <div class="mac-card">
                <h5 class="mb-2 fw-bold">{{ $s->name }}</h5>
                <p class="text-muted mb-3">{{ $s->description ?? 'Tanpa deskripsi' }}</p>
                <div class="fw-bold text-primary">Rp {{ number_format($s->price, 0, ',', '.') }}</div>
            </div>
        </div>
        @empty
        <div class="mac-col">
            <div class="mac-card text-center">
                <p class="mb-0 text-muted">Belum ada layanan tersedia.</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

{{-- ---------- PROMO ---------- --}}
@if(!empty($promos))
<div class="container mac-section">
    <h3 class="mac-title">Promo Spesial</h3>
    <div class="mac-row">
        @foreach($promos as $p)
        <div class="mac-col">
            <div class="mac-card">
                <h5 class="mb-2 fw-semibold">{{ $p['title'] }}</h5>
                <p class="text-muted mb-0">{{ $p['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ---------- FOOTER ---------- --}}
<div class="container">
    <footer class="mac-footer-pro">
        <div class="social-icons" aria-label="Deva Laundry social links">
            <a href="https://facebook.com/devalaundry" target="_blank" title="Facebook" rel="noopener">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" alt="Facebook" class="social-logo">
            </a>
            <a href="https://www.instagram.com/devasptr_?igsh=OWl5d28xbm11N3dn" target="_blank" title="Instagram" rel="noopener">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram" class="social-logo">
            </a>
            <a href="https://www.tiktok.com/@devalaundry.official" target="_blank" title="TikTok" rel="noopener">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/tiktok.svg" alt="TikTok" class="social-logo">
            </a>
            <a href="https://wa.me/{{ preg_replace('/\D/', '', config('app.admin_whatsapp', '6285333634884')) }}" target="_blank" title="WhatsApp" rel="noopener">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/whatsapp.svg" alt="Whatsapp" class="social-logo">
            </a>
            <a href="mailto:hello@devalaundry.example" title="Email" rel="noopener">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/gmail.svg" alt="Email" class="social-logo">
            </a>
            <a href="https://maps.app.goo.gl/z7jsgdWsD8JC4MFo8" target="_blank" title="Lokasi kami" rel="noopener">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/googlemaps.svg" alt="Maps" class="social-logo">
            </a>
        </div>
        <div class="copyright">
            © {{ date('Y') }} <strong>Deva Laundry</strong> — Made with  by 
            <a href="#" style="color:rgba(255,255,255,0.95);text-decoration:underline;">Deva Studio</a>
        </div>
    </footer>
</div>
@endsection
