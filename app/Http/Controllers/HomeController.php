<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\SiteSetting;


class HomeController extends Controller
{
public function index(){
$services = Service::latest()->take(6)->get();

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

$contactPhone = SiteSetting::getValue('contact_phone', config('app.admin_whatsapp', '+62 821-4703-7006'));
$contactEmail = SiteSetting::getValue('contact_email', 'hello@devalaundry.example');
$contactMapsLink = SiteSetting::getValue('contact_maps_link', 'https://maps.app.goo.gl/z7jsgdWsD8JC4MFo8');
$contactInstagram = SiteSetting::getValue('contact_instagram', '@devalaundry');
$contactFacebook = SiteSetting::getValue('contact_facebook', '@devalaundry');
$contactTikTok = SiteSetting::getValue('contact_tiktok', '@devalaundry.official');

return view('home', compact(
    'services',
    'promos',
    'contactPhone',
    'contactEmail',
    'contactMapsLink',
    'contactInstagram',
    'contactFacebook',
    'contactTikTok'
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

    return view('about', compact('teams', 'vision', 'missions', 'locationText'));
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
