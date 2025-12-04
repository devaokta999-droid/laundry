@extends('layouts.app')

@section('title', 'Hubungi Kami - Deva Laundry')

@section('content')
<style>
    #page-content {
        padding: 0;
        background: radial-gradient(circle at top left, #e5f0ff, #ffffff 55%, #f3f4ff);
        min-height: calc(100vh - 70px);
    }

    .contact-mac-wrapper {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 72px 0 88px;
    }

    .mac-container {
        max-width: 1920px;
        width: 100%;
        padding: 24px;
    }

    .mac-header {
        text-align: center;
        background: rgba(255,255,255,0.82);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border-radius: 26px;
        padding: 82px 36px 64px;
        border: 1px solid rgba(148,163,184,0.28);
        box-shadow: 0 22px 60px rgba(15,23,42,0.16);
        margin-bottom: 50px;
    }
    .mac-header-eyebrow {
        font-size: 0.76rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #9ca3af;
        margin-bottom: 0.35rem;
    }
    .mac-header h1 {
        font-size: 2.7rem;
        font-weight: 800;
        letter-spacing: -0.04em;
        color: #0f172a;
        margin: 0 0 0.4rem;
    }
    .mac-header p {
        color: #6b7280;
        font-size: 1rem;
        margin: 0;
    }

    .mac-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(0, 1.1fr);
        gap: 32px;
    }

    @media (max-width: 960px) {
        .mac-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    .mac-card {
        background: rgba(255,255,255,0.96);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-radius: 22px;
        padding: 30px 26px;
        border: 1px solid rgba(226,232,240,0.9);
        box-shadow: 0 16px 40px rgba(15,23,42,0.12);
    }
    .mac-card h2 {
        font-size: 1.4rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 1rem;
    }
    .mac-card-subtitle {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 1.4rem;
    }

    .mac-info {
        margin-top: 0.5rem;
        margin-bottom: 1.4rem;
    }
    .mac-info-row {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.98rem;
        color: #374151;
        margin-bottom: 10px;
    }
    .mac-info-row i {
        font-size: 1.15rem;
        color: #007aff;
        width: 22px;
        text-align: center;
    }
    .mac-info-row strong {
        font-weight: 600;
    }

    .mac-actions {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 1.4rem;
        align-items: flex-start;
        margin-left: 32px; /* sejajar dengan teks setelah ikon */
        max-width: 360px;
        width: 100%;
    }

    .mac-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        border-radius: 999px;
        padding: 0.45rem 1.1rem;
        font-weight: 600;
        font-size: 0.86rem;
        color: #0f9cf5;
        text-decoration: none;
        border: 1px solid #0f9cf5;
        background: transparent;
        box-shadow: none;
        transition: all 0.2s ease;
        width: 100%;
        position: relative;
        overflow: hidden;
    }
    .mac-btn i {
        font-size: 1.1rem;
    }
    .mac-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15,23,42,0.12);
        background: rgba(239,246,255,0.9);
    }

    /* Animasi denyut halus pada tombol */
    @keyframes mac-btn-pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(15,156,245,0.35);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(15,156,245,0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(15,156,245,0);
        }
    }
    .mac-btn {
        animation: mac-btn-pulse 1.9s ease-out infinite;
    }

    .mac-map {
        text-align: center;
    }
    .mac-map iframe {
        border: 0;
        width: 100%;
        height: 380px;
        border-radius: 18px;
        margin: 0 0 16px;
        box-shadow: 0 14px 34px rgba(15,23,42,0.24);
    }

    .mac-footer-note {
        margin-top: 1.2rem;
        font-size: 0.82rem;
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .contact-mac-wrapper {
            padding: 56px 0 64px;
        }
        .mac-container {
            padding: 16px;
        }
        .mac-header h1 {
            font-size: 2.1rem;
        }
    }
</style>

<div class="contact-mac-wrapper">
    <div class="mac-container">
        <div class="mac-header">
            <div class="mac-header-eyebrow">Support & Contact</div>
            <h1>Hubungi Deva Laundry</h1>
            <p>Kami siap membantu Anda dengan layanan terbaik setiap hari.</p>
        </div>

        <div class="mac-grid">
            <div class="mac-card">
                <h2>Informasi Kontak</h2>
                <p class="mac-card-subtitle">Silakan pilih cara tercepat untuk menghubungi kami.</p>

                <div class="mac-info">
                    <div class="mac-info-row">
                        <i class="fa-solid fa-phone"></i>
                        <span>Telepon / WhatsApp: <strong>{{ $contactPhone }}</strong></span>
                    </div>
                    <div class="mac-info-row">
                        <i class="fa-solid fa-envelope"></i>
                        <span>Email: <strong>{{ $contactEmail }}</strong></span>
                    </div>
                    <div class="mac-info-row">
                        <i class="fa-brands fa-instagram"></i>
                        <span>Instagram: <strong>{{ $contactInstagram }}</strong></span>
                    </div>
                    <div class="mac-info-row">
                        <i class="fa-brands fa-facebook"></i>
                        <span>Facebook: <strong>{{ $contactFacebook }}</strong></span>
                    </div>
                    <div class="mac-info-row">
                        <i class="fa-brands fa-tiktok"></i>
                        <span>TikTok: <strong>{{ $contactTikTok }}</strong></span>
                    </div>
                </div>

                <div class="mac-actions">
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', $contactPhone) }}"
                       target="_blank" class="mac-btn">
                        <i class="fa-brands fa-whatsapp"></i>
                        <span>Chat via WhatsApp</span>
                    </a>
                    <a href="mailto:{{ $contactEmail }}" class="mac-btn">
                        <i class="fa-solid fa-envelope"></i>
                        <span>Kirim Email</span>
                    </a>
                    <a href="{{ $contactInstagram }}" target="_blank" class="mac-btn">
                        <i class="fa-brands fa-instagram"></i>
                        <span>Buka Instagram</span>
                    </a>
                    <a href="{{ $contactFacebook }}" target="_blank" class="mac-btn">
                        <i class="fa-brands fa-facebook-f"></i>
                        <span>Buka Facebook</span>
                    </a>
                    <a href="{{ $contactTikTok }}" target="_blank" class="mac-btn">
                        <i class="fa-brands fa-tiktok"></i>
                        <span>Buka TikTok</span>
                    </a>
                </div>
            </div>

            <div class="mac-card mac-map">
                <h2>Lokasi Kami</h2>
                <p class="mac-card-subtitle">Temukan kami di Google Maps untuk antar-jemput atau drop-off cucian.</p>

                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d249.18494673429208!2d115.16754820472273!3d-8.513313035513681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd23b6ad9306f77%3A0x864fe1c076fac689!2sDeva%20Laundry!5e1!3m2!1sid!2sid!4v1761801207973!5m2!1sid!2sid"
                    allowfullscreen
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>

                <a href="{{ $contactMapsLink }}"
                   target="_blank" class="mac-btn">
                    <i class="fa-solid fa-map-location-dot"></i>
                    <span>Lihat di Google Maps</span>
                </a>

                <div class="mac-footer-note">
                    Jam operasional dan layanan antar-jemput mengikuti informasi terbaru di profil resmi kami.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://kit.fontawesome.com/a2b1f3b59d.js" crossorigin="anonymous"></script>
@endpush
