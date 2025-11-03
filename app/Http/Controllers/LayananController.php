<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class LayananController extends Controller
{
    /**
     * Terapkan middleware auth agar hanya user login yang bisa akses.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 🧺 Tampilkan daftar semua layanan laundry.
     */
    public function index()
    {
        $services = Service::orderBy('created_at', 'desc')->get();

        return view('layanan.index', compact('services'));
    }

    /**
     * ➕ Form untuk menambah layanan baru.
     */
    public function create()
    {
        return view('layanan.create');
    }

    /**
     * 💾 Simpan layanan baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        Service::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return redirect()->route('layanan.index')->with('success', '✅ Layanan berhasil ditambahkan!');
    }

    /**
     * ✏️ Form untuk mengedit layanan.
     */
    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('layanan.edit', compact('service'));
    }

    /**
     * 🔄 Update data layanan di database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ]);

        $service = Service::findOrFail($id);
        $service->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return redirect()->route('layanan.index')->with('success', '✅ Layanan berhasil diperbarui!');
    }

    /**
     * 🗑️ Hapus layanan dari database.
     */
    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        return redirect()->route('layanan.index')->with('success', '🗑️ Layanan berhasil dihapus!');
    }
}
