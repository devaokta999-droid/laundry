<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AdminProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (!$user || $user->role !== 'admin') {
                abort(403, 'Akses ditolak - hanya admin yang dapat mengelola profil & pengaturan situs.');
            }
            return $next($request);
        });
    }

    public function show(Request $request)
    {
        $user = $request->user();

        $defaults = $this->defaultSettings();

        $settings = [];
        foreach ($defaults as $key => $default) {
            $settings[$key] = SiteSetting::getValue($key, $default);
        }

        // Promo
        $promos = [];
        $promosJson = SiteSetting::getValue('promos', $defaults['promos']);
        if ($promosJson) {
            $decoded = json_decode($promosJson, true);
            if (is_array($decoded)) {
                $promos = $decoded;
            }
        }
        if (empty($promos)) {
            $promos = [
                ['title' => 'Promo 1', 'desc' => 'Diskon 20% untuk cucian pertama'],
                ['title' => 'Promo 2', 'desc' => 'Gratis pengambilan di area tertentu'],
            ];
        }

        // Metode pembayaran
        $paymentMethods = [];
        $paymentsJson = SiteSetting::getValue('payment_methods', $defaults['payment_methods']);
        if ($paymentsJson) {
            $decodedPayments = json_decode($paymentsJson, true);
            if (is_array($decodedPayments)) {
                $paymentMethods = $decodedPayments;
            }
        }
        if (empty($paymentMethods)) {
            $paymentMethods = json_decode($defaults['payment_methods'], true) ?? [];
        }

        return view('admin.profile', compact('user', 'settings', 'promos', 'paymentMethods'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            // Contact
            'contact_phone' => 'nullable|string|max:255',
            'contact_email' => 'nullable|string|max:255',
            'contact_instagram' => 'nullable|string|max:255',
            'contact_facebook' => 'nullable|string|max:255',
            'contact_tiktok' => 'nullable|string|max:255',
            'contact_maps_link' => 'nullable|string|max:500',

            // About
            'about_vision' => 'nullable|string',
            'about_mission' => 'nullable|string',
            'about_location' => 'nullable|string',
            'about_why_title' => 'nullable|string|max:255',
            'about_why_text' => 'nullable|string',
            'about_hours' => 'nullable|string',
            'about_hero_title' => 'nullable|string|max:255',
            'about_hero_tagline' => 'nullable|string|max:255',

            // Hero beranda
            'hero_eyebrow' => 'nullable|string|max:255',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string',
            'hero_bullets' => 'nullable|string',
            'hero_card_badge' => 'nullable|string|max:255',
            'hero_card_title' => 'nullable|string|max:255',
            'hero_card_text' => 'nullable|string',

            // Promo dinamis
            'promo_titles' => 'nullable|array',
            'promo_titles.*' => 'nullable|string|max:255',
            'promo_descs' => 'nullable|array',
            'promo_descs.*' => 'nullable|string',

            // Metode pembayaran dinamis
            'payment_names' => 'nullable|array',
            'payment_names.*' => 'nullable|string|max:255',
            'payment_logos' => 'nullable|array',
            // Tanpa batas ukuran file eksplisit; dibatasi oleh konfigurasi PHP/server saja
            'payment_logos.*' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'existing_payment_logos' => 'nullable|array',
            'existing_payment_logos.*' => 'nullable|string',

            // Logo
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'reset_logo' => 'nullable|boolean',
        ]);

        foreach ($this->defaultSettings() as $key => $default) {
            if (in_array($key, ['promos', 'payment_methods', 'logo_path'], true)) {
                continue;
            }
            $value = $data[$key] ?? null;
            if ($value === null) {
                // Jika tidak diisi, simpan string kosong supaya mudah dibedakan dengan default
                $value = '';
            }
            SiteSetting::setValue($key, $value);
        }

        // Reset logo ke default jika diminta
        if ($request->boolean('reset_logo')) {
            $oldLogo = SiteSetting::getValue('logo_path', 'header.png');
            if ($oldLogo && $oldLogo !== 'header.png') {
                $oldPath = public_path('images/' . $oldLogo);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }
            SiteSetting::setValue('logo_path', 'header.png');
        }

        // Simpan logo baru jika di-upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $destination = public_path('images/logo');

            if (!is_dir($destination)) {
                mkdir($destination, 0755, true);
            }

            $filename = time() . '_' . preg_replace('/\s+/', '_', $logo->getClientOriginalName());
            $logo->move($destination, $filename);

            $oldLogo = SiteSetting::getValue('logo_path', 'header.png');
            if ($oldLogo && $oldLogo !== 'header.png') {
                $oldPath = public_path('images/' . $oldLogo);
                if (is_file($oldPath)) {
                    @unlink($oldPath);
                }
            }

            SiteSetting::setValue('logo_path', 'logo/' . $filename);
        }

        // Simpan promo
        $titles = $request->input('promo_titles', []);
        $descs = $request->input('promo_descs', []);
        $promos = [];
        foreach ($titles as $idx => $title) {
            $title = trim((string) $title);
            $desc = trim((string) ($descs[$idx] ?? ''));
            if ($title === '' && $desc === '') {
                continue;
            }
            $promos[] = [
                'title' => $title ?: 'Promo',
                'desc' => $desc,
            ];
        }
        if (empty($promos)) {
            $promos = [
                ['title' => 'Promo 1', 'desc' => 'Diskon 20% untuk cucian pertama'],
                ['title' => 'Promo 2', 'desc' => 'Gratis pengambilan di area tertentu'],
            ];
        }
        SiteSetting::setValue('promos', json_encode($promos));

        // Simpan metode pembayaran
        $paymentNames = $request->input('payment_names', []);
        $existingPaymentLogos = $request->input('existing_payment_logos', []);
        $paymentLogoFiles = $request->file('payment_logos', []);
        $payments = [];
        foreach ($paymentNames as $idx => $name) {
            $name = trim((string) $name);

            $logoPath = trim((string) ($existingPaymentLogos[$idx] ?? ''));
            if (isset($paymentLogoFiles[$idx]) && $paymentLogoFiles[$idx] && $paymentLogoFiles[$idx]->isValid()) {
                $file = $paymentLogoFiles[$idx];
                $destination = public_path('images/payments');
                if (!is_dir($destination)) {
                    mkdir($destination, 0755, true);
                }

                $filename = time() . '_' . $idx . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $file->move($destination, $filename);

                // Hapus logo lama jika berupa file lokal (bukan URL eksternal)
                if ($logoPath && !preg_match('#^https?://#i', $logoPath)) {
                    $oldPath = public_path($logoPath);
                    if (is_file($oldPath)) {
                        @unlink($oldPath);
                    }
                }

                $logoPath = 'images/payments/' . $filename;
            }

            if ($name === '' && $logoPath === '') {
                continue;
            }

            $payments[] = [
                'name' => $name !== '' ? $name : 'Metode Pembayaran',
                'logo_path' => $logoPath,
            ];
        }
        if (empty($payments)) {
            $payments = json_decode($this->defaultSettings()['payment_methods'], true) ?? [];
        }
        SiteSetting::setValue('payment_methods', json_encode($payments));

        return redirect()
            ->route('admin.profile')
            ->with('success', 'Pengaturan profil, contact, about, dan promo berhasil diperbarui.');
    }

    private function defaultSettings(): array
    {
        return [
            // Contact defaults
            'contact_phone' => config('app.admin_whatsapp', '+62 821-4703-7006'),
            'contact_email' => 'hello@devalaundry.example',
            'contact_instagram' => '@devalaundry',
            'contact_facebook' => '@devalaundry',
            'contact_tiktok' => '@devalaundry.official',
            'contact_maps_link' => 'https://maps.app.goo.gl/G1ERg4TJQhMLp2cm9',

            // About defaults (sesuai teks yang ada sekarang)
            'about_vision' => 'Menjadi penyedia layanan laundry terpercaya di Bali yang berfokus pada kebersihan, kecepatan pelayanan, dan kepuasan pelanggan dengan standar profesional.',
            'about_mission' => implode(PHP_EOL, [
                'Memberikan hasil laundry bersih, rapi, dan wangi.',
                'Menyediakan layanan antar-jemput cepat dan tepat waktu.',
                'Menggunakan bahan ramah lingkungan dan mesin modern.',
                'Menjaga kepercayaan dan kenyamanan pelanggan sebagai prioritas utama.',
            ]),
            'about_location' => 'Jl. Wisnu Marga No. Belayu, Peken, Kec. Marga, Kabupaten Tabanan, Bali 82181',
            'about_why_title' => 'Kenapa Pilih Deva Laundry?',
            'about_why_text' => implode(PHP_EOL, [
                'Deva Laundry hadir untuk mempermudah hidup Anda. Kami mengutamakan kecepatan, ketepatan, dan kualitas layanan premium untuk setiap pelanggan.',
                'Dengan peralatan modern, sistem kerja profesional, serta tim berpengalaman, kami memastikan pakaian Anda bersih sempurna, harum, dan terawat seperti baru setiap kali dicuci.',
            ]),
            'about_hours' => implode(PHP_EOL, [
                'Senin — Minggu',
                '08.30 – 17.00 WITA',
            ]),

            // Hero beranda defaults
            'hero_eyebrow' => 'Premium Laundry Experience',
            'hero_title' => 'Deva Laundry',
            'hero_subtitle' => 'Cuci bersih, wangi, cepat, dan rapi - solusi pakaian harian dan spesial kamu.',
            'hero_bullets' => implode(PHP_EOL, [
                'Antar-jemput area sekitar',
                'Estimasi selesai tepat waktu',
                'Pencucian rapi dan terstandar',
            ]),
            'hero_card_badge' => 'Hari ini kamu sudah laundry?',
            'hero_card_title' => 'Berikan pakaian kamu pengalaman premium.',
            'hero_card_text' => implode(PHP_EOL, [
                'Serahkan proses cuci, kering, dan setrika ke tim Deva Laundry. Kamu cukup pesan dari rumah.',
                'Pantau pesanan, atur jadwal, dan nikmati pakaian yang selalu siap dipakai.',
            ]),

            // About hero (halaman Tentang)
            'about_hero_title' => 'Tentang Deva Laundry',
            'about_hero_tagline' => 'Bersih • Rapi • Wangi • Tepat Waktu',

            // Promo defaults (disimpan sebagai JSON)
            'promos' => json_encode([
                ['title' => 'Promo 1', 'desc' => 'Diskon 20% untuk cucian pertama'],
                ['title' => 'Promo 2', 'desc' => 'Gratis pengambilan di area tertentu'],
            ]),

            // Payment methods defaults (disimpan sebagai JSON)
            'payment_methods' => json_encode([
                ['name' => 'BRImo', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/0/0f/Logo_BRI_Brimo.png'],
                ['name' => 'Mastercard', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/0/04/Mastercard-logo.png'],
                ['name' => 'OCTO Mobile', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/0/0b/Octo_Mobile_logo.svg'],
                ['name' => 'Livin by Mandiri', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/55/Livin%27_by_Mandiri_logo.png'],
                ['name' => 'GPN', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/3/39/Gerbang_Pembayaran_Nasional_logo.svg'],
                ['name' => 'JCB', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/2/2a/JCB_logo.svg'],
                ['name' => 'blu by BCA Digital', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/4/4e/Blu_by_BCA_Digital_logo.svg'],
                ['name' => 'Gopay', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/5/5e/Logo_Gopay.svg'],
                ['name' => 'DANA', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/9/9d/Logo_dana_blue.svg'],
                ['name' => 'OVO', 'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/6/69/Logo_ovo_purple.svg'],
            ]),

            // Logo default
            'logo_path' => 'header.png',
        ];
    }
}
