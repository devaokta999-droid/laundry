<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class CashierController extends Controller
{
    /**
     * Menampilkan halaman kasir
     * Hanya bisa diakses oleh admin, kasir, atau deva
     */
    public function index()
    {
        // Ambil semua pesanan yang belum selesai
        $orders = Order::with(['user', 'services'])
            ->whereIn('status', ['pending', 'proses'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.cashier.index', compact('orders'));
    }

    /**
     * Mencetak nota transaksi dan ubah status jadi "selesai"
     */
    public function print(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with(['user', 'services'])->findOrFail($request->order_id);

        // Update status pesanan
        $order->update(['status' => 'selesai']);

        // Di sini kamu bisa menambahkan logika cetak nota ke printer atau PDF
        // (sementara hanya flash message sukses)
        return redirect()->route('admin.cashier.index')->with('success', "Nota untuk pesanan #{$order->id} berhasil dicetak dan status diperbarui!");
    }
}
