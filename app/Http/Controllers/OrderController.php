<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;

class OrderController extends Controller
{
    // Form pemesanan (tampil ke pelanggan)
    public function create()
    {
        $services = Service::all();
        return view('order.create', compact('services'));
    }

    // Proses simpan pesanan
    public function store(Request $r)
    {
        // Validasi input
        $r->validate([
            'customer_name' => 'required|string|max:255',
            'customer_address' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'service_ids' => 'required|array',
            'qty' => 'required|array',
        ]);

        // Hitung total harga
        $items = [];
        $total = 0;

        foreach ($r->service_ids as $i => $service_id) {
            $service = Service::find($service_id);
            if (!$service) continue;

            $qty = max(1, intval($r->qty[$i] ?? 1));
            $subtotal = $service->price * $qty;

            $items[] = [
                'service_id' => $service->id,
                'title' => $service->title,
                'qty' => $qty,
                'price' => $service->price,
                'subtotal' => $subtotal,
            ];

            $total += $subtotal;
        }

        // Simpan ke database (user_id bisa null jika pelanggan tidak login)
        $order = Order::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'customer_name' => $r->customer_name,
            'customer_email' => $r->customer_email,
            'customer_phone' => $r->customer_phone,
            'customer_address' => $r->customer_address,
            'notes' => $r->notes,
            'items' => json_encode($items),
            'total_price' => $total,
            'pickup_date' => $r->pickup_date,
            'pickup_time' => $r->pickup_time,
        ]);

        // Kirim pesan otomatis ke WhatsApp admin
        $adminNumber = config('app.admin_whatsapp', '6281234567890'); // default WA admin
        $text = "🧺 *Order Baru Deva Laundry*%0A"
            ."Nama: {$order->customer_name}%0A"
            ."No HP: {$order->customer_phone}%0A"
            ."Alamat: {$order->customer_address}%0A"
            ."Total: Rp".number_format($total, 0, ',', '.')."%0A%0A";

        foreach ($items as $it) {
            $text .= "{$it['title']} x{$it['qty']} = Rp".number_format($it['subtotal'], 0, ',', '.')."%0A";
        }

        $waLink = "https://wa.me/{$adminNumber}?text={$text}";

        return redirect($waLink);
    }
}
