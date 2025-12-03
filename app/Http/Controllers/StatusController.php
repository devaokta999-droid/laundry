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

        $orders = $orders->paginate(20)->withQueryString();
        return view('admin.status-orders', compact('orders'));
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
