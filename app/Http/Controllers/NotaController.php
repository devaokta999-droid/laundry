<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\NotaItem;
use App\Models\ItemLaundry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\NotaExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Payment;

class NotaController extends Controller
{
    protected function buildWeeklySummaries(Carbon $forDate)
    {
        $startOfMonth = $forDate->copy()->startOfMonth();
        $endOfMonth = $forDate->copy()->endOfMonth();

        $cursor = $startOfMonth->copy();
        $index = 1;
        $weeks = [];

        while ($cursor->lte($endOfMonth)) {
            $weekStart = $cursor->copy()->startOfWeek();
            if ($weekStart->lt($startOfMonth)) {
                $weekStart = $startOfMonth->copy();
            }
            $weekEnd = $cursor->copy()->endOfWeek();
            if ($weekEnd->gt($endOfMonth)) {
                $weekEnd = $endOfMonth->copy();
            }

            $rangeStart = $weekStart->copy()->startOfDay();
            $rangeEnd = $weekEnd->copy()->endOfDay();

            $total = Nota::whereBetween('created_at', [$rangeStart, $rangeEnd])
                ->where('sisa', 0)
                ->sum('total');
            $count = Nota::whereBetween('created_at', [$rangeStart, $rangeEnd])->count();

            $weeks[] = [
                'label' => 'Minggu ' . $index,
                'start' => $weekStart->format('d/m/Y'),
                'end' => $weekEnd->format('d/m/Y'),
                'total' => $total,
                'count' => $count,
            ];

            $index++;
            $cursor = $cursor->copy()->addWeek();
        }

        return $weeks;
    }

    /**
     * dY_ Menampilkan daftar nota.
     */
    public function index()
    {
        $notas = Nota::with('items.item', 'payments.user', 'user')->latest()->get();
        $items = ItemLaundry::orderBy('name')->get();

        return view('admin.nota.index', compact('notas', 'items'));
    }

    /**
     * dY'_ Menyimpan data nota baru.
     *
     * Note: front-end may send item fields as arrays: name[0], price[0], quantity[0]
     * so we parse them into an items array here and validate server-side.
     */
    public function store(Request $r)
    {
        // basic validation untuk data pelanggan (uang muka tidak dipakai lagi)
        $basic = Validator::make($r->all(), [
            'customer_name' => 'required|string|max:100',
            'customer_address' => 'nullable|string|max:255',
            'tgl_keluar' => 'nullable|date',
        ]);

        if ($basic->fails()) {
            if ($r->ajax()) return response()->json(['errors' => $basic->errors()], 422);
            return back()->withErrors($basic)->withInput();
        }

        // Build items array from name[], price[], quantity[] OR from items[] if provided
        $items = [];
        if ($r->has('items') && is_array($r->items)) {
            $items = $r->items;
        } else {
            $names = $r->input('name', []);
            $prices = $r->input('price', []);
            $qtys = $r->input('quantity', []);

            $max = max(count($names), count($prices), count($qtys));
            for ($i = 0; $i < $max; $i++) {
                $n = isset($names[$i]) ? trim($names[$i]) : '';
                $p = isset($prices[$i]) ? $prices[$i] : null;
                $q = isset($qtys[$i]) ? $qtys[$i] : null;

                // push raw �?" we'll filter/validate after
                $items[] = [
                    'name' => $n,
                    'price' => $p,
                    'quantity' => $q,
                ];
            }
        }

        // Remove completely empty rows (name empty AND price empty/0 AND qty empty/0)
        $items = array_filter($items, function ($row) {
            $name = isset($row['name']) ? trim($row['name']) : '';
            $price = isset($row['price']) ? floatval($row['price']) : 0;
            $qty = isset($row['quantity']) ? floatval($row['quantity']) : 0;
            return !($name === '' && $price == 0 && $qty == 0);
        });

        if (count($items) === 0) {
            $msg = ['items' => ['Minimal harus ada 1 item laundry.']];
            if ($r->ajax()) return response()->json(['errors' => $msg], 422);
            return back()->withErrors($msg)->withInput();
        }

        // Validate each item row
        $itemRules = [];
        foreach (array_values($items) as $i => $row) {
            $itemRules["items.$i.name"] = 'required|string|max:100';
            $itemRules["items.$i.price"] = 'required|numeric|min:0';
            $itemRules["items.$i.quantity"] = 'required|numeric|min:1';
        }

        $validator = Validator::make(['items' => array_values($items)], $itemRules);
        if ($validator->fails()) {
            if ($r->ajax()) return response()->json(['errors' => $validator->errors()], 422);
            return back()->withErrors($validator)->withInput();
        }

        // All good �?" proceed to save
        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($items as $row) {
                $total += floatval($row['price']) * floatval($row['quantity']);
            }

            $uangMuka = $r->input('uang_muka', 0) ? floatval($r->input('uang_muka', 0)) : 0;
            $sisa = $total - $uangMuka;

            // Override: uang muka tidak diambil dari input lagi; selalu 0,
            // sehingga sisa awal sama dengan total penuh.
            $uangMuka = 0;
            $sisa = $total;

            $nota = Nota::create([
                'user_id' => Auth::id(),
                'customer_name' => $r->input('customer_name'),
                'customer_address' => $r->input('customer_address'),
                'tgl_masuk' => now(),
                'tgl_keluar' => $r->input('tgl_keluar'),
                'total' => $total,
                'uang_muka' => $uangMuka,
                'sisa' => $sisa,
            ]);

            foreach ($items as $row) {
                $item = ItemLaundry::firstOrCreate(
                    ['name' => $row['name']],
                    ['price' => $row['price'] ?? 0]
                );

                NotaItem::create([
                    'nota_id' => $nota->id,
                    'item_id' => $item->id,
                    'quantity' => $row['quantity'],
                    'price' => $row['price'],
                    'subtotal' => floatval($row['price']) * floatval($row['quantity']),
                ]);
            }

            DB::commit();

            if ($r->ajax()) {
                return response()->json(['message' => 'Nota berhasil dibuat', 'nota' => $nota], 201);
            }

            return redirect()->route('admin.nota.show', $nota->id)->with('success', 'Nota berhasil dibuat!');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($r->ajax()) return response()->json(['message' => 'Gagal menyimpan nota', 'error' => $e->getMessage()], 500);
            return back()->with('error', 'Gagal menyimpan nota: ' . $e->getMessage());
        }
    }

    /**
     * dY-"�,? Cetak nota dalam format PDF.
     */
    public function print($id)
    {
        $nota = Nota::with(['items.item', 'user', 'kasir', 'payments'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.nota.print', compact('nota'))
            ->setPaper('A5', 'portrait');

        return $pdf->download('Nota-' . $nota->id . '.pdf');
    }

    /**
     * dY-"�,? Tampilan print HTML langsung.
     */
    public function printToPrinter($id)
    {
        $nota = Nota::with(['items.item', 'user', 'kasir', 'payments'])->findOrFail($id);
        return view('admin.nota.print', compact('nota'));
    }

    /**
     * dY'� Tandai nota sebagai lunas (AJAX).
     */
    public function markLunas(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);

        if ((int) $nota->sisa <= 0) {
            return response()->json([
                'message' => 'Nota sudah lunas.',
                'nota' => $nota
            ], 200);
        }

        $nota->update([
            'uang_muka' => $nota->total,
            'sisa' => 0,
        ]);

        return response()->json([
            'message' => 'Berhasil menandai nota sebagai lunas.',
            'nota' => $nota
        ], 200);
    }

    /**
     * dY'3 Proses pembayaran (AJAX or form)
     * Request fields: amount (numeric), type (cash|transfer), method (optional)
     */
    public function pay(Request $request, $id)
      {
          $nota = Nota::findOrFail($id);
  
          $data = $request->validate([
              'amount' => 'required|numeric|min:0.01',
              'cash_given' => 'nullable|numeric|min:0',
              'type' => 'required|in:cash,transfer',
              'method' => 'nullable|string|max:100',
              'discount_percent' => 'nullable|numeric|min:0|max:100',
          ]);
  
          $amount = floatval($data['amount']);
          $cashGiven = isset($data['cash_given']) ? floatval($data['cash_given']) : null;
          $discountPercent = isset($data['discount_percent']) ? floatval($data['discount_percent']) : 0;

        DB::beginTransaction();
        try {
            $discountAmount = 0;
            if ($discountPercent > 0) {
                $discountAmount = round(floatval($nota->total) * ($discountPercent / 100), 2);
                // apply discount to total
                $nota->total = round(floatval($nota->total) - $discountAmount, 2);
            }

            // Prevent overpaying beyond updated total �?" cap to remaining sisa
            $remaining = max(0, floatval($nota->total) - floatval($nota->uang_muka));
            if ($amount > $remaining) {
                $amount = $remaining;
            }

            // Update uang_muka and sisa after discount
            $newUangMuka = floatval($nota->uang_muka) + $amount;
            $newSisa = floatval($nota->total) - $newUangMuka;
            if ($newSisa <= 0) {
                $newSisa = 0;
                $newUangMuka = floatval($nota->total);
            }

            $nota->update([
                'total' => $nota->total,
                'uang_muka' => $newUangMuka,
                'sisa' => $newSisa,
            ]);

            // If after discount there's nothing left to pay, skip creating a payment
            if ($amount <= 0) {
                DB::commit();

                if ($request->ajax()) {
                    return response()->json([
                        'message' => 'Diskon diterapkan. Tidak ada pembayaran yang diperlukan.',
                        'nota' => $nota->fresh()->load('payments.user'),
                        'payment' => null,
                        'discount_percent' => $discountPercent,
                        'discount_amount' => $discountAmount,
                    ], 200);
                }

                return redirect()->route('admin.nota.show', $nota->id)->with('success', 'Diskon diterapkan. Tidak ada pembayaran yang diperlukan.');
              }
  
              // Create payment record (store discount info per payment)
              $payment = Payment::create([
                  'nota_id' => $nota->id,
                  'user_id' => Auth::id(),
                  'amount' => $amount,
                  'cash_given' => $cashGiven,
                  'type' => $data['type'],
                  'method' => $data['method'] ?? null,
                  'discount_percent' => $discountPercent > 0 ? $discountPercent : null,
                'discount_amount' => $discountAmount > 0 ? $discountAmount : null,
            ]);

            // eager load user for immediate JSON response
            $payment->load('user');

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Pembayaran berhasil diproses.',
                    'nota' => $nota->fresh()->load('payments.user'),
                    'payment' => $payment,
                    'discount_percent' => $discountPercent,
                    'discount_amount' => $discountAmount,
                ], 200);
            }

            return redirect()->route('admin.nota.show', $nota->id)->with('success', 'Pembayaran berhasil.');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($request->ajax()) return response()->json(['message' => 'Gagal menyimpan pembayaran: '.$e->getMessage()], 500);
            return back()->with('error', 'Gagal menyimpan pembayaran: '.$e->getMessage());
        }
    }

    /**
     * dY"? Detail nota.
     */
    public function show($id)
    {
        $nota = Nota::with(['user', 'items.item', 'payments.user'])->findOrFail($id);
        return view('admin.nota.show', compact('nota'));
    }

    /**
     * dY-`�,? Hapus nota dan item terkait.
     */
    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);
        $nota->items()->delete();
        $nota->payments()->delete();
        $nota->delete();

        return redirect()->route('admin.nota.index')->with('success', 'Nota berhasil dihapus.');
    }

    /**
     * Hapus banyak nota sekaligus dari halaman index.
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || empty($ids)) {
            return redirect()
                ->route('admin.nota.index')
                ->with('error', 'Tidak ada nota yang dipilih untuk dihapus.');
        }

        $notas = Nota::whereIn('id', $ids)->get();
        foreach ($notas as $nota) {
            $nota->items()->delete();
            $nota->payments()->delete();
            $nota->delete();
        }

        return redirect()
            ->route('admin.nota.index')
            ->with('success', 'Nota terpilih berhasil dihapus.');
    }

    /**
     * Tampilkan form edit nota.
     */
    public function edit($id)
    {
        $nota = Nota::with(['items.item', 'user'])->findOrFail($id);
        $catalogItems = ItemLaundry::orderBy('name')->get();

        // Ringkasan hanya untuk tampilan (tidak wajib tersimpan di DB)
        $nota->total_items = $nota->items->sum('quantity');
        $nota->grand_total = $nota->items->sum('subtotal');
        $nota->sisa_bayar = $nota->sisa;

        return view('admin.nota.edit', compact('nota', 'catalogItems'));
    }

    /**
     * Update data nota (item, total, sisa).
     */
    public function update(Request $request, $id)
    {
        $nota = Nota::with('items')->findOrFail($id);

        // Validasi dasar header nota
        $basic = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:100',
            'customer_address' => 'nullable|string|max:255',
            'tgl_keluar' => 'nullable|date',
        ]);

        if ($basic->fails()) {
            if ($request->ajax()) {
                return response()->json(['errors' => $basic->errors()], 422);
            }
            return back()->withErrors($basic)->withInput();
        }

        // Ambil ulang items dari form (sama pola dengan store)
        $items = [];
        $names = $request->input('name', []);
        $prices = $request->input('price', []);
        $qtys = $request->input('quantity', []);

        $max = max(count($names), count($prices), count($qtys));
        for ($i = 0; $i < $max; $i++) {
            $n = isset($names[$i]) ? trim($names[$i]) : '';
            $p = isset($prices[$i]) ? $prices[$i] : null;
            $q = isset($qtys[$i]) ? $qtys[$i] : null;

            $items[] = [
                'name' => $n,
                'price' => $p,
                'quantity' => $q,
            ];
        }

        // Buang baris yang tidak dipakai: qty <= 0 di-skip
        $items = array_filter($items, function ($row) {
            $qty = isset($row['quantity']) ? floatval($row['quantity']) : 0;
            if ($qty <= 0) {
                return false;
            }
            $name  = isset($row['name']) ? trim($row['name']) : '';
            $price = isset($row['price']) ? floatval($row['price']) : 0;
            return !($name === '' && $price == 0);
        });

        if (count($items) === 0) {
            $msg = ['items' => ['Minimal harus ada 1 item laundry.']];
            if ($request->ajax()) return response()->json(['errors' => $msg], 422);
            return back()->withErrors($msg)->withInput();
        }

        // Validasi tiap baris item
        $itemRules = [];
        foreach (array_values($items) as $i => $row) {
            $itemRules["items.$i.name"] = 'required|string|max:100';
            $itemRules["items.$i.price"] = 'required|numeric|min:0';
            $itemRules["items.$i.quantity"] = 'required|numeric|min:1';
        }

        $validator = Validator::make(['items' => array_values($items)], $itemRules);
        if ($validator->fails()) {
            if ($request->ajax()) return response()->json(['errors' => $validator->errors()], 422);
            return back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // Hitung total baru
            $total = 0;
            foreach ($items as $row) {
                $total += floatval($row['price']) * floatval($row['quantity']);
            }

            // uang_muka tetap dipakai internal sebagai total yang sudah dibayar
            $paid = floatval($nota->uang_muka);
            $sisa = max(0, $total - $paid);

            // Update header nota
            $nota->update([
                'customer_name' => $request->input('customer_name'),
                'customer_address' => $request->input('customer_address'),
                'tgl_keluar' => $request->input('tgl_keluar'),
                'total' => $total,
                'sisa' => $sisa,
            ]);

            // Hapus item lama dan buat ulang
            $nota->items()->delete();

            foreach ($items as $row) {
                $item = ItemLaundry::firstOrCreate(
                    ['name' => $row['name']],
                    ['price' => $row['price'] ?? 0]
                );

                NotaItem::create([
                    'nota_id' => $nota->id,
                    'item_id' => $item->id,
                    'quantity' => $row['quantity'],
                    'price' => $row['price'],
                    'subtotal' => floatval($row['price']) * floatval($row['quantity']),
                ]);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Nota berhasil diperbarui.',
                    'nota' => $nota->fresh()->load('items.item', 'payments.user'),
                ], 200);
            }

            return redirect()
                ->route('admin.nota.show', $nota->id)
                ->with('success', 'Nota berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'message' => 'Gagal memperbarui nota',
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()->with('error', 'Gagal memperbarui nota: ' . $e->getMessage());
        }
    }


    /* ---------------------------------------------------
     | dY"S Bagian Laporan (DIPERBAIKI: pendapatan hanya nota LUNAS)
     --------------------------------------------------- */

    public function laporan()
    {
        $today = now();
        $startOfWeek = $today->copy()->startOfWeek();
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfYear = $today->copy()->startOfYear();

        // HANYA HITUNG NOTA YANG SUDAH LUNAS (sisa = 0)
        $harian = Nota::whereDate('created_at', today())->where('sisa', 0)->sum('total');
        $mingguan = Nota::whereBetween('created_at', [$startOfWeek, now()])->where('sisa', 0)->sum('total');
        $bulanan = Nota::whereBetween('created_at', [$startOfMonth, now()])->where('sisa', 0)->sum('total');
        $tahunan = Nota::whereBetween('created_at', [$startOfYear, now()])->where('sisa', 0)->sum('total');

        // Jika kamu ingin jumlah nota (count) berdasarkan semua nota termasuk belum lunas, biarkan seperti ini.
        $nota_harian = Nota::whereDate('created_at', today())->count();
        $nota_mingguan = Nota::whereBetween('created_at', [$startOfWeek, now()])->count();
        $nota_bulanan = Nota::whereBetween('created_at', [$startOfMonth, now()])->count();
        $nota_tahunan = Nota::whereBetween('created_at', [$startOfYear, now()])->count();

        // eager load payments so laporan view can show payment breakdown per nota
        $notas = Nota::with('payments.user', 'kasir')->latest()->get();

        // Totals by payment type (cash vs transfer) across all payments (only dari tabel payments)
        $totalCash = Payment::where('type', 'cash')->sum('amount');
        $totalTransfer = Payment::where('type', 'transfer')->sum('amount');
        $weeklySummaries = $this->buildWeeklySummaries($today);

        return view('admin.laporan', compact(
            'harian', 'mingguan', 'bulanan', 'tahunan',
            'nota_harian', 'nota_mingguan', 'nota_bulanan', 'nota_tahunan',
            'notas', 'totalCash', 'totalTransfer', 'weeklySummaries'
        ));
    }

    public function laporanExcel()
    {
        $today = Carbon::today();

        // Pastikan hanya menghitung pendapatan dari nota yang LUNAS
        $harian = Nota::whereDate('created_at', $today)->where('sisa', 0)->sum('total');
        $mingguan = Nota::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->where('sisa', 0)->sum('total');
        $bulanan = Nota::whereMonth('created_at', Carbon::now()->month)->where('sisa', 0)->sum('total');
        $tahunan = Nota::whereYear('created_at', Carbon::now()->year)->where('sisa', 0)->sum('total');

        $notas = Nota::latest()->get();
        $weeklySummaries = $this->buildWeeklySummaries(Carbon::today());

        return view('admin.laporan', compact('harian', 'mingguan', 'bulanan', 'tahunan', 'notas', 'weeklySummaries'));
    }

    public function exportExcel(Request $request)
    {
        $filter = $request->input('filter');
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        if ($filter === 'daily') {
            $start = Carbon::today()->toDateString();
            $end = Carbon::today()->toDateString();
        } elseif ($filter === 'weekly') {
            $start = Carbon::now()->startOfWeek()->toDateString();
            $end = Carbon::now()->endOfWeek()->toDateString();
        } elseif ($filter === 'monthly') {
            $start = Carbon::now()->startOfMonth()->toDateString();
            $end = Carbon::now()->endOfMonth()->toDateString();
        } elseif ($filter === 'yearly') {
            $start = Carbon::now()->startOfYear()->toDateString();
            $end = Carbon::now()->endOfYear()->toDateString();
        }

        $fileName = 'Laporan-Nota-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new NotaExport($start, $end), $fileName);
    }
}
