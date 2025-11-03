@extends('layouts.app')

@section('title', 'Hubungi Kami - Deva Laundry')

@section('content')
<style>
/* 🌈 macOS Clean Style */
body {
    background: linear-gradient(135deg, #eaf3ff, #ffffff);
    font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    color: #333;
}

/* 🧊 Container utama */
.mac-container {
    max-width: 1100px;
    margin: 80px auto;
    padding: 20px;
}

/* 🌟 Header */
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
.mac-header h1 {
    font-size: 2.8rem;
    font-weight: 800;
    color: #007aff;
}
.mac-header p {
    color: #666;
    font-size: 1.1rem;
}

/* 💬 Dua kolom */
.mac-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
    gap: 30px;
}

/* 📬 Kartu isi */
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
    margin-bottom: 20px;
}

/* 🔗 Info kontak */
.mac-info p {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.05rem;
    color: #444;
    margin-bottom: 12px;
}
.mac-info i {
    font-size: 1.3rem;
    color: #007aff;
}

/* 🌐 Tombol iOS Style */
.mac-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    border-radius: 50px;
    padding: 0.8rem 1.6rem;
    font-weight: 600;
    font-size: 1rem;
    color: white;
    transition: 0.25s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}
.mac-btn:hover {
    transform: translateY(-3px);
}
.mac-btn.whatsapp {
    background: linear-gradient(135deg, #34c759, #2ecf55);
}
.mac-btn.email {
    background: linear-gradient(135deg, #007aff, #0071e3);
}

/* 🗺️ Map frame */
.mac-map iframe {
    border: 0;
    width: 100%;
    height: 400px;
    border-radius: 18px;
}

/* 📱 Responsif */
@media (max-width: 768px) {
    .mac-header h1 {
        font-size: 2.2rem;
    }
}
</style>

<div class="mac-container">
    {{-- 🌟 HEADER --}}
    <div class="mac-header">
        <h1>Hubungi Kami</h1>
        <p>Kami siap membantu Anda dengan layanan terbaik setiap hari 💙</p>
    </div>

    {{-- 💬 DUA KOLOM --}}
    <div class="mac-grid">
        {{-- 💎 Informasi Kontak --}}
        <div class="mac-card">
            <h2>Informasi Kontak</h2>
            <div class="mac-info">
                <p><i class="fa-solid fa-phone"></i> Telepon / WhatsApp: <strong>{{ config('app.admin_whatsapp', '+62 853-3363-4884') }}</strong></p>
                <p><i class="fa-solid fa-envelope"></i> Email: <strong>hello@devalaundry.example</strong></p>
                <p><i class="fa-brands fa-instagram text-pink-500"></i> Instagram: <a href="https://instagram.com/devalaundry" target="_blank" class="text-blue-600 hover:underline">@devalaundry</a></p>
                <p><i class="fa-brands fa-facebook"></i> Facebook: <a href="https://facebook.com/devalaundry" target="_blank" class="text-blue-600 hover:underline">@devalaundry</a></p>
            </div>

            {{-- 🌟 Tombol Aksi --}}
            <div class="mt-4 flex flex-wrap gap-3">
                <a href="https://wa.me/{{ preg_replace('/\D/', '', config('app.admin_whatsapp', '6285333634884')) }}"
                   target="_blank" class="mac-btn whatsapp">
                    <i class="fa-brands fa-whatsapp"></i> Chat via WhatsApp
                </a>
                <a href="mailto:hello@devalaundry.example" class="mac-btn email">
                    <i class="fa-solid fa-envelope"></i> Kirim Email
                </a>
            </div>
        </div>

        {{-- 🗺️ Lokasi Kami --}}
        <div class="mac-card mac-map">
            <h2>Lokasi Kami</h2>
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d249.18494673429208!2d115.16754820472273!3d-8.513313035513681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd23b6ad9306f77%3A0x864fe1c076fac689!2sDeva%20Laundry!5e1!3m2!1sid!2sid!4v1761801207973!5m2!1sid!2sid"
                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</div>

{{-- 🔗 FontAwesome Icons --}}
@push('scripts')
<script src="https://kit.fontawesome.com/a2b1f3b59d.js" crossorigin="anonymous"></script>
@endpush
@endsection
