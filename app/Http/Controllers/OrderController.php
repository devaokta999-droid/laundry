<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;

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
            'pickup_date' => 'required|date',
            'pickup_time' => 'required',
        ]);

        // Simpan item tanpa harga
        $items = [];

        foreach ($r->service_ids as $i => $service_id) {
            $service = Service::find($service_id);
            if (!$service) {
                continue;
            }

            $qty = max(1, (int) ($r->qty[$i] ?? 1));

            $items[] = [
                'service_id' => $service->id,
                'title' => $service->title,
                'description' => $service->description,
                'qty' => $qty,
            ];
        }

        // Simpan ke database tanpa total_price
        $order = Order::create([
            'user_id' => auth()->check() ? auth()->id() : null,
            'customer_name' => $r->customer_name,
            'customer_email' => $r->customer_email,
            'customer_phone' => $r->customer_phone,
            'customer_address' => $r->customer_address,
            'notes' => $r->notes,
            'items' => json_encode($items),
            'total_price' => 0, // tidak dipakai
            'pickup_date' => $r->pickup_date,
            'pickup_time' => $r->pickup_time,
            'delivery_date' => null,
            'delivery_time' => null,
        ]);

        // Kirim pesan ke WhatsApp admin tanpa harga
        $adminRaw = config('app.admin_whatsapp', '+62 821-4703-7006');
        $adminNumber = preg_replace('/\D/', '', $adminRaw); // hanya angka
        if (strpos($adminNumber, '0') === 0) {
            $adminNumber = '62' . substr($adminNumber, 1);
        }

        // Format jadwal jemput agar rapi (DD-MM-YYYY HH:MM)
        $pickupDate = $order->pickup_date
            ? Carbon::parse($order->pickup_date)->format('d-m-Y')
            : '-';
        $pickupTime = $order->pickup_time
            ? Carbon::parse($order->pickup_time)->format('H:i')
            : '-';
        $pickupAt = trim($pickupDate . ' ' . $pickupTime);

        // Susun pesan WhatsApp yang rapi tanpa spasi kosong atas/bawah
        $message = "Order Baru Deva Laundry\n"
            ."Nama   : {$order->customer_name}\n"
            ."No HP  : {$order->customer_phone}\n"
            ."Alamat : {$order->customer_address}\n"
            ."Jemput : {$pickupAt}\n";

        if (!empty($order->notes)) {
            $message .= "Catatan: {$order->notes}\n";
        }

        $message .= "List Pesanan:\n";
        foreach ($items as $it) {
            $message .= "- {$it['title']} x{$it['qty']}\n";
        }

        // Hilangkan newline di akhir sebelum encode
        $encoded = rawurlencode(rtrim($message));
        $waLink = "https://wa.me/{$adminNumber}?text={$encoded}";

        // Redirect ke WhatsApp (app/web) dengan chat admin dan pesan otomatis
        return redirect($waLink);
    }
}
