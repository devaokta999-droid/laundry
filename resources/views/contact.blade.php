@extends('layouts.app')

@section('title', 'Hubungi Kami - Deva Laundry')

@section('content')
<!-- 🌈 iOS Premium Split Frame Background -->
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-blue-100 py-16 px-6 flex items-center justify-center">
    <div class="max-w-6xl w-full space-y-8">

        <!-- 🌟 Header -->
        <div class="bg-white/60 backdrop-blur-2xl rounded-3xl shadow-lg border border-white/30 text-center p-10 transition-all duration-500 hover:shadow-blue-200/50">
            <h1 class="text-4xl md:text-5xl font-extrabold text-blue-700 tracking-tight mb-3">Hubungi Kami</h1>
            <p class="text-gray-600 text-lg">Kami siap membantu Anda setiap saat dengan pelayanan terbaik 💙</p>
        </div>

        <!-- 🧩 Dua Frame Terpisah -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- 💎 Frame 1: Informasi Kontak -->
            <div class="bg-white/70 backdrop-blur-2xl rounded-3xl shadow-xl border border-white/30 p-8 md:p-10 transition-transform duration-500 hover:scale-[1.02] hover:shadow-blue-200/50">
                <h2 class="text-2xl font-semibold text-blue-600 mb-5">Informasi Kontak</h2>

                <div class="space-y-4 text-gray-700 leading-relaxed">
                    <p class="flex items-center gap-3">
                        <i class="fa-solid fa-phone text-blue-600 text-xl"></i>
                        <span>Telepon / WhatsApp:
                            <strong>{{ config('app.admin_whatsapp', '+62 853-3363-4884') }}</strong>
                        </span>
                    </p>

                    <p class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope text-blue-600 text-xl"></i>
                        <span>Email: <strong>hello@devalaundry.example</strong></span>
                    </p>

                    <p class="flex items-center gap-3">
                        <i class="fa-brands fa-instagram text-pink-500 text-xl"></i>
                        <span>Instagram:
                            <a href="https://instagram.com/devalaundry" target="_blank" class="text-blue-600 hover:underline">@devalaundry</a>
                        </span>
                    </p>

                    <p class="flex items-center gap-3">
                        <i class="fa-brands fa-facebook text-blue-700 text-xl"></i>
                        <span>Facebook:
                            <a href="https://facebook.com/devalaundry" target="_blank" class="text-blue-600 hover:underline">@devalaundry</a>
                        </span>
                    </p>
                </div>

                <!-- 🌟 Tombol Aksi -->
                <div class="mt-10 flex flex-wrap gap-4">
                    <!-- WhatsApp -->
                    <a href="https://wa.me/{{ preg_replace('/\D/', '', config('app.admin_whatsapp', '6285333634884')) }}"
                       target="_blank"
                       class="ios-btn bg-green-500 hover:bg-green-600 text-white shadow-lg shadow-green-200">
                        <i class="fa-brands fa-whatsapp text-xl"></i>
                        Chat via WhatsApp
                    </a>

                    <!-- Email -->
                    <a href="mailto:hello@devalaundry.example"
                       class="ios-btn bg-blue-500 hover:bg-blue-600 text-white shadow-lg shadow-blue-200">
                        <i class="fa-solid fa-envelope text-xl"></i>
                        Chat via Email
                    </a>
                </div>
            </div>

            <!-- 💎 Frame 2: Lokasi Peta -->
            <div class="bg-white/70 backdrop-blur-2xl rounded-3xl shadow-xl border border-white/30 overflow-hidden transition-transform duration-500 hover:scale-[1.02] hover:shadow-blue-200/50">
                <h2 class="text-2xl font-semibold text-blue-600 px-8 pt-8 mb-4">Lokasi Kami</h2>
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-blue-50/30 z-10 pointer-events-none rounded-b-3xl"></div>
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d249.18494673429208!2d115.16754820472273!3d-8.513313035513681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd23b6ad9306f77%3A0x864fe1c076fac689!2sDeva%20Laundry!5e1!3m2!1sid!2sid!4v1761801207973!5m2!1sid!2sid"
                        width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"
                        class="rounded-b-3xl">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 💅 Style Kustom iOS Premium Split Edition -->
<style>
    /* 🌈 Efek Denyut Tombol */
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(59,130,246,0.4); }
        70% { transform: scale(1.05); box-shadow: 0 0 0 15px rgba(59,130,246,0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(59,130,246,0); }
    }

    .ios-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 0.9rem 1.6rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        animation: pulse 2.5s infinite;
        backdrop-filter: blur(10px);
    }

    .ios-btn:hover {
        transform: translateY(-3px) scale(1.05);
        animation: none;
    }

    /* 🌤️ Efek Premium Card Hover */
    .bg-white\/70:hover {
        background-color: rgba(255, 255, 255, 0.85);
    }

    /* 🌙 Bayangan Lembut iOS */
    .shadow-xl {
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
    }

    /* 💠 Transisi Halus */
    .transition-transform {
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }
</style>

@push('scripts')
<script src="https://kit.fontawesome.com/a2b1f3b59d.js" crossorigin="anonymous"></script>
@endpush
@endsection
