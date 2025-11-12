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
     */
    public function store(Request $r)
    {
        $r->validate([
            'customer_name' => 'required|string|max:100',
            'customer_address' => 'nullable|string|max:255',
            'tgl_keluar' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:100',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:1',
            'uang_muka' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            foreach ($r->items as $row) {
                $total += $row['price'] * $row['quantity'];
            }

            $uangMuka = $r->uang_muka ?? 0;
            $sisa = $total - $uangMuka;

            $nota = Nota::create([
                'user_id' => Auth::id(),
                'customer_name' => $r->customer_name,
                'customer_address' => $r->customer_address,
                'tgl_masuk' => now(),
                'tgl_keluar' => $r->tgl_keluar,
                'total' => $total,
                'uang_muka' => $uangMuka,
                'sisa' => $sisa,
            ]);

            foreach ($r->items as $row) {
                $item = ItemLaundry::firstOrCreate(
                    ['name' => $row['name']],
                    ['price' => $row['price']]
                );

                NotaItem::create([
                    'nota_id' => $nota->id,
                    'item_id' => $item->id,
                    'quantity' => $row['quantity'],
                    'price' => $row['price'],
                    'subtotal' => $row['quantity'] * $row['price'],
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Nota berhasil dibuat!');
        } catch (\Throwable $e) {
            DB::rollBack();
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

    /**
     * 📊 Laporan keuangan harian, mingguan, bulanan, tahunan (versi utama).
     */
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

    /**
     * 📊 Laporan keuangan (versi Carbon untuk Excel Export)
     */
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

    /**
     * 📤 Export Laporan ke Excel (dengan filter harian, mingguan, bulanan, tahunan).
     */
    public function exportExcel(Request $request)
    {
        $filter = $request->input('filter'); // daily, weekly, monthly, yearly
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        // 🗓️ Tentukan rentang tanggal berdasarkan filter
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

        // 🧾 Nama file dinamis berdasarkan filter
        $filterLabel = match ($filter) {
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            default => 'Semua',
        };

        $fileName = 'Laporan-Nota-' . $filterLabel . '-' . now()->format('Ymd_His') . '.xlsx';

        // 📤 Export ke Excel
        return Excel::download(new NotaExport($start, $end), $fileName);
    }
}
