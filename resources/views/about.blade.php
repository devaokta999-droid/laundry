@extends('layouts.app')

@section('title', 'Tentang Kami - Deva Laundry')

@section('content')
<style>
    .about-shell {
        max-width: 1720px;
        margin: 72px auto 72px;
        padding: 0 16px;
    }

    .about-header {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(0, 1.05fr);
        gap: 32px;
        align-items: center;
        background: rgba(255,255,255,0.92);
        border-radius: 28px;
        padding: 38px 34px;
        border: 1px solid rgba(148,163,184,0.28);
        box-shadow: 0 24px 70px rgba(15,23,42,0.18);
        margin-bottom: 40px;
    }

    .about-header-left {
        text-align: left;
    }

    .about-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 4px 11px;
        border-radius: 999px;
        background: rgba(15,23,42,0.03);
        border: 1px solid rgba(148,163,184,0.35);
        font-size: 0.78rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #6b7280;
        margin-bottom: 0.6rem;
    }

    .about-pill-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34,197,94,0.25);
    }

    .about-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 0.35rem;
    }

    .about-subtitle {
        color: #4b5563;
        font-size: 1rem;
        margin-bottom: 0.3rem;
    }

    .about-tagline {
        color: #6b7280;
        font-size: 0.9rem;
    }

    .about-hero-logo {
        width: 100%;
        max-width: 420px;
        border-radius: 24px;
        box-shadow: 0 24px 50px rgba(15,23,42,0.25);
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    .about-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
        gap: 32px;
    }

    .about-card {
        background: rgba(255,255,255,0.96);
        border-radius: 22px;
        padding: 30px 26px;
        border: 1px solid rgba(226,232,240,0.9);
        box-shadow: 0 16px 40px rgba(15,23,42,0.12);
    }

    .about-card h2 {
        font-size: 1.5rem;
        color: #007aff;
        margin-bottom: 14px;
        font-weight: 700;
    }

    .about-card ul {
        padding-left: 18px;
    }

    .about-card li {
        margin-bottom: 8px;
        color: #444;
        font-size: 0.95rem;
    }

    .about-hours-row {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.95rem;
        color: #374151;
    }

    .about-hours-time {
        flex: 1;
    }

    .about-contact-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        border-radius: 999px;
        padding: 0.7rem 1.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        color: #ffffff;
        background: linear-gradient(135deg, #0a84ff, #0054d6);
        text-decoration: none;
        box-shadow: 0 10px 26px rgba(15,23,42,0.22);
    }

    .about-contact-btn:hover {
        background: linear-gradient(135deg, #4ea9ff, #0a84ff);
        color: #ffffff;
    }

    .about-team {
        margin-top: 56px;
        text-align: center;
    }

    .about-team h2 {
        font-size: 2rem;
        font-weight: 800;
        color: #007aff;
        margin-bottom: 28px;
    }

    .about-team-grid {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 24px;
    }

    .about-member {
        background: rgba(255,255,255,0.96);
        border-radius: 18px;
        padding: 20px;
        width: 210px;
        text-align: center;
        border: 1px solid rgba(226,232,240,0.9);
        box-shadow: 0 10px 26px rgba(15,23,42,0.08);
    }

    .about-member img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.85);
        margin-bottom: 10px;
    }

    .about-member-name {
        font-size: 1rem;
        font-weight: 600;
        color: #111827;
    }

    .about-member-role {
        font-size: 0.9rem;
        color: #007aff;
        font-weight: 500;
        margin-bottom: 6px;
    }

    .about-member-desc {
        font-size: 0.85rem;
        color: #4b5563;
    }

    @media (max-width: 960px) {
        .about-header {
            grid-template-columns: minmax(0,1fr);
        }

        .about-title {
            font-size: 2.2rem;
        }
    }
</style>

<div class="about-shell">
    @php
        $aboutLogo = \App\Models\SiteSetting::getValue('logo_path', 'header.png');
        $hoursText = \App\Models\SiteSetting::getValue('about_hours', implode(PHP_EOL, [
            'Senin – Minggu',
            '08.30 – 17.00 WITA',
        ]));
        $hoursRows = collect(preg_split('/\r\n|\r|\n/', $hoursText))
            ->map(fn($line) => trim($line))
            ->filter()
            ->values();
    @endphp

    <div class="about-header">
        <div class="about-header-left">
            <div class="about-pill">
                <span class="about-pill-dot"></span>
                TENTANG DEVA LAUNDRY
            </div>
            <div class="about-title">{{ $aboutHeroTitle }}</div>
            <p class="about-subtitle">{{ $aboutHeroTagline }}</p>
            <p class="about-tagline">
                Laundry harian sampai pakaian spesial, kami tangani dengan standar yang konsisten dan rapi.
            </p>
        </div>
        <div>
            <img src="{{ asset('images/' . $aboutLogo) }}" alt="Logo Deva Laundry" class="about-hero-logo">
        </div>
    </div>

    <div class="about-grid">
        <div class="about-card">
            <h2>Visi Kami</h2>
            <p>{{ $vision }}</p>

            <h2 class="mt-4">Misi Kami</h2>
            <ul>
                @foreach($missions as $misi)
                    <li>{{ $misi }}</li>
                @endforeach
            </ul>

            <div style="margin-top:22px; border-top:1px solid #e5e7eb; padding-top:14px;">
                <h3 style="color:#007aff; font-weight:600; font-size:1.05rem;">Lokasi Kami</h3>
                <p style="font-size:0.95rem; color:#4b5563;">{{ $locationText }}</p>
            </div>
        </div>

        <div class="about-card">
            <h2>{{ $whyTitle }}</h2>
            @foreach($whyParagraphs as $p)
                <p style="font-size:0.96rem; color:#374151;">{{ $p }}</p>
            @endforeach

            <div class="mt-3">
                <a href="{{ route('contact') }}" class="about-contact-btn">
                    <i class="fa-solid fa-phone"></i>
                    <span>Hubungi Kami</span>
                </a>
            </div>

            <div style="margin-top:24px; padding-top:16px; border-top:1px solid #e5e7eb;">
                <h3 style="font-size:1.05rem; font-weight:700; color:#007aff; margin-bottom:10px;">Jam Operasional</h3>
                @foreach($hoursRows as $line)
                    <div class="about-hours-row">
                        <span class="about-hours-time">{{ $line }}</span>
                    </div>
                @endforeach
                <p style="margin-top:8px; color:#6b7280; font-size:0.85rem;">
                    Silakan datang pada jam tersebut untuk drop-off langsung di outlet atau layanan antar-jemput.
                </p>
            </div>
        </div>
    </div>

    <div class="about-team">
        <h2>Tim Profesional Kami</h2>
        <div class="about-team-grid">
            @forelse($teams as $tim)
                <div class="about-member">
                    @if($tim->photo)
                        <img src="{{ asset('images/' . $tim->photo) }}" alt="{{ $tim->name }}">
                    @else
                        <img src="{{ asset('images/' . $aboutLogo) }}" alt="{{ $tim->name }}">
                    @endif
                    <div class="about-member-name">{{ $tim->name }}</div>
                    <div class="about-member-role">{{ $tim->position }}</div>
                    <div class="about-member-desc">{{ $tim->description }}</div>
                </div>
            @empty
                <div class="about-member">
                    <img src="{{ asset('images/' . $aboutLogo) }}" alt="Deva Laundry">
                    <div class="about-member-name">Tim Deva Laundry</div>
                    <div class="about-member-role">Profesional & Berpengalaman</div>
                    <div class="about-member-desc">
                        Tim kami siap memberikan layanan terbaik untuk setiap pelanggan.
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script src="https://kit.fontawesome.com/a2b1f3b59d.js" crossorigin="anonymous"></script>
@endpush
@endsection

