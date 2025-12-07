<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\SiteSetting;
use App\Models\Review;


class HomeController extends Controller
{
public function index()
{
    $services = Service::latest()->take(6)->get();
    $reviews = Review::where('is_visible', true)
        ->orderByDesc('created_at')
        ->take(6)
        ->get();

    // Promo beranda
    $defaultPromos = [
        ['title' => 'Promo 1', 'desc' => 'Diskon 20% untuk cucian pertama'],
        ['title' => 'Promo 2', 'desc' => 'Gratis pengambilan di area tertentu'],
    ];

    $promosJson = SiteSetting::getValue('promos', json_encode($defaultPromos));
    $promos = $defaultPromos;
    if ($promosJson) {
        $decoded = json_decode($promosJson, true);
        if (is_array($decoded) && count($decoded) > 0) {
            $promos = [];
            foreach ($decoded as $promo) {
                $promos[] = [
                    'title' => $promo['title'] ?? 'Promo',
                    'desc' => $promo['desc'] ?? '',
                ];
            }
        }
    }

    // Metode pembayaran
    $defaultPaymentMethods = [
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
    ];

    $paymentsJson = SiteSetting::getValue('payment_methods', json_encode($defaultPaymentMethods));
    $paymentMethods = $defaultPaymentMethods;
    if ($paymentsJson) {
        $decodedPayments = json_decode($paymentsJson, true);
        if (is_array($decodedPayments) && count($decodedPayments) > 0) {
            $paymentMethods = [];
            foreach ($decodedPayments as $payment) {
                $rawLogo = $payment['logo_path'] ?? $payment['logo_url'] ?? '';
                if ($rawLogo !== '' && !preg_match('#^https?://#i', $rawLogo)) {
                    $logoUrl = asset($rawLogo);
                } else {
                    $logoUrl = $rawLogo;
                }

                $paymentMethods[] = [
                    'name' => $payment['name'] ?? 'Metode Pembayaran',
                    'logo_url' => $logoUrl,
                ];
            }
        }
    }

    $contactPhone = SiteSetting::getValue('contact_phone', config('app.admin_whatsapp', '+62 821-4703-7006'));
    $contactEmail = SiteSetting::getValue('contact_email', 'hello@devalaundry.example');
    $contactMapsLink = SiteSetting::getValue('contact_maps_link', 'https://maps.app.goo.gl/z7jsgdWsD8JC4MFo8');
    $contactInstagram = SiteSetting::getValue('contact_instagram', '@devalaundry');
    $contactFacebook = SiteSetting::getValue('contact_facebook', '@devalaundry');
    $contactTikTok = SiteSetting::getValue('contact_tiktok', '@devalaundry.official');

    // Hero beranda
    $heroEyebrow = SiteSetting::getValue('hero_eyebrow', 'Premium Laundry Experience');
    $heroTitle = SiteSetting::getValue('hero_title', 'Deva Laundry');
    $heroSubtitle = SiteSetting::getValue('hero_subtitle', 'Cuci bersih, wangi, cepat, dan rapi - solusi pakaian harian dan spesial kamu.');
    $heroBulletsText = SiteSetting::getValue('hero_bullets', implode(PHP_EOL, [
        'Antar-jemput area sekitar',
        'Estimasi selesai tepat waktu',
        'Pencucian rapi dan terstandar',
    ]));
    $heroBullets = array_filter(preg_split('/\r\n|\r|\n/', (string) $heroBulletsText));

    $heroCardBadge = SiteSetting::getValue('hero_card_badge', 'Hari ini kamu sudah laundry?');
    $heroCardTitle = SiteSetting::getValue('hero_card_title', 'Berikan pakaian kamu pengalaman premium.');
    $heroCardText = SiteSetting::getValue('hero_card_text', implode(PHP_EOL, [
        'Serahkan proses cuci, kering, dan setrika ke tim Deva Laundry. Kamu cukup pesan dari rumah.',
        'Pantau pesanan, atur jadwal, dan nikmati pakaian yang selalu siap dipakai.',
    ]));
    $heroCardParagraphs = array_filter(preg_split('/\r\n|\r|\n/', (string) $heroCardText));

    return view('home', compact(
        'services',
        'promos',
        'paymentMethods',
        'contactPhone',
        'contactEmail',
        'contactMapsLink',
        'contactInstagram',
        'contactFacebook',
        'contactTikTok',
        'heroEyebrow',
        'heroTitle',
        'heroSubtitle',
        'heroBullets',
        'heroCardBadge',
        'heroCardTitle',
        'heroCardParagraphs',
        'reviews'
    ));
}


public function about(){
    $teams = TeamMember::orderBy('id')->get();

    $vision = SiteSetting::getValue('about_vision', 'Menjadi penyedia layanan laundry terpercaya di Bali yang berfokus pada kebersihan, kecepatan pelayanan, dan kepuasan pelanggan dengan standar profesional.');
    $missionText = SiteSetting::getValue('about_mission', implode(PHP_EOL, [
        'Memberikan hasil laundry bersih, rapi, dan wangi.',
        'Menyediakan layanan antar-jemput cepat dan tepat waktu.',
        'Menggunakan bahan ramah lingkungan dan mesin modern.',
        'Menjaga kepercayaan dan kenyamanan pelanggan sebagai prioritas utama.',
    ]));
    $locationText = SiteSetting::getValue('about_location', 'Jl. Wisnu Marga No. Belayu, Peken, Kec. Marga, Kabupaten Tabanan, Bali 82181');

    $missions = array_filter(preg_split('/\r\n|\r|\n/', $missionText));

    $whyTitle = SiteSetting::getValue('about_why_title', 'Kenapa Pilih Deva Laundry?');
    $whyText = SiteSetting::getValue('about_why_text', implode(PHP_EOL, [
        'Deva Laundry hadir untuk mempermudah hidup Anda. Kami mengutamakan kecepatan, ketepatan, dan kualitas layanan premium untuk setiap pelanggan.',
        'Dengan peralatan modern, sistem kerja profesional, serta tim berpengalaman, kami memastikan pakaian Anda bersih sempurna, harum, dan terawat seperti baru setiap kali dicuci.',
    ]));
    $whyParagraphs = array_filter(preg_split('/\r\n|\r|\n/', $whyText));

    $hoursText = SiteSetting::getValue('about_hours', implode(PHP_EOL, [
        'Senin — Minggu',
        '08.30 – 17.00 WITA',
    ]));
    $hoursLines = array_filter(preg_split('/\r\n|\r|\n/', $hoursText));

    $aboutHeroTitle = SiteSetting::getValue('about_hero_title', 'Tentang Deva Laundry');
    $aboutHeroTagline = SiteSetting::getValue('about_hero_tagline', 'Bersih • Rapi • Wangi • Tepat Waktu');

    return view('about', compact('teams', 'vision', 'missions', 'locationText', 'whyTitle', 'whyParagraphs', 'hoursLines', 'aboutHeroTitle', 'aboutHeroTagline'));
}


public function contact(){
    $contactPhone = SiteSetting::getValue('contact_phone', config('app.admin_whatsapp', '+62 821-4703-7006'));
    $contactEmail = SiteSetting::getValue('contact_email', 'hello@devalaundry.example');
    $contactInstagram = SiteSetting::getValue('contact_instagram', '@devalaundry');
    $contactFacebook = SiteSetting::getValue('contact_facebook', '@devalaundry');
    $contactTikTok = SiteSetting::getValue('contact_tiktok', '@devalaundry.official');
    $contactMapsLink = SiteSetting::getValue('contact_maps_link', 'https://maps.app.goo.gl/G1ERg4TJQhMLp2cm9');

    return view('contact', compact(
        'contactPhone',
        'contactEmail',
        'contactInstagram',
        'contactFacebook',
        'contactTikTok',
        'contactMapsLink'
    ));
}


public function promos(){
return view('promos');
}
}
