<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\NotaItem;
use App\Models\ItemLaundry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class NotaController extends Controller
{
    /**
     * Menampilkan daftar nota.
     */
    public function index()
    {
        $notas = Nota::with('items.item')->latest()->get();
        $items = ItemLaundry::orderBy('name')->get();
        return view('admin.nota.index', compact('notas', 'items'));
    }

    /**
     * Menyimpan data nota baru.
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
     * Mencetak nota dalam format PDF (download file).
     */
    public function print($id)
    {
        $nota = Nota::with(['items.item', 'user'])->findOrFail($id);

        // Gunakan view admin.nota.print (desain cetak PDF)
        $pdf = Pdf::loadView('admin.nota.print', compact('nota'))
            ->setPaper('A5', 'portrait');

        return $pdf->download('Nota-' . $nota->id . '.pdf');
    }

    /**
     * Menampilkan view print-friendly yang otomatis memanggil window.print()
     */
    public function printToPrinter($id)
    {
        $nota = Nota::with('items.item')->findOrFail($id);

        // View admin.nota.print_direct adalah halaman HTML yang langsung memicu window.print()
        return view('admin.nota.print', compact('nota'));
    }

    /**
     * Tandai nota sebagai lunas (set uang_muka = total, sisa = 0)
     * Mengembalikan JSON (bisa dipanggil via AJAX)
     */
    public function markLunas(Request $request, $id)
    {
        $nota = Nota::findOrFail($id);

        // Jika sisa sudah 0, tidak perlu diupdate lagi
        $sisa = (int) $nota->sisa;
        if ($sisa <= 0) {
            return response()->json([
                'message' => 'Nota sudah lunas.',
                'nota' => $nota
            ], 200);
        }

        // Update: set uang_muka menjadi total, sisa jadi 0
        $nota->uang_muka = $nota->total;
        $nota->sisa = 0;

        // Jika ingin mencatat user yang menandai lunas, tambahkan di sini misal:
        // $nota->lunas_by = Auth::id();

        $nota->save();

        return response()->json([
            'message' => 'Berhasil menandai nota sebagai lunas.',
            'nota' => $nota
        ], 200);
    }
}
