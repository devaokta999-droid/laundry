<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class TransactionController extends Controller
{
    public function index()
    {
        // Ambil pesanan yang sudah selesai saja
        $orders = Order::with(['user', 'services'])
            ->where('status', 'selesai')
            ->latest()
            ->paginate(30);

        return view('admin.transactions.index', compact('orders'));
    }
}
