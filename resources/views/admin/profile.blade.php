@extends('layouts.app')

@section('title', 'Profil Admin - Deva Laundry')

@section('content')
<style>
    .profile-shell {
        max-width: 1480px;
        width: 100%;
        margin: 40px auto 56px;
        padding: 0 1.25rem;
        font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .profile-window {
        border-radius: 26px;
        background: radial-gradient(circle at top left, rgba(255,255,255,0.97), rgba(243,246,255,0.94));
        box-shadow: 0 28px 80px rgba(15,23,42,0.18);
        border: 1px solid rgba(255,255,255,0.9);
        overflow: hidden;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
    .profile-window-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.9rem 1.5rem;
        border-bottom: 1px solid rgba(226,232,240,0.9);
        background: linear-gradient(135deg, #f5f5f7, #e5e7eb);
    }
    .profile-traffic {
        display: flex;
        gap: .45rem;
        align-items: center;
    }
    .profile-traffic-dot {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        border: 1px solid rgba(0,0,0,0.08);
        box-shadow: 0 1px 2px rgba(0,0,0,0.08);
    }
    .profile-traffic-dot.red { background:#ff5f57; }
    .profile-traffic-dot.yellow { background:#febc2e; }
    .profile-traffic-dot.green { background:#28c840; }
    .profile-window-title {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    .profile-window-title h3 {
        font-size: 1.3rem;
        margin: 0;
        font-weight: 800;
        letter-spacing: -.3px;
        color: #111827;
    }
    .profile-window-title span {
        font-size: .85rem;
        color: #6b7280;
    }

    .profile-body {
        padding: 1.7rem 1.9rem 2.1rem;
        background: radial-gradient(circle at top left, rgba(248,250,252,0.88), rgba(239,246,255,0.94));
    }
    .profile-layout {
        display: grid;
        grid-template-columns: minmax(260px, 320px) minmax(0, 1.5fr);
        gap: 22px;
        align-items: flex-start;
    }
    @media (max-width: 992px) {
        .profile-layout {
            grid-template-columns: 1fr;
        }
    }

    .profile-card {
        border-radius: 20px;
        background: linear-gradient(145deg, rgba(255,255,255,0.99), rgba(246,248,255,0.96));
        border: 1px solid rgba(255,255,255,0.9);
        box-shadow: 0 14px 40px rgba(15,23,42,0.13);
        padding: 1.4rem 1.6rem;
        transition:
            box-shadow .25s ease,
            transform .25s ease,
            border-color .25s ease,
            background .25s ease;
    }
    .profile-card + .profile-card {
        margin-top: 1.1rem;
    }
    .profile-card:hover {
        box-shadow:
            0 20px 55px rgba(15,23,42,0.18),
            0 0 0 1px rgba(191,219,254,0.8);
        transform: translateY(-2px);
        background: linear-gradient(145deg, rgba(255,255,255,1), rgba(241,245,255,0.98));
    }

    .profile-avatar {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 1.1rem;
    }
    .profile-avatar-circle {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at 20% 20%, #4f46e5, #0ea5e9);
        color: #fff;
        font-size: 2.1rem;
        font-weight: 700;
        box-shadow: 0 12px 32px rgba(79,70,229,0.55);
        overflow: hidden;
    }
    .profile-avatar-circle img{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
    }
    .profile-avatar-meta h4 {
        margin: 0;
        font-weight: 700;
        font-size: 1.15rem;
        letter-spacing: -.25px;
    }
    .badge-role {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 600;
        background: rgba(37,99,235,0.08);
        color: #1d4ed8;
    }

    .profile-meta dl {
        margin-bottom: 0;
    }
    .profile-meta dt {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #9ca3af;
    }
    .profile-meta dd {
        font-size: .92rem;
        font-weight: 500;
        color: #111827;
    }

    .section-title {
        font-size: .9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .14em;
        color: #9ca3af;
        margin-bottom: .45rem;
    }
    .section-heading {
        font-size: 1.05rem;
        font-weight: 700;
        margin-bottom: .4rem;
        color: #111827;
    }
    .section-description {
        font-size: .84rem;
        color: #6b7280;
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    .profile-form-group label {
        font-size: .85rem;
        font-weight: 600;
        color: #374151;
    }
    .profile-form-group input,
    .profile-form-group textarea,
    .profile-form-group select {
        border-radius: 12px;
        border: 1px solid rgba(148,163,184,0.55);
        font-size: .9rem;
        padding: .55rem .8rem;
        background: rgba(255,255,255,0.9);
        box-shadow: 0 1px 2px rgba(15,23,42,0.02);
    }
    .profile-form-group input:focus,
    .profile-form-group textarea:focus,
    .profile-form-group select:focus {
        border-color: #3b82f6;
        box-shadow:
            0 0 0 1px rgba(59,130,246,0.18),
            0 0 0 4px rgba(191,219,254,0.9);
        outline: none;
    }

    .profile-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 1.4rem;
    }
    .btn-mac-primary {
        border-radius: 999px;
        padding: .6rem 1.8rem;
        font-weight: 600;
        border: none;
        background: linear-gradient(135deg, #007aff, #0051d4);
        color: #fff;
        box-shadow: 0 10px 24px rgba(37,99,235,0.4);
    }
    .btn-mac-primary:hover {
        background: linear-gradient(135deg, #0a84ff, #1d4ed8);
        box-shadow: 0 14px 32px rgba(37,99,235,0.55);
        transform: translateY(-1px);
    }

    /* Promo & payment item cards */
    .promo-item,
    .payment-item {
        border-radius: 16px !important;
        border: 1px solid rgba(226,232,240,0.9) !important;
        background: linear-gradient(145deg, rgba(255,255,255,0.98), rgba(248,250,252,0.96)) !important;
        box-shadow: 0 10px 26px rgba(15,23,42,0.08);
    }
    .promo-item strong,
    .payment-item strong {
        font-size: .9rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #6b7280;
    }
    .promo-item .btn-outline-danger,
    .payment-item .btn-outline-danger {
        border-radius: 999px;
        padding: .25rem .9rem;
        font-size: .72rem;
    }

    .profile-card .btn.btn-outline-primary.btn-sm {
        border-radius: 999px;
        padding-inline: 1.1rem;
        font-weight: 600;
    }

    .payment-item {
        padding: .55rem .8rem !important;
    }
    .payment-item .profile-form-group label {
        font-size: .78rem;
    }
    .payment-item .profile-form-group input[type="text"] {
        padding-top: .3rem;
        padding-bottom: .3rem;
    }
    .payment-item .logo-preview {
        max-height: 32px;
    }
    .payment-item-header strong {
        font-size: .78rem;
    }
    .payment-item-header .btn {
        padding: .15rem .7rem;
        font-size: .7rem;
    }
    @media (min-width: 768px) {
        .payment-item-fields {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(0, 1.2fr);
            gap: .75rem;
        }
    }

    .profile-card--payment {
        padding: 1rem 1.2rem;
    }
    .profile-card--payment .section-heading {
        margin-bottom: .25rem;
    }
    .profile-card--payment .section-description {
        margin-bottom: .7rem;
        font-size: .8rem;
    }
    @media (min-width: 992px) {
        #payment-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: .75rem;
        }
        #payment-list .payment-item {
            margin-bottom: 0 !important;
        }
    }

    .profile-card--branding {
        padding: 1.1rem 1.3rem;
    }
    .profile-card--branding .section-heading {
        margin-bottom: .25rem;
    }
    .profile-card--branding .section-description {
        margin-bottom: .65rem;
        font-size: .8rem;
    }

    .profile-card--home {
        padding: 1.1rem 1.35rem;
    }
    .profile-card--home .section-heading {
        margin-bottom: .25rem;
    }
    .profile-card--home .section-description {
        margin-bottom: .7rem;
        font-size: .8rem;
    }

    .promo-item {
        padding: .6rem .8rem !important;
    }
    .promo-item-header strong {
        font-size: .8rem;
    }
    .promo-item-header .btn {
        padding: .15rem .7rem;
        font-size: .7rem;
    }
    .promo-item .profile-form-group label {
        font-size: .8rem;
    }
    .promo-item .profile-form-group textarea,
    .promo-item .profile-form-group input[type="text"] {
        padding-top: .3rem;
        padding-bottom: .3rem;
        font-size: .82rem;
    }
    @media (min-width: 992px) {
        #promo-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: .75rem;
        }
        #promo-list .promo-item {
            margin-bottom: 0 !important;
        }
    }

    /* Tabs untuk mengelompokkan form profil */
    .profile-tabs {
        margin-top: .4rem;
        margin-bottom: 1rem;
        padding: .55rem .55rem .65rem;
        border-radius: 20px;
        background: linear-gradient(145deg, rgba(255,255,255,0.98), rgba(243,246,255,0.96));
        box-shadow:
            0 16px 40px rgba(15,23,42,0.16),
            0 0 0 1px rgba(226,232,240,0.9);
    }
    .profile-tab {
        width: 100%;
        border: none;
        border-radius: 14px;
        padding: .45rem .6rem;
        font-size: .8rem;
        font-weight: 600;
        background: transparent;
        color: #6b7280;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: .55rem;
    }
    .profile-tab-icon {
        width: 22px;
        height: 22px;
        border-radius: 999px;
        background: rgba(148,163,184,0.16);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 0 1px rgba(226,232,240,0.85);
        flex-shrink: 0;
    }
    .profile-tab-icon svg {
        width: 12px;
        height: 12px;
        stroke-width: 2;
    }
    .profile-tab-label {
        flex: 1;
        text-align: left;
    }
    .profile-tab.active {
        background: linear-gradient(135deg, rgba(0,122,255,0.12), rgba(37,99,235,0.18));
        color: #0f172a;
        box-shadow:
            0 10px 26px rgba(37,99,235,0.35),
            0 0 0 1px rgba(191,219,254,0.95);
    }
    .profile-tab.active .profile-tab-icon {
        background: rgba(59,130,246,0.16);
        box-shadow: 0 0 0 1px rgba(191,219,254,0.95);
        color: #2563eb;
    }

    .profile-section-card {
        display: none;
    }
    .profile-section-card.is-active {
        display: block;
    }
</style>

<div class="profile-shell">
    @if(session('success'))
        {{-- Disimpan sebagai teks tersembunyi, notifikasi ditampilkan via modal pop up --}}
        <div id="profileSuccessMessage" class="d-none">{{ session('success') }}</div>
    @endif

    <div class="profile-window">
        <div class="profile-window-header">
            <div class="d-flex align-items-center gap-3">
                <div class="profile-traffic" aria-hidden="true">
                    <span class="profile-traffic-dot red"></span>
                    <span class="profile-traffic-dot yellow"></span>
                    <span class="profile-traffic-dot green"></span>
                </div>
                <div class="profile-window-title">
                    <h3>Profil Admin</h3>
                    <span>Kelola identitas & tampilan Deva Laundry</span>
                </div>
            </div>
        </div>

        <div class="profile-body">
            <div class="profile-layout">
                {{-- Kolom kiri: kartu profil & snapshot --}}
                <div>
                    <div class="profile-card mb-3">
                        <div class="profile-avatar">
                            <div class="profile-avatar-circle">
                                @if(!empty($user->avatar))
                                    <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}">
                                @else
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="profile-avatar-meta">
                                <h4>{{ $user->name }}</h4>
                                <span class="badge-role">
                                    <span style="width:7px;height:7px;border-radius:999px;background:#22c55e;display:inline-block;"></span>
                                    {{ ucfirst($user->role ?? 'admin') }}
                                </span>
                            </div>
                        </div>

                        <div class="profile-meta">
                            <dl class="row mb-0">
                                <dt class="col-4">Email</dt>
                                <dd class="col-8">{{ $user->email }}</dd>

                                @if(!empty($user->phone))
                                    <dt class="col-4 mt-2">Telepon</dt>
                                    <dd class="col-8 mt-2">{{ $user->phone }}</dd>
                                @endif

                                @if(!empty($user->address))
                                    <dt class="col-4 mt-2">Alamat</dt>
                                    <dd class="col-8 mt-2">{{ $user->address }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>

                    <div class="profile-tabs">
                        <button type="button" class="profile-tab active" data-profile-section-button="contact">
                            <span class="profile-tab-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M5 4h14v14H6.5L5 19.5z" />
                                    <path d="M8 9h8" />
                                    <path d="M8 13h5" />
                                </svg>
                            </span>
                            <span class="profile-tab-label">Kontak</span>
                        </button>
                        <button type="button" class="profile-tab" data-profile-section-button="branding">
                            <span class="profile-tab-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <circle cx="7.5" cy="8.5" r="2.5" />
                                    <path d="M3 19a4.5 4.5 0 0 1 9 0" />
                                    <path d="M15 5h6" />
                                    <path d="M15 9h6" />
                                    <path d="M15 13h4" />
                                </svg>
                            </span>
                            <span class="profile-tab-label">Branding &amp; Tentang</span>
                        </button>
                        <button type="button" class="profile-tab" data-profile-section-button="home">
                            <span class="profile-tab-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M3 11.5 12 4l9 7.5" />
                                    <path d="M5 11v9h14v-9" />
                                </svg>
                            </span>
                            <span class="profile-tab-label">Beranda</span>
                        </button>
                        <button type="button" class="profile-tab" data-profile-section-button="promo">
                            <span class="profile-tab-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <path d="M5 5h9l5 5-9 9-5-5z" />
                                    <path d="M7.5 7.5h.01" />
                                </svg>
                            </span>
                            <span class="profile-tab-label">Promo</span>
                        </button>
                        <button type="button" class="profile-tab" data-profile-section-button="payment">
                            <span class="profile-tab-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                    <path d="M3 10h18" />
                                    <path d="M8 15h3" />
                                </svg>
                            </span>
                            <span class="profile-tab-label">Payment</span>
                        </button>
                    </div>

                    <div class="profile-card">
                        <div class="section-title">Snapshot</div>
                        <div class="section-description mb-2">
                            Informasi ini akan muncul di area publik (Beranda, Tentang, Kontak) sebagai identitas resmi Deva Laundry.
                        </div>
                        <ul class="mb-0" style="font-size:.84rem;color:#4b5563;padding-left:1.1rem;">
                            <li>Logo digunakan di sidebar, tab browser, dan header.</li>
                            <li>Kontak dipakai di footer dan halaman Kontak.</li>
                            <li>Promo tampil sebagai highlight di beranda.</li>
                        </ul>
                    </div>
                </div>

                {{-- Kolom kanan: form pengaturan --}}
                <div>
                    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-4" data-section-column="contact">
                                <div class="profile-card h-100 profile-section-card is-active" data-section="contact">
                                    <div class="section-title">Kontak Publik</div>
                                    <div class="section-heading">Informasi Kontak & Sosial</div>
                                    <p class="section-description">
                                        Data ini muncul di halaman Kontak dan ikon media sosial di beranda.
                                    </p>

                                    <div class="profile-form-group mb-3">
                                        <label>Telepon / WhatsApp</label>
                                        <input type="text" name="contact_phone" class="form-control"
                                               value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
                                    </div>

                                    <div class="profile-form-group mb-3">
                                        <label>Email</label>
                                        <input type="text" name="contact_email" class="form-control"
                                               value="{{ old('contact_email', $settings['contact_email'] ?? '') }}">
                                    </div>

                                    <div class="profile-form-group mb-3">
                                        <label>Instagram</label>
                                        <input type="text" name="contact_instagram" class="form-control"
                                               value="{{ old('contact_instagram', $settings['contact_instagram'] ?? '') }}">
                                    </div>

                                    <div class="profile-form-group mb-3">
                                        <label>Facebook</label>
                                        <input type="text" name="contact_facebook" class="form-control"
                                               value="{{ old('contact_facebook', $settings['contact_facebook'] ?? '') }}">
                                    </div>

                                    <div class="profile-form-group mb-3">
                                        <label>TikTok</label>
                                        <input type="text" name="contact_tiktok" class="form-control"
                                               value="{{ old('contact_tiktok', $settings['contact_tiktok'] ?? '') }}">
                                    </div>

                                    <div class="profile-form-group mb-0">
                                        <label>Link Google Maps</label>
                                        <input type="text" name="contact_maps_link" class="form-control"
                                               value="{{ old('contact_maps_link', $settings['contact_maps_link'] ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4" data-section-column="branding">
                                <div class="profile-card h-100 profile-section-card profile-card--branding" data-section="branding">
                                    <div class="section-title">Branding</div>
                                    <div class="section-heading">Logo & About (Visi, Misi, Lokasi)</div>
                                    <p class="section-description">
                                        Ubah logo dan teks About yang akan tampil di beranda dan halaman Tentang Kami.
                                    </p>

                                    @php
                                        $logoPath = $settings['logo_path'] ?? 'header.png';
                                    @endphp
                                    <div class="mb-3 text-center">
                                        <img src="{{ asset('images/'.$logoPath) }}" alt="Logo Saat Ini" class="rounded-3 shadow-sm" style="max-width:140px;">
                                        <div class="text-muted mt-1" style="font-size:.8rem;">Logo saat ini</div>
                                    </div>

                                    <div class="profile-form-group mb-3">
                                        <label>Ganti Logo (PNG/JPG)</label>
                                        <input type="file" name="logo" class="form-control">
                                        <div class="form-text">Biarkan kosong jika tidak ingin mengganti logo.</div>
                                    </div>

                            <div class="profile-form-group mb-3">
                                <label>Alamat / Lokasi Pendek</label>
                                <textarea name="about_location" class="form-control" rows="2">{{ old('about_location', $settings['about_location'] ?? '') }}</textarea>
                            </div>

                            <hr class="my-3">
                            <div class="section-heading mb-2">Header Halaman Tentang</div>
                            <div class="section-description">
                                Atur judul besar dan tagline pendek yang tampil di bagian atas halaman Tentang.
                            </div>
                            <div class="profile-form-group mb-2">
                                <label>Judul Header</label>
                                <input type="text" name="about_hero_title" class="form-control"
                                       value="{{ old('about_hero_title', $settings['about_hero_title'] ?? 'Tentang Deva Laundry') }}">
                            </div>
                            <div class="profile-form-group mb-0">
                                <label>Tagline Pendek</label>
                                <input type="text" name="about_hero_tagline" class="form-control"
                                       value="{{ old('about_hero_tagline', $settings['about_hero_tagline'] ?? 'Bersih • Rapi • Wangi • Tepat Waktu') }}">
                            </div>

                            <hr class="my-3">
                            <div class="section-heading mb-2">Visi &amp; Misi Kami</div>
                            <div class="section-description">
                                Tampilkan visi dan misi Deva Laundry yang akan muncul di halaman Tentang Kami.
                            </div>

                            <div class="profile-form-group mb-3">
                                <label>Visi Kami</label>
                                <textarea name="about_vision" class="form-control" rows="2">{{ old('about_vision', $settings['about_vision'] ?? '') }}</textarea>
                                <small class="text-muted">Isi singkat visi utama bisnis kamu.</small>
                            </div>

                            <div class="profile-form-group mb-3">
                                <label>Misi Kami</label>
                                <textarea name="about_mission" class="form-control" rows="3">{{ old('about_mission', $settings['about_mission'] ?? '') }}</textarea>
                                <small class="text-muted">Pisahkan setiap misi dengan enter supaya tampil sebagai list.</small>
                            </div>

                            <hr class="my-3">
                            <div class="section-heading mb-2">Kenapa Pilih Deva Laundry?</div>
                            <div class="section-description">
                                Ubah judul dan isi bagian alasan kenapa pelanggan harus memilih Deva Laundry.
                            </div>

                            <div class="profile-form-group mb-2">
                                <label>Judul</label>
                                <input type="text" name="about_why_title" class="form-control"
                                       value="{{ old('about_why_title', $settings['about_why_title'] ?? 'Kenapa Pilih Deva Laundry?') }}">
                            </div>
                            <div class="profile-form-group mb-0">
                                <label>Isi (setiap baris = 1 paragraf)</label>
                                <textarea name="about_why_text" class="form-control" rows="3">{{ old('about_why_text', $settings['about_why_text'] ?? '') }}</textarea>
                                <small class="text-muted">Teks ini akan muncul di kolom kanan halaman Tentang Kami.</small>
                            </div>

                            <hr class="my-3">
                            <div class="section-heading mb-2">Jam Operasional (Halaman Tentang)</div>
                            <div class="section-description">
                                Atur jam buka tutup laundry yang tampil di halaman Tentang Kami.
                            </div>

                            <div class="profile-form-group mb-0">
                                <label>Jam Operasional</label>
                                <textarea name="about_hours" class="form-control" rows="2">{{ old('about_hours', $settings['about_hours'] ?? "Senin — Minggu\n08.30 – 17.00 WITA") }}</textarea>
                                <small class="text-muted">Gunakan enter untuk membuat baris baru (misalnya hari dan jam di baris terpisah).</small>
                            </div>
                                </div>
                            </div>
                        </div>

                        <div class="profile-card mb-3 profile-section-card profile-card--home" data-section="home">
                            <div class="section-title">Beranda</div>
                            <div class="section-heading">Hero Halaman Beranda</div>
                            <p class="section-description">
                                Ubah teks utama di banner biru pada halaman beranda (judul besar Deva Laundry,
                                subjudul, bullet point, dan teks kartu di sebelah kanan).
                            </p>

                            <div class="profile-form-group mb-2">
                                <label>Label Kecil Atas (Eyebrow)</label>
                                <input type="text" name="hero_eyebrow" class="form-control"
                                       value="{{ old('hero_eyebrow', $settings['hero_eyebrow'] ?? 'Premium Laundry Experience') }}">
                            </div>
                            <div class="profile-form-group mb-2">
                                <label>Judul Besar</label>
                                <input type="text" name="hero_title" class="form-control"
                                       value="{{ old('hero_title', $settings['hero_title'] ?? 'Deva Laundry') }}">
                            </div>
                            <div class="profile-form-group mb-3">
                                <label>Subjudul / Deskripsi Singkat</label>
                                <textarea name="hero_subtitle" class="form-control" rows="2">{{ old('hero_subtitle', $settings['hero_subtitle'] ?? '') }}</textarea>
                            </div>

                            <div class="profile-form-group mb-3">
                                <label>Bullet Point (setiap baris = 1 poin)</label>
                                <textarea name="hero_bullets" class="form-control" rows="2">{{ old('hero_bullets', $settings['hero_bullets'] ?? '') }}</textarea>
                                <small class="text-muted">Contoh: Antar-jemput area sekitar, Estimasi selesai tepat waktu, dll.</small>
                            </div>

                            <hr class="my-2">
                            <div class="section-heading mb-2">Kartu Kanan Hero</div>
                            <div class="profile-form-group mb-2">
                                <label>Badge Kecil (atas kartu)</label>
                                <input type="text" name="hero_card_badge" class="form-control"
                                       value="{{ old('hero_card_badge', $settings['hero_card_badge'] ?? '') }}">
                            </div>
                            <div class="profile-form-group mb-2">
                                <label>Judul Kartu</label>
                                <input type="text" name="hero_card_title" class="form-control"
                                       value="{{ old('hero_card_title', $settings['hero_card_title'] ?? '') }}">
                            </div>
                            <div class="profile-form-group mb-0">
                                <label>Isi Kartu (setiap baris = 1 paragraf)</label>
                                <textarea name="hero_card_text" class="form-control" rows="2">{{ old('hero_card_text', $settings['hero_card_text'] ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="profile-card mb-3 profile-section-card" data-section="promo">
                            <div class="section-title">Promo</div>
                            <div class="section-heading">Promo di Halaman Beranda</div>
                            <p class="section-description">
                                Tambah, edit, atau hapus promo yang tampil di bagian Promo Spesial beranda.
                            </p>

                            <div id="promo-list">
                                @php
                                    $oldTitles = old('promo_titles', []);
                                    $oldDescs = old('promo_descs', []);
                                    $useOld = count($oldTitles) > 0 || count($oldDescs) > 0;
                                    $promoItems = $useOld ? collect($oldTitles)->map(function ($t, $i) use ($oldDescs) {
                                        return ['title' => $t, 'desc' => $oldDescs[$i] ?? ''];
                                    })->all() : ($promos ?? []);
                                @endphp

                                @forelse($promoItems as $index => $promo)
                                    <div class="border rounded-3 mb-3 promo-item bg-light bg-opacity-60">
                                        <div class="d-flex justify-content-between align-items-center mb-2 promo-item-header">
                                            <strong>Promo {{ $index + 1 }}</strong>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-promo">Hapus</button>
                                        </div>
                                        <div class="profile-form-group mb-2">
                                            <label>Judul Promo</label>
                                            <input type="text" name="promo_titles[]" class="form-control"
                                                   value="{{ $promo['title'] ?? '' }}">
                                        </div>
                                        <div class="profile-form-group mb-0">
                                            <label>Deskripsi Promo</label>
                                            <textarea name="promo_descs[]" class="form-control" rows="2">{{ $promo['desc'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                @empty
                                    <div class="border rounded-3 mb-3 promo-item bg-light bg-opacity-60">
                                        <div class="d-flex justify-content-between align-items-center mb-2 promo-item-header">
                                            <strong>Promo 1</strong>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-promo">Hapus</button>
                                        </div>
                                        <div class="profile-form-group mb-2">
                                            <label>Judul Promo</label>
                                            <input type="text" name="promo_titles[]" class="form-control">
                                        </div>
                                        <div class="profile-form-group mb-0">
                                            <label>Deskripsi Promo</label>
                                            <textarea name="promo_descs[]" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btn-add-promo">+ Tambah Promo</button>
                        </div>

                        <div class="profile-card mb-0 profile-section-card profile-card--payment" data-section="payment">
                            <div class="section-title">Payment</div>
                            <div class="section-heading">Metode Pembayaran</div>
                            <p class="section-description">
                                Atur logo dan nama metode pembayaran yang tampil di beranda pada bagian Metode Pembayaran.
                                Gunakan URL logo yang valid (misalnya dari CDN atau storage aplikasi).
                            </p>

                            <div id="payment-list">
                                @php
                                    $oldPaymentNames = old('payment_names', []);
                                    $oldExistingLogos = old('existing_payment_logos', []);
                                    $useOldPayments = count($oldPaymentNames) > 0 || count($oldExistingLogos) > 0;
                                    $paymentItems = $useOldPayments
                                        ? collect($oldPaymentNames)->map(function ($name, $i) use ($oldExistingLogos) {
                                            return [
                                                'name' => $name,
                                                'logo_raw' => $oldExistingLogos[$i] ?? '',
                                            ];
                                        })->all()
                                        : ($paymentMethods ?? []);
                                @endphp

                                @forelse($paymentItems as $index => $payment)
                                    @php
                                        $storedLogo = $payment['logo_path'] ?? $payment['logo_url'] ?? ($payment['logo_raw'] ?? '');
                                        $previewLogo = $storedLogo
                                            ? (preg_match('#^https?://#i', $storedLogo) ? $storedLogo : asset($storedLogo))
                                            : '';
                                    @endphp
                                    <div class="border rounded-3 mb-3 payment-item bg-light bg-opacity-60">
                                        <div class="d-flex justify-content-between align-items-center mb-2 payment-item-header">
                                            <strong>Metode {{ $index + 1 }}</strong>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-payment">Hapus</button>
                                        </div>
                                        <div class="payment-item-fields">
                                            <div class="profile-form-group mb-2 mb-md-0">
                                                <label>Nama Metode Pembayaran</label>
                                                <input type="text" name="payment_names[]" class="form-control"
                                                       value="{{ $payment['name'] ?? '' }}">
                                            </div>
                                            <div class="profile-form-group mb-0">
                                                <label>Logo (upload gambar)</label>
                                                @if($previewLogo)
                                                    <div class="mb-2">
                                                        <img src="{{ $previewLogo }}" alt="Logo {{ $payment['name'] ?? '' }}" class="logo-preview">
                                                    </div>
                                                @endif
                                                <input type="hidden" name="existing_payment_logos[]" value="{{ $storedLogo }}">
                                                <input type="file" name="payment_logos[]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="border rounded-3 mb-3 payment-item bg-light bg-opacity-60">
                                        <div class="d-flex justify-content-between align-items-center mb-2 payment-item-header">
                                            <strong>Metode 1</strong>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-remove-payment">Hapus</button>
                                        </div>
                                        <div class="payment-item-fields">
                                            <div class="profile-form-group mb-2 mb-md-0">
                                                <label>Nama Metode Pembayaran</label>
                                                <input type="text" name="payment_names[]" class="form-control">
                                            </div>
                                            <div class="profile-form-group mb-0">
                                                <label>Logo (upload gambar)</label>
                                                <input type="hidden" name="existing_payment_logos[]" value="">
                                                <input type="file" name="payment_logos[]" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="btn-add-payment">+ Tambah Metode Pembayaran</button>
                        </div>

                        <div class="profile-footer">
                            <button type="submit" class="btn btn-mac-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tabs untuk mengelompokkan form profil
        (function () {
            const tabButtons = document.querySelectorAll('[data-profile-section-button]');
            const sectionCards = document.querySelectorAll('.profile-section-card');

            function activateSection(key) {
                sectionCards.forEach(function (card) {
                    const match = card.getAttribute('data-section') === key;
                    card.classList.toggle('is-active', match);

                    const container = card.closest('[data-section-column]') || card;
                    if (match) {
                        container.style.display = '';
                    } else {
                        container.style.display = 'none';
                    }
                });

                tabButtons.forEach(function (btn) {
                    const match = btn.getAttribute('data-profile-section-button') === key;
                    btn.classList.toggle('active', match);
                });
            }

            if (tabButtons.length && sectionCards.length) {
                const defaultKey = 'contact';
                activateSection(defaultKey);

                tabButtons.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        const key = this.getAttribute('data-profile-section-button');
                        if (!key) return;
                        activateSection(key);
                    });
                });
            }
        })();

        // Modal pop up otomatis untuk notifikasi sukses profil/contact/about/promo
        try {
            var msgEl = document.getElementById('profileSuccessMessage');
            if (msgEl && window.Swal) {
                var text = msgEl.textContent || msgEl.innerText || 'Pengaturan berhasil diperbarui.';
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: text,
                    timer: 2200,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
        } catch (e) {}

        // Script promo yang sudah ada
        const list = document.getElementById('promo-list');
        const addBtn = document.getElementById('btn-add-promo');

        function attachRemoveHandlers(scope) {
            (scope || document).querySelectorAll('.btn-remove-promo').forEach(function (btn) {
                btn.onclick = function () {
                    const item = this.closest('.promo-item');
                    if (!item) return;
                    if (list.querySelectorAll('.promo-item').length <= 1) {
                        item.querySelectorAll('input, textarea').forEach(el => el.value = '');
                        return;
                    }
                    item.remove();
                };
            });
        }

        if (addBtn) {
            addBtn.addEventListener('click', function () {
                const count = list.querySelectorAll('.promo-item').length;
                const wrapper = document.createElement('div');
                wrapper.className = 'border rounded-3 mb-3 promo-item bg-light bg-opacity-60';
                wrapper.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2 promo-item-header">
                        <strong>Promo ${count + 1}</strong>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-promo">Hapus</button>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Judul Promo</label>
                        <input type="text" name="promo_titles[]" class="form-control">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Deskripsi Promo</label>
                        <textarea name="promo_descs[]" class="form-control" rows="2"></textarea>
                    </div>
                `;
                list.appendChild(wrapper);
                attachRemoveHandlers(wrapper);
            });
        }

        attachRemoveHandlers(document);

        // Script metode pembayaran
        const paymentList = document.getElementById('payment-list');
        const addPaymentBtn = document.getElementById('btn-add-payment');

        function attachPaymentRemoveHandlers(scope) {
            (scope || document).querySelectorAll('.btn-remove-payment').forEach(function (btn) {
                btn.onclick = function () {
                    const item = this.closest('.payment-item');
                    if (!item || !paymentList) return;
                    if (paymentList.querySelectorAll('.payment-item').length <= 1) {
                        item.querySelectorAll('input').forEach(el => el.value = '');
                        return;
                    }
                    item.remove();
                };
            });
        }

        if (addPaymentBtn && paymentList) {
            addPaymentBtn.addEventListener('click', function () {
                const count = paymentList.querySelectorAll('.payment-item').length;
                const wrapper = document.createElement('div');
                wrapper.className = 'border rounded-3 mb-3 payment-item bg-light bg-opacity-60';
                wrapper.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>Metode ${count + 1}</strong>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-payment">Hapus</button>
                    </div>
                    <div class="payment-item-fields">
                        <div class="mb-2 mb-md-0">
                            <label class="form-label">Nama Metode Pembayaran</label>
                            <input type="text" name="payment_names[]" class="form-control">
                        </div>
                        <div class="mb-0">
                            <label class="form-label">Logo (upload gambar)</label>
                            <input type="hidden" name="existing_payment_logos[]" value="">
                            <input type="file" name="payment_logos[]" class="form-control">
                        </div>
                    </div>
                `;
                paymentList.appendChild(wrapper);
                attachPaymentRemoveHandlers(wrapper);
            });
        }

        attachPaymentRemoveHandlers(document);
    });
</script>
@endpush
