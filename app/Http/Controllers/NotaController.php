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

class NotaController extends Controller
{
    /**
     * 🧾 Menampilkan daftar nota.
     */
    public function index()
    {
        $notas = Nota::with('items.item')->latest()->get();
        $items = ItemLaundry::orderBy('name')->get();

        return view('admin.nota.index', compact('notas', 'items'));
    }

    /**
     * 💾 Menyimpan data nota baru.
     *
     * Note: front-end may send item fields as arrays: name[0], price[0], quantity[0]
     * so we parse them into an items array here and validate server-side.
     */
    public function store(Request $r)
    {
        // basic validation for customer and uang_muka
        $basic = Validator::make($r->all(), [
            'customer_name' => 'required|string|max:100',
            'customer_address' => 'nullable|string|max:255',
            'tgl_keluar' => 'nullable|date',
            'uang_muka' => 'nullable|numeric|min:0',
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

                // push raw — we'll filter/validate after
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

        // All good — proceed to save
        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($items as $row) {
                $total += floatval($row['price']) * floatval($row['quantity']);
            }

            $uangMuka = $r->input('uang_muka', 0) ? floatval($r->input('uang_muka', 0)) : 0;
            $sisa = $total - $uangMuka;

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
     * 🖨️ Cetak nota dalam format PDF.
     */
    public function print($id)
    {
        $nota = Nota::with(['items.item', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('admin.nota.print', compact('nota'))
            ->setPaper('A5', 'portrait');

        return $pdf->download('Nota-' . $nota->id . '.pdf');
    }

    /**
     * 🖨️ Tampilan print HTML langsung.
     */
    public function printToPrinter($id)
    {
        $nota = Nota::with('items.item')->findOrFail($id);
        return view('admin.nota.print', compact('nota'));
    }

    /**
     * 💰 Tandai nota sebagai lunas (AJAX).
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
     * 🔍 Detail nota.
     */
    public function show($id)
    {
        $nota = Nota::with(['user', 'items.item'])->findOrFail($id);
        return view('admin.nota.show', compact('nota'));
    }

    /**
     * 🗑️ Hapus nota dan item terkait.
     */
    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);
        $nota->items()->delete();
        $nota->delete();

        return redirect()->route('admin.nota.index')->with('success', 'Nota berhasil dihapus.');
    }


    /* -------------------------------------------
     | ✨ ✨ FITUR BARU → EDIT & UPDATE NOTA ✨ ✨
     --------------------------------------------*/

    /**
     * 📌 Menampilkan form edit nota
     */
    public function edit($id)
    {
        $nota = Nota::with('items.item')->findOrFail($id);

        if ($nota->sisa <= 0) {
            return redirect()->back()->with('error', 'Nota sudah lunas dan tidak bisa diedit.');
        }

        $items = ItemLaundry::orderBy('name')->get();
        return view('admin.nota.edit', compact('nota', 'items'));
    }

    /**
     * 💾 Update data nota
     */
    public function update(Request $r, $id)
    {
        // basic validation for customer and uang_muka
        $basic = Validator::make($r->all(), [
            'customer_name' => 'required|string|max:100',
            'customer_address' => 'nullable|string|max:255',
            'tgl_keluar' => 'nullable|date',
            'uang_muka' => 'nullable|numeric|min:0',
        ]);

        if ($basic->fails()) {
            if ($r->ajax()) return response()->json(['errors' => $basic->errors()], 422);
            return back()->withErrors($basic)->withInput();
        }

        // Build items array from request same as in store()
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

                $items[] = [
                    'name' => $n,
                    'price' => $p,
                    'quantity' => $q,
                ];
            }
        }

        // Remove empty rows
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

        // validate each item
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

        DB::beginTransaction();
        try {
            $nota = Nota::findOrFail($id);

            if ($nota->sisa <= 0) {
                $msg = 'Nota sudah lunas dan tidak bisa diedit.';
                if ($r->ajax()) return response()->json(['message' => $msg], 403);
                return back()->with('error', $msg);
            }

            $total = 0;
            foreach ($items as $row) {
                $total += floatval($row['price']) * floatval($row['quantity']);
            }

            $uangMuka = $r->input('uang_muka', 0) ? floatval($r->input('uang_muka', 0)) : 0;
            $sisa = $total - $uangMuka;

            $nota->update([
                'customer_name' => $r->input('customer_name'),
                'customer_address' => $r->input('customer_address'),
                'tgl_keluar' => $r->input('tgl_keluar'),
                'total' => $total,
                'uang_muka' => $uangMuka,
                'sisa' => $sisa,
            ]);

            // Delete old items and insert new ones
            $nota->items()->delete();

            foreach ($items as $row) {
                $item = ItemLaundry::firstOrCreate([
                    'name' => $row['name']
                ], [
                    'price' => $row['price'] ?? 0
                ]);

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
                return response()->json(['message' => 'Nota berhasil diperbarui', 'nota' => $nota], 200);
            }

            // redirect to show nota by default
            return redirect()->route('admin.nota.show', $id)->with('success', 'Nota berhasil diperbarui!');
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($r->ajax()) return response()->json(['message' => 'Gagal memperbarui nota', 'error' => $e->getMessage()], 500);
            return back()->with('error', 'Gagal memperbarui nota: ' . $e->getMessage());
        }
    }


    /* ---------------------------------------------------
     | 📊 Bagian Laporan (TIDAK DIUBAH SAMA SEKALI)
     --------------------------------------------------- */

    public function laporan()
    {
        $today = now();
        $startOfWeek = $today->copy()->startOfWeek();
        $startOfMonth = $today->copy()->startOfMonth();
        $startOfYear = $today->copy()->startOfYear();

        $harian = Nota::whereDate('created_at', today())->sum('total');
        $mingguan = Nota::whereBetween('created_at', [$startOfWeek, now()])->sum('total');
        $bulanan = Nota::whereBetween('created_at', [$startOfMonth, now()])->sum('total');
        $tahunan = Nota::whereBetween('created_at', [$startOfYear, now()])->sum('total');

        $nota_harian = Nota::whereDate('created_at', today())->count();
        $nota_mingguan = Nota::whereBetween('created_at', [$startOfWeek, now()])->count();
        $nota_bulanan = Nota::whereBetween('created_at', [$startOfMonth, now()])->count();
        $nota_tahunan = Nota::whereBetween('created_at', [$startOfYear, now()])->count();

        $notas = Nota::latest()->get();

        return view('admin.laporan', compact(
            'harian', 'mingguan', 'bulanan', 'tahunan',
            'nota_harian', 'nota_mingguan', 'nota_bulanan', 'nota_tahunan',
            'notas'
        ));
    }

    public function laporanExcel()
    {
        $today = Carbon::today();

        $harian = Nota::whereDate('created_at', $today)->sum('total');
        $mingguan = Nota::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total');
        $bulanan = Nota::whereMonth('created_at', Carbon::now()->month)->sum('total');
        $tahunan = Nota::whereYear('created_at', Carbon::now()->year)->sum('total');

        $notas = Nota::latest()->get();

        return view('admin.laporan', compact('harian', 'mingguan', 'bulanan', 'tahunan', 'notas'));
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
