<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function customer(Request $request)
    {
        $orders = collect();
        $phone = trim($request->input('phone', ''));
        if ($phone !== '') {
            $clean = preg_replace('/\D+/', '', $phone);
            if ($clean !== '') {
                $orders = Order::with('nota')
                    ->where('customer_phone', 'like', "%{$clean}%")
                    ->orderByDesc('created_at')
                    ->limit(30)
                    ->get();
            }
        }

        return view('status.index', [
            'orders' => $orders,
            'phone' => $request->input('phone'),
        ]);
    }

    public function admin(Request $request)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, ['admin', 'kasir'], true)) {
            abort(403, 'Akses ditolak - hanya admin atau kasir yang dapat melihat status order.');
        }

        $orders = Order::with('nota')->orderByDesc('created_at');
        $keyword = trim($request->input('keyword', ''));
        if ($keyword !== '') {
            $orders = $orders->where(function ($sub) use ($keyword) {
                $sub->where('customer_name', 'like', "%{$keyword}%")
                    ->orWhere('customer_phone', 'like', "%{$keyword}%");
            });
        }

        $statusFilter = $request->input('status', '');
        if ($statusFilter === 'selesai') {
            $orders = $orders->where('status', 'selesai');
        } elseif ($statusFilter === 'proses') {
            $orders = $orders->where('status', '!=', 'selesai');
        }

        // Filter tambahan berdasarkan status pengiriman (delivered_at)
        $deliveryFilter = $request->input('delivery', '');
        if ($deliveryFilter === 'sudah') {
            $orders = $orders->whereNotNull('delivered_at');
        } elseif ($deliveryFilter === 'belum') {
            $orders = $orders->whereNull('delivered_at');
        }

        $orders = $orders->paginate(20)->withQueryString();
        return view('admin.status-orders', compact('orders'));
    }

    public function markDelivered(Request $request, Order $order)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, ['admin', 'kasir'], true)) {
            abort(403, 'Akses ditolak - hanya admin atau kasir yang dapat mengubah status pengiriman.');
        }

        if (!$order->delivered_at) {
            $order->delivered_at = now();
            $order->save();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'delivered_at' => optional($order->delivered_at)->toDateTimeString(),
            ]);
        }

        return back()->with('success', 'Order telah ditandai sebagai sudah dikirim.');
    }

    public function createNota(Request $request, Order $order)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Akses ditolak - hanya admin yang dapat membuat nota.');
        }

        return redirect()
            ->route('admin.nota.index', [
                'order_id' => $order->id,
                'customer_name' => $order->customer_name,
                'customer_address' => $order->customer_address,
            ]);
    }
}
