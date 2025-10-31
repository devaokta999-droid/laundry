@extends('layouts.app')

@section('title', 'Tentang Kami - Deva Laundry')

@section('content')
<!-- 🌈 Background gaya macOS Pro -->
<div class="min-h-screen bg-gradient-to-br from-[#eef2ff] via-[#f8fbff] to-[#dfe8ff] py-20 px-6">

    <div class="max-w-6xl mx-auto bg-white/30 backdrop-blur-2xl border border-white/40 
                shadow-[0_8px_32px_rgba(31,38,135,0.1)] rounded-[3rem] p-10 md:p-16 
                transition-all duration-700 hover:shadow-[0_12px_45px_rgba(31,38,135,0.2)]">

        <!-- 🪞 Header -->
        <div class="text-center mb-16">
            <img src="{{ asset('images/hero-laundry.png') }}" 
                 alt="Logo Deva Laundry"
                 class="mx-auto mb-6 w-32 md:w-40 drop-shadow-2xl transition-transform duration-700 hover:scale-110 hover:rotate-1">
            
            <h1 class="text-5xl font-extrabold bg-gradient-to-r from-blue-700 via-blue-600 to-sky-500 
                       bg-clip-text text-transparent tracking-tight mb-4">
                Tentang Deva Laundry
            </h1>
            <p class="text-gray-600 text-lg font-medium tracking-wide">
                Bersih • Rapi • Wangi • Tepat Waktu
            </p>
        </div>

        <!-- 🧩 Konten Utama -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-14 items-center">

            <!-- Kolom Kiri -->
            <div class="bg-white/60 backdrop-blur-lg rounded-[2rem] shadow-inner border border-white/40 p-8 
                        hover:shadow-xl transition-all duration-500">
                <h2 class="text-2xl font-bold text-blue-600 mb-4">Visi Kami</h2>
                <p class="text-gray-700 leading-relaxed mb-8">
                    Menjadi layanan laundry terpercaya dan terbaik di kota, dengan fokus pada kualitas hasil, keramahan pelayanan, dan inovasi berkelanjutan.
                </p>

                <h2 class="text-2xl font-bold text-blue-600 mb-4">Misi Kami</h2>
                <ul class="space-y-3 text-gray-700 font-medium">
                    <li>Mencuci dengan bersih, rapi, dan wangi.</li>
                    <li>Pengantaran cepat & tepat waktu.</li>
                    <li>Menggunakan bahan ramah lingkungan.</li>
                    <li>Menjaga kepercayaan pelanggan sebagai prioritas utama.</li>
                </ul>

                <div class="mt-8 border-t border-gray-200 pt-6">
                    <h3 class="text-xl font-bold text-blue-600 mb-1">Lokasi Kami</h3>
                    <p class="text-gray-700">Jl. Wisnu Marga No.Belayu, Peken, Kec. Marga, Kabupaten Tabanan, Bali 82181</p>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="flex flex-col justify-center text-gray-700 leading-relaxed">
                <h2 class="text-3xl font-bold text-blue-600 mb-5">Kenapa Pilih Kami?</h2>
                <p class="mb-6">
                    Deva Laundry hadir untuk mempermudah hidup Anda. Kami mengutamakan kecepatan, ketepatan,
                    dan kualitas layanan premium untuk setiap pelanggan.
                </p>
                <p class="mb-8">
                    Dengan peralatan modern, sistem kerja profesional, serta tim berpengalaman, kami memastikan
                    pakaian Anda bersih sempurna, harum, dan terawat seperti baru setiap kali dicuci.
                </p>

                <a href="{{ route('contact') }}"
                   class="inline-flex items-center gap-3 px-8 py-3 
                          bg-gradient-to-r from-blue-600 via-blue-500 to-sky-400 
                          text-white rounded-full shadow-md 
                          hover:shadow-2xl hover:scale-105 active:scale-95 
                          transition-all duration-300 font-semibold">
                    <i class="fa-solid fa-phone"></i> Hubungi Kami
                </a>
            </div>
        </div>

        <!-- ✨ Tim Profesional -->
        <div class="mt-28 text-center">
            <h2 class="text-4xl font-extrabold text-blue-700 mb-14 tracking-tight">
                Tim Profesional Kami
            </h2>

            <div class="flex flex-wrap justify-center gap-10 md:gap-14">
                @foreach ([ 
                    ['img' => 'foto1.png', 'nama' => 'Deva Saputra', 'jabatan' => 'Owner & Founder', 'desc' => 'Membangun Deva Laundry dengan visi pelayanan terbaik dan inovasi berkelanjutan.'],
                    ['img' => 'foto2.png', 'nama' => 'Rano Utama', 'jabatan' => 'Customer Service', 'desc' => 'Selalu siap melayani pelanggan dengan ramah dan cepat tanggap setiap hari.'],
                    ['img' => 'foto3.png', 'nama' => 'Bayu Pratama', 'jabatan' => 'Supervisor Laundry', 'desc' => 'Mengawasi setiap proses pencucian agar hasil selalu maksimal dan tepat waktu.']
                ] as $tim)
                <div class="group bg-white/40 backdrop-blur-2xl rounded-[1.5rem] border border-white/50 shadow-lg 
                            hover:shadow-[0_8px_25px_rgba(31,38,135,0.25)] hover:bg-white/60 
                            p-6 w-52 md:w-56 text-center transition-all duration-500 transform hover:-translate-y-1">

                    <!-- 🖼️ Foto Tim (kecil & melingkar) -->
                    <div class="relative w-20 h-20 mx-auto mb-5">
                        <img src="{{ asset('images/' . $tim['img']) }}" 
                             alt="{{ $tim['nama'] }}"
                             class="w-20 h-20 rounded-full object-cover shadow-lg 
                                    transition-transform duration-500 group-hover:scale-110 border-4 border-white/80">
                        <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-blue-300/20 to-transparent"></div>
                    </div>

                    <h4 class="text-lg font-semibold text-gray-800">{{ $tim['nama'] }}</h4>
                    <p class="text-blue-600 font-medium text-sm mb-1">{{ $tim['jabatan'] }}</p>
                    <p class="text-gray-600 text-xs leading-snug">{{ $tim['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- FontAwesome -->
@push('scripts')
<script src="https://kit.fontawesome.com/a2b1f3b59d.js" crossorigin="anonymous"></script>
@endpush
@endsection
