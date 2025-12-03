@extends('layouts.app')

@section('title', 'Deva Laundry - Cuci Bersih, Wangi, dan Cepat')

@section('content')
<style>
/* ---------- HERO ---------- */
.mac-hero {
    background: radial-gradient(circle at 0% 0%, #0a84ff 0, #0051cc 35%, #00152f 100%);
    color: #fff;
    padding: 56px 60px;
    border-radius: 28px;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.25);
    margin-top: 56px;
    position: relative;
    overflow: hidden;
}
.mac-hero-inner {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 40px;
}
.mac-hero-copy {
    max-width: 540px;
}
.mac-eyebrow {
    letter-spacing: 0.14em;
    text-transform: uppercase;
    font-size: 0.78rem;
    opacity: 0.78;
    margin-bottom: 10px;
}
.mac-hero h1 {
    font-weight: 800;
    font-size: clamp(2.8rem, 4.8vw, 3.6rem);
    margin: 0;
    letter-spacing: -0.6px;
    text-transform: uppercase;
}
.mac-hero p.lead {
    font-size: 1.15rem;
    margin-top: 12px;
    opacity: 0.95;
    max-width: 440px;
}
.mac-hero .cta {
    margin-top: 28px;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
.mac-hero-meta {
    margin-top: 20px;
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    font-size: 0.9rem;
    opacity: 0.9;
}
.mac-hero-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.mac-hero-meta span::before {
    content: "";
    width: 6px;
    height: 6px;
    border-radius: 999px;
    background: rgba(255,255,255,0.8);
}
.mac-hero-card {
    background: rgba(5, 18, 40, 0.78);
    border-radius: 22px;
    padding: 20px 22px;
    border: 1px solid rgba(255,255,255,0.18);
    box-shadow: 0 10px 34px rgba(0,0,0,0.4);
    min-width: 260px;
    max-width: 320px;
}
.mac-hero-card h5 {
    font-size: 1rem;
    font-weight: 700;
    margin-bottom: 8px;
}
.mac-hero-card p {
    font-size: 0.9rem;
    opacity: 0.9;
    margin-bottom: 6px;
}
.mac-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 999px;
    background: rgba(255,255,255,0.08);
    font-size: 0.78rem;
    margin-bottom: 10px;
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
    box-shadow: 0 8px 20px rgba(3,30,66,0.18);
    transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
}
.btn-primary.mac-btn {
    background: linear-gradient(135deg, #005ef7 0%, #007aff 100%);
}
.btn-primary.mac-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 40px rgba(0,122,255,0.25);
}
.btn-ghost.mac-btn {
    background: rgba(255,255,255,0.14);
    color: #fff;
    border: 1px solid rgba(255,255,255,0.32);
}
.btn-ghost.mac-btn:hover {
    transform: translateY(-3px);
    background: rgba(255,255,255,0.22);
}

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
    letter-spacing: 0.04em;
    text-transform: uppercase;
}
.mac-section-header {
    text-align: center;
    margin-bottom: 32px;
}
.mac-section-eyebrow {
    font-size: 0.8rem;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: #9ca3af;
    margin-bottom: 6px;
}
.mac-section-subtitle {
    max-width: 560px;
    margin: 4px auto 0;
    font-size: 0.95rem;
    color: #4b5563;
}

/* ---------- CARDS ---------- */
.mac-row {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;
    justify-content: center;
}
.mac-col {
    flex: 1 1 320px;
    max-width: 360px;
}
.mac-card {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 10px 24px rgba(6,30,66,0.06);
    border: 1px solid #f1f5fb;
    transition: transform .22s ease, box-shadow .22s ease;
    min-height: 140px;
}
.mac-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 40px rgba(6,30,66,0.08);
}

/* ---------- FOOTER (glass style) ---------- */
.mac-footer-pro {
    background: radial-gradient(circle at 0% 0%, rgba(10,132,255,0.95) 0%, rgba(0,40,100,0.98) 45%, rgba(0,15,45,1) 100%);
    color: #fff;
    text-align: center;
    padding: 40px 24px 32px;
    border-radius: 24px;
    margin-top: 48px;
    border: 1px solid rgba(255,255,255,0.18);
    backdrop-filter: blur(14px) saturate(150%);
    -webkit-backdrop-filter: blur(14px) saturate(150%);
    box-shadow: 0 -18px 50px rgba(3,30,66,0.18), 0 6px 24px rgba(3,30,66,0.06);
    position: relative;
    overflow: hidden;
}
.mac-footer-pro::before {
    content: "";
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    top: -26px;
    width: 94%;
    height: 40px;
    border-radius: 20px;
    background: linear-gradient(90deg, rgba(0,122,255,0.4), rgba(0,180,255,0.3), rgba(0,122,255,0.4));
    filter: blur(20px);
    z-index: 0;
    opacity: 0.9;
}
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
.mac-footer-pro .footer-meta {
    position: relative;
    z-index: 2;
    margin-bottom: 12px;
    font-size: 0.85rem;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    opacity: 0.85;
}
.mac-footer-pro .footer-title {
    position: relative;
    z-index: 2;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 4px;
}
.mac-footer-pro .footer-subtitle {
    position: relative;
    z-index: 2;
    font-size: 0.9rem;
    color: rgba(243,244,246,0.9);
    margin-bottom: 16px;
}
.mac-footer-pro .social-icons a {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,0.16);
    text-decoration: none;
    transition: all 0.25s ease;
    box-shadow: 0 8px 18px rgba(3,30,66,0.16), inset 0 -2px 6px rgba(0,0,0,0.12);
}
.mac-footer-pro .social-icons a:hover {
    transform: translateY(-8px) scale(1.08);
    background: #ffffff;
    box-shadow: 0 18px 40px rgba(0,122,255,0.22);
}
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
.mac-footer-pro .copyright {
    position: relative;
    z-index: 2;
    color: rgba(255,255,255,0.95);
    margin-top: 4px;
    font-size: 0.9rem;
}

/* Responsive */
@media (max-width: 900px) {
    .mac-hero {
        padding: 40px 22px;
        border-radius: 22px;
        margin-top: 32px;
    }
    .mac-hero-inner {
        flex-direction: column;
        align-items: flex-start;
    }
    .mac-hero-copy {
        max-width: 100%;
    }
}
@media (max-width: 520px) {
    .mac-hero {
        padding: 32px 18px;
    }
    .mac-footer-pro .social-icons a {
        width: 48px;
        height: 48px;
    }
    .social-logo {
        width: 22px;
        height: 22px;
    }
    .mac-footer-pro::before {
        width: 95%;
        top: -22px;
        height: 32px;
    }
}
</style>

{{-- ---------- HERO ---------- --}}
<div class="container">
    <section class="mac-hero">
        <div class="mac-hero-inner">
            <div class="mac-hero-copy">
                <p class="mac-eyebrow">Premium Laundry Experience</p>
                <h1>Deva Laundry</h1>
                <p class="lead">Cuci bersih, wangi, cepat, dan rapi - solusi pakaian harian dan spesial kamu.</p>
                <div class="cta mac-hero-cta">
                    <a href="{{ route('order.create') }}" class="mac-btn btn-primary mac-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Pesan sekarang
                    </a>
                    <a target="_blank" href="https://wa.me/6282147037006" class="mac-btn btn-ghost mac-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 12a9 9 0 10-9 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 22l-4.35-1.74" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Hubungi kami
                    </a>
                </div>
                <div class="mac-hero-meta">
                    <span>Antar-jemput area sekitar</span>
                    <span>Estimasi selesai tepat waktu</span>
                    <span>Pencucian rapi dan terstandar</span>
                </div>
            </div>
            <div class="mac-hero-card">
                <div class="mac-badge">Hari ini kamu sudah laundry?</div>
                <h5>Berikan pakaian kamu pengalaman premium.</h5>
                <p>Serahkan proses cuci, kering, dan setrika ke tim Deva Laundry. Kamu cukup pesan dari rumah.</p>
                <p class="mb-0">Pantau pesanan, atur jadwal, dan nikmati pakaian yang selalu siap dipakai.</p>
            </div>
        </div>
    </section>
</div>

{{-- ---------- SERVICES ---------- --}}
<div class="container mac-section">
    <div class="mac-section-header">
        <div class="mac-section-eyebrow">Services</div>
        <h3 class="mac-title">Layanan Kami</h3>
        <p class="mac-section-subtitle">Pilihan layanan cuci, setrika, dan perawatan kain yang disusun rapi seperti rak di layanan deva laundry.</p>
    </div>
    <div class="mac-row">
        @forelse($services as $s)
            <div class="mac-col">
                <div class="mac-card">
                    <h5 class="mb-2 fw-bold">{{ $s->name }}</h5>
                    <p class="mb-0" style="color:#4b5563;">{{ $s->description ?? 'Tanpa deskripsi' }}</p>
                    {{-- Harga disembunyikan, hanya deskripsi layanan. --}}
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
        <div class="mac-section-header">
            <div class="mac-section-eyebrow">Offers</div>
            <h3 class="mac-title">Promo Spesial</h3>
            <p class="mac-section-subtitle">Nikmati penawaran terbaik Deva Laundry, dirancang agar pengalaman laundry terasa lebih ringan.</p>
        </div>
        <div class="mac-row">
            @foreach($promos as $p)
                <div class="mac-col">
                    <div class="mac-card">
                        <h5 class="mb-2 fw-semibold">{{ $p['title'] }}</h5>
                        <p class="mb-0" style="color:#4b5563;">{{ $p['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

{{-- ---------- FOOTER ---------- --}}
<div class="container">
    <footer class="mac-footer-pro">
        <div class="footer-meta">Stay Fresh, Stay Ready</div>
        <div class="footer-title">Deva Laundry</div>
        <div class="footer-subtitle">Laundry harian sampai pakaian spesial, ditangani dengan standar premium.</div>
        <div class="social-icons" aria-label="Deva Laundry social links">
            <a href="{{ $contactFacebook }}" target="_blank" title="Facebook" rel="noopener">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/facebook.svg" alt="Facebook" class="social-logo">
            </a>
            <a href="{{ $contactInstagram }}" target="_blank" title="Instagram" rel="noopener">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/instagram.svg" alt="Instagram" class="social-logo">
            </a>
            <a href="{{ $contactTikTok }}" target="_blank" title="TikTok" rel="noopener">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/tiktok.svg" alt="TikTok" class="social-logo">
            </a>
            <a href="https://wa.me/{{ preg_replace('/\D/', '', $contactPhone) }}" target="_blank" title="WhatsApp" rel="noopener">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/whatsapp.svg" alt="Whatsapp" class="social-logo">
            </a>
            <a href="mailto:{{ $contactEmail }}" title="Email" rel="noopener">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/gmail.svg" alt="Email" class="social-logo">
            </a>
            <a href="{{ $contactMapsLink }}" target="_blank" title="Lokasi kami" rel="noopener">
                <img src="https://cdn.jsdelivr.net/gh/simple-icons/simple-icons/icons/googlemaps.svg" alt="Maps" class="social-logo">
            </a>
        </div>
        <div class="copyright">
            &copy; {{ date('Y') }} <strong>Deva Laundry</strong> &mdash; Made with care by
            <a href="#" style="color:rgba(255,255,255,0.95);text-decoration:underline;">Deva Studio</a>
        </div>
    </footer>
</div>
@endsection
