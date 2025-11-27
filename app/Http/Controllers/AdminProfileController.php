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

        return view('admin.profile', compact('user', 'settings', 'promos'));
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

            // Promo dinamis
            'promo_titles' => 'nullable|array',
            'promo_titles.*' => 'nullable|string|max:255',
            'promo_descs' => 'nullable|array',
            'promo_descs.*' => 'nullable|string',
        ]);

        foreach ($this->defaultSettings() as $key => $default) {
            if ($key === 'promos') {
                continue;
            }
            $value = $data[$key] ?? null;
            if ($value === null) {
                // Jika tidak diisi, simpan string kosong supaya mudah dibedakan dengan default
                $value = '';
            }
            SiteSetting::setValue($key, $value);
        }

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

            // Promo defaults (disimpan sebagai JSON)
            'promos' => json_encode([
                ['title' => 'Promo 1', 'desc' => 'Diskon 20% untuk cucian pertama'],
                ['title' => 'Promo 2', 'desc' => 'Gratis pengambilan di area tertentu'],
            ]),
        ];
    }
}
