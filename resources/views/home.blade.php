@extends('layouts.app')

@section('title', 'Deva Laundry - Cuci Bersih, Wangi, dan Cepat')

@section('content')
<style>
/* ---------- HERO ---------- */
.mac-hero {
    background:
        radial-gradient(circle at 0% 0%, #0a84ff 0, #0051cc 35%, #00152f 100%);
    color: #fff;
    padding: 56px 60px;
    border-radius: 28px;
    box-shadow:
        0 26px 70px rgba(15,23,42,0.45),
        0 0 0 1px rgba(255,255,255,0.10);
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
.mac-hero-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 4px 11px;
    border-radius: 999px;
    background: rgba(15,23,42,0.18);
    border: 1px solid rgba(148,163,184,0.35);
    font-size: 0.78rem;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    color: rgba(243,244,246,0.9);
    margin-bottom: 10px;
}
.mac-hero-pill-dot {
    width: 7px;
    height: 7px;
    border-radius: 999px;
    background: #22c55e;
    box-shadow: 0 0 0 4px rgba(34,197,94,0.35);
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
    background:
        radial-gradient(circle at 0% 0%, rgba(255,255,255,0.16), transparent 55%),
        rgba(5, 18, 40, 0.82);
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
    box-shadow: 0 10px 24px rgba(6,30,66,0.09);
    border: 1px solid rgba(226,232,240,0.9);
    transition: transform .22s ease, box-shadow .22s ease;
    min-height: 140px;
}
.mac-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 40px rgba(6,30,66,0.08);
}

/* ---------- REVIEWS (Testimonials Slider) ---------- */
.review-slider {
    position: relative;
    overflow: hidden;
}
.review-shell {
    background: radial-gradient(circle at 0% 0%, #ffffff 0, #eef2ff 40%, #e0e7ff 100%);
    border-radius: 26px;
    padding: 26px 26px 46px;
    box-shadow:
        0 26px 60px rgba(15,23,42,0.14),
        0 0 0 1px rgba(255,255,255,0.8);
    border: 1px solid rgba(148,163,184,0.12);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
}
.review-slider-inner {
    display: flex;
    gap: 24px;
    overflow-x: auto;
    scroll-behavior: smooth;
    scroll-snap-type: x mandatory;
    padding-bottom: 6px;
}
.review-slider-inner::-webkit-scrollbar {
    display: none;
}
.review-col {
    flex: 0 0 calc(25% - 18px);
    scroll-snap-align: start;
}
.review-card {
    position: relative;
    background: rgba(255,255,255,0.96);
    border-radius: 18px;
    padding: 20px 18px 54px;
    box-shadow:
        0 18px 40px rgba(15,23,42,0.08),
        0 0 0 1px rgba(226,232,240,0.9);
    border: 1px solid rgba(226,232,240,0.9);
    text-align: center;
    min-height: 160px;
    transition: transform .2s ease, box-shadow .2s ease;
}
.review-card:hover {
    transform: translateY(-4px);
    box-shadow:
        0 26px 60px rgba(15,23,42,0.16),
        0 0 0 1px rgba(191,219,254,0.9);
}
.review-quote-icon {
    font-size: 2.6rem;
    line-height: 1;
    color: #e5e7eb;
    margin-bottom: 6px;
}
.review-text {
    font-size: 0.92rem;
    color: #111827;
    line-height: 1.7;
    margin-bottom: 14px;
}
.review-stars {
    display: flex;
    justify-content: center;
    gap: 4px;
    margin-bottom: 20px;
}
.review-star {
    font-size: 1rem;
}
.review-star.filled {
    color: #fbbf24;
}
.review-star.empty {
    color: #e5e7eb;
}
.review-footer {
    position: absolute;
    left: 50%;
    bottom: -46px;
    transform: translateX(-50%);
    display: flex;
    flex-direction: column;
    align-items: center;
}
.review-avatar {
    width: 56px;
    height: 56px;
    border-radius: 999px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 1.1rem;
    box-shadow: 0 8px 20px rgba(15,23,42,0.18);
    margin-bottom: 6px;
}
.review-avatar.variant-1 {
    background: radial-gradient(circle at 30% 0, #fde68a, #f97316);
}
.review-avatar.variant-2 {
    background: radial-gradient(circle at 30% 0, #bfdbfe, #3b82f6);
}
.review-avatar.variant-3 {
    background: radial-gradient(circle at 30% 0, #bbf7d0, #22c55e);
}
.review-avatar.variant-4 {
    background: radial-gradient(circle at 30% 0, #fee2e2, #f97373);
}
.review-name {
    font-weight: 700;
    color: #0f172a;
    font-size: 0.92rem;
}
@media (max-width: 1199.98px) {
    .review-col {
        flex: 0 0 calc(33.333% - 18px);
    }
}
@media (max-width: 991.98px) {
    .review-col {
        flex: 0 0 calc(50% - 18px);
    }
}
@media (max-width: 575.98px) {
    .review-col {
        flex: 0 0 100%;
    }
}

/* ---------- HOME WIDTH ---------- */
.home-wide-container {
    max-width: 1780px;
    width: 100%;
    margin-left: auto;
    margin-right: auto;
}

/* ---------- PAYMENT LOGOS (Apple-like minimal + marquee) ---------- */
.pay-section {
    padding: 32px 34px 40px;
    background: radial-gradient(circle at 0 0, #f9fbff 0, #eef2ff 45%, #e5edff 100%);
    border-radius: 26px;
    box-shadow:
        0 24px 60px rgba(15,23,42,0.10),
        0 0 0 1px rgba(255,255,255,0.90);
}
.pay-section-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
}
.pay-section-header {
    text-align: center;
}
.pay-marquee {
    width: 100%;
    margin-top: 12px;
}
.pay-row {
    position: relative;
    overflow-x: auto;
    overflow-y: hidden;
    padding-inline: 16px;
}
.pay-row::-webkit-scrollbar {
    display: none;
}
.pay-row-track {
    display: flex;
    align-items: center;
    gap: 40px;
    padding-block: 4px;
}
.pay-logo-card {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 140px;
    min-height: 70px;
    padding: 0 6px;
    background: transparent;
    border-radius: 0;
    box-shadow: none;
    transition: transform .18s ease-out;
}
.pay-logo-card img {
    max-height: 78px;
    max-width: 200px;
    width: auto;
    object-fit: contain;
}
.pay-logo-card:hover {
    transform: translateY(-4px) scale(1.04);
}
@media (max-width: 767.98px) {
    .pay-section {
        padding: 24px 18px 30px;
    }
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
  <div class="container-fluid home-wide-container">
    <section class="mac-hero">
        <div class="mac-hero-inner">
            <div class="mac-hero-copy">
                <div class="mac-hero-pill">
                    <span class="mac-hero-pill-dot"></span>
                    {{ $heroEyebrow }}
                </div>
                <h1>{{ $heroTitle }}</h1>
                <p class="lead">{{ $heroSubtitle }}</p>
                <div class="cta mac-hero-cta">
                    <a href="{{ route('order.create') }}" class="mac-btn btn-primary mac-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Pesan sekarang
                    </a>
                    <a target="_blank" href="https://wa.me/{{ preg_replace('/\D/', '', $contactPhone) }}?text={{ urlencode('Halo Deva Laundry') }}" class="mac-btn btn-ghost mac-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 12a9 9 0 10-9 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 22l-4.35-1.74" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Hubungi kami
                    </a>
                </div>
                <div class="mac-hero-meta">
                    @foreach($heroBullets as $bullet)
                        @if(trim($bullet) !== '')
                            <span>{{ $bullet }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="mac-hero-card">
                <div class="mac-badge">{{ $heroCardBadge }}</div>
                <h5>{{ $heroCardTitle }}</h5>
                @foreach($heroCardParagraphs as $paragraph)
                    <p class="{{ $loop->last ? 'mb-0' : '' }}">{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>
    </section>
</div>

{{-- ---------- SERVICES ---------- --}}
  <div class="container-fluid home-wide-container mac-section">
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
    <div class="container-fluid home-wide-container mac-section">
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

{{-- ---------- CUSTOMER REVIEWS ---------- --}}
@if(isset($reviews) && $reviews->count() > 0)
<div class="container-fluid home-wide-container mac-section">
    <div class="mac-section-header">
        <div class="mac-section-eyebrow">Testimonials</div>
        <h3 class="mac-title">Rating & Ulasan Pelanggan</h3>
        <p class="mac-section-subtitle">
            Beberapa pengalaman langsung dari pelanggan Deva Laundry.
        </p>
    </div>
    <div class="review-shell review-slider">
        <div class="review-slider-inner" id="reviewSliderInner">
            @foreach($reviews as $review)
                @php
                    $initial = mb_strtoupper(mb_substr($review->name ?? 'U', 0, 1));
                @endphp
                <div class="review-col">
                    <div class="review-card">
                        <div class="review-quote-icon">“</div>
                        @if(!empty($review->image_path))
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $review->image_path) }}"
                                     alt="Foto ulasan dari {{ $review->name }}"
                                     style="max-width: 100%; border-radius: 16px; box-shadow: 0 10px 30px rgba(15,23,42,0.18); object-fit: cover;">
                            </div>
                        @endif
                        <p class="review-text">{{ $review->comment }}</p>
                        <div class="review-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="review-star {{ $i <= $review->rating ? 'filled' : 'empty' }}">★</span>
                            @endfor
                        </div>
                        <div class="review-footer">
                            <div class="review-avatar">
                                <span>{{ $initial }}</span>
                            </div>
                            <div class="review-name">{{ $review->name }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

{{-- ---------- PAYMENT METHODS ---------- --}}
  <div class="container-fluid home-wide-container">
      <section class="pay-section">
          <div class="pay-section-inner">
              <div class="mac-section-header pay-section-header">
                  <div class="mac-section-eyebrow">Payment</div>
                  <h3 class="mac-title">Metode Pembayaran</h3>
                  <p class="mac-section-subtitle">
                      Kami mendukung berbagai metode pembayaran digital agar proses transaksi di Deva Laundry terasa cepat dan praktis.
                  </p>
              </div>
              @php
                  $methods = collect($paymentMethods ?? []);
                  $half = (int) ceil(max($methods->count(), 1) / 2);
                  $topRow = $methods->slice(0, $half);
                  $bottomRow = $methods->slice($half);
              @endphp
              <div class="pay-marquee">
                  <div class="pay-row pay-row-top">
                      @if($methods->isEmpty())
                          <div class="pay-row-track">
                              <div class="pay-logo-card">
                                  <span class="fw-semibold" style="color:#111827;font-size:0.95rem;">
                                      Metode pembayaran belum diatur.
                                  </span>
                              </div>
                          </div>
                      @else
                          <div class="pay-row-track">
                              @foreach($topRow as $method)
                                  <div class="pay-logo-card">
                                      @if(!empty($method['logo_url'] ?? ''))
                                          <img src="{{ $method['logo_url'] }}" alt="{{ $method['name'] ?? 'Metode Pembayaran' }}">
                                      @else
                                          <span class="fw-semibold" style="color:#111827;font-size:0.95rem;">
                                              {{ $method['name'] ?? 'Metode Pembayaran' }}
                                          </span>
                                      @endif
                                  </div>
                              @endforeach
                              @foreach($topRow as $method)
                                  <div class="pay-logo-card">
                                      @if(!empty($method['logo_url'] ?? ''))
                                          <img src="{{ $method['logo_url'] }}" alt="{{ $method['name'] ?? 'Metode Pembayaran' }}">
                                      @else
                                          <span class="fw-semibold" style="color:#111827;font-size:0.95rem;">
                                              {{ $method['name'] ?? 'Metode Pembayaran' }}
                                          </span>
                                      @endif
                                  </div>
                              @endforeach
                          </div>
                      @endif
                  </div>
                  @if($methods->count() > 1)
                      <div class="pay-row pay-row-bottom">
                          <div class="pay-row-track">
                              @foreach($bottomRow as $method)
                                  <div class="pay-logo-card">
                                      @if(!empty($method['logo_url'] ?? ''))
                                          <img src="{{ $method['logo_url'] }}" alt="{{ $method['name'] ?? 'Metode Pembayaran' }}">
                                      @else
                                          <span class="fw-semibold" style="color:#111827;font-size:0.95rem;">
                                              {{ $method['name'] ?? 'Metode Pembayaran' }}
                                          </span>
                                      @endif
                                  </div>
                              @endforeach
                              @foreach($bottomRow as $method)
                                  <div class="pay-logo-card">
                                      @if(!empty($method['logo_url'] ?? ''))
                                          <img src="{{ $method['logo_url'] }}" alt="{{ $method['name'] ?? 'Metode Pembayaran' }}">
                                      @else
                                          <span class="fw-semibold" style="color:#111827;font-size:0.95rem;">
                                              {{ $method['name'] ?? 'Metode Pembayaran' }}
                                          </span>
                                      @endif
                                  </div>
                              @endforeach
                          </div>
                      </div>
                  @endif
              </div>
          </div>
      </section>
  </div>

{{-- ---------- FOOTER ---------- --}}
<div class="container-fluid home-wide-container">
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto slideshow for testimonials (manual scroll tetap bisa)
        var slider = document.getElementById('reviewSliderInner');
        if (slider) {
            var total = slider.children.length;
            var visibleCount = 4;
            var currentPage = 0;

            if (total > visibleCount) {
                function scrollToPage(page) {
                    var cardWidth = slider.children[0].clientWidth + 24; // width + gap
                    var target = page * cardWidth;
                    slider.scrollTo({
                        left: target,
                        behavior: 'smooth'
                    });
                }

                function nextPage() {
                    var maxPage = Math.max(total - visibleCount, 0);
                    currentPage = (currentPage + 1) % (maxPage + 1);
                    scrollToPage(currentPage);
                }

                setInterval(nextPage, 6000);
            }

            var avatars = document.querySelectorAll('.review-avatar');
            avatars.forEach(function (avatar, index) {
                var variant = (index % 4) + 1;
                avatar.classList.add('variant-' + variant);
            });
        }

        // Auto-scroll untuk baris payment (manual scroll tetap bisa)
        function autoScrollRow(selector, direction) {
            var row = document.querySelector(selector);
            if (!row) return;

            var step = 1; // px per tick
            var interval = 25; // ms

            setInterval(function () {
                if (!row) return;
                var maxScroll = row.scrollWidth - row.clientWidth;
                if (maxScroll <= 0) return;

                var next = row.scrollLeft + step * direction;
                if (next < 0) {
                    next = maxScroll;
                } else if (next > maxScroll) {
                    next = 0;
                }
                row.scrollLeft = next;
            }, interval);
        }

        autoScrollRow('.pay-row-top', 1);   // geser ke kiri
        autoScrollRow('.pay-row-bottom', -1); // geser ke kanan
    });
</script>
@endsection
