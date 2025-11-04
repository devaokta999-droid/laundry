@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-primary fw-bold">🧾 Nota Deva Laundry Satuan Digital</h3>

    {{-- ✅ Notifikasi sukses & error --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- ✅ Form Input Nota --}}
    <form method="POST" action="{{ route('admin.nota.store') }}" id="notaForm">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="fw-semibold">Nama Pelanggan</label>
                <input type="text" name="customer_name" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold">Alamat</label>
                <input type="text" name="customer_address" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="fw-semibold">Tanggal Masuk</label>
                <input type="text" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
            </div>
            <div class="col-md-2">
                <label class="fw-semibold">Tanggal Keluar</label>
                <input type="date" name="tgl_keluar" class="form-control">
            </div>
        </div>

        {{-- ✅ Tabel Input Item --}}
        <table class="table table-bordered align-middle text-center" id="notaTable">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Jenis Pakaian</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan (Rp)</th>
                    <th>Total (Rp)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="notaBody"></tbody>
        </table>

        <button type="button" id="addRow" class="btn btn-outline-primary mb-3"> + Tambah Item</button>

        {{-- ✅ Total & Uang Muka --}}
        <div class="row text-end">
            <div class="col-md-4 offset-md-8">
                <div class="mb-2">
                    <label class="fw-semibold">Jumlah Total (Rp)</label>
                    <input type="text" id="total" name="total" class="form-control" readonly>
                </div>
                <div class="mb-2">
                    <label class="fw-semibold">Uang Muka (Rp)</label>
                    <input type="number" name="uang_muka" id="uang_muka" class="form-control" value="0">
                </div>
                <div class="mb-2">
                    <label class="fw-semibold">Sisa Pembayaran (Rp)</label>
                    <input type="text" id="sisa" class="form-control" readonly>
                </div>
                <button class="btn btn-success w-100 mt-3 fw-semibold">Simpan Nota</button>
            </div>
        </div>
    </form>

    {{-- ✅ Riwayat Nota --}}
    <hr class="my-5">
    <h4 class="text-primary fw-bold mb-3">Riwayat Nota Laundry</h4>

    <div class="table-responsive">
        <table class="table table-striped align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama Pelanggan</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Keluar</th>
                    <th>Total (Rp)</th>
                    <th>Uang Muka</th>
                    <th>Sisa</th>
                    <th>Dibuat Oleh</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notas as $n)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $n->customer_name }}</td>

                    <td>
                        @if($n->tgl_masuk)
                            {{ \Carbon\Carbon::parse($n->tgl_masuk)->format('Y-m-d') }}
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        @if($n->tgl_keluar)
                            {{ \Carbon\Carbon::parse($n->tgl_keluar)->format('Y-m-d') }}
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ number_format($n->total, 0, ',', '.') }}</td>
                    <td>{{ number_format($n->uang_muka, 0, ',', '.') }}</td>
                    <td>{{ number_format($n->sisa, 0, ',', '.') }}</td>
                    <td>{{ $n->user->name ?? '-' }}</td>
                    <td>
                        <a href="{{ route('admin.nota.print', $n->id) }}" class="btn btn-sm btn-primary" target="_blank">
                            Cetak PDF
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-muted">Belum ada nota dibuat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ✅ Script Dinamis --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    let rowCount = 0;

    // ✅ Daftar item pakaian satuan beserta harga (bisa kamu ubah sesuka hati)
    const itemList = [
        { name: 'baju Kaos', price: 1000 },
        { name: 'Celana', price: 2000 },
        { name: 'Kemeja', price: 2500 },
        { name: 'Jaket', price: 3000 },
        { name: 'Sprei', price: 4000 },
        { name: 'Bed Cover', price: 5000 },
        { name: 'Handuk', price: 1500 },
        { name: 'celana panjang', price: 1500 },
        { name: 'kemeja', price: 1500 },
        { name: 'kaos kaki', price: 1500 },
        { name: 'selimut', price: 1500 },
        { name: 'kamen', price: 1500 },
        { name: 'udeng', price: 1500 },
        { name: 'sapari', price: 1500 },
        { name: 'boneka', price: 1500 },
        { name: 'sepatu', price: 1500 },
        { name: 'baju dalam', price: 1500 },
        { name: 'celana dalam', price: 1500 },
        { name: 'setrika baju anak', price: 1500 },
        { name: 'setrika baju dewasa', price: 1500 },
        { name: 'cuci saja', price: 1500 },
        { name: 'hodie', price: 1500 },
        { name: 'baju kaos dewasa', price: 1500 },
        { name: 'baju anak', price: 1500 },
        { name: 'Sarung', price: 1500 }
    ];

    // ✅ Fungsi menghitung total
    function recalc() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(el => {
            total += parseInt(el.value || 0);
        });
        document.getElementById('total').value = total;
        const uangMuka = parseInt(document.getElementById('uang_muka').value || 0);
        document.getElementById('sisa').value = total - uangMuka;
    }

    // ✅ Tambah baris item baru
    document.getElementById('addRow').addEventListener('click', function () {
        rowCount++;

        // Buat dropdown item
        let options = `<option value="">-- Pilih Jenis Pakaian --</option>`;
        itemList.forEach(i => {
            options += `<option value="${i.name}" data-price="${i.price}">${i.name}</option>`;
        });

        const row = `
        <tr>
            <td>${rowCount}</td>
            <td>
                <select name="items[${rowCount}][name]" class="form-select item-select" required>
                    ${options}
                </select>
            </td>
            <td><input type="number" name="items[${rowCount}][quantity]" class="form-control qty" min="1" value="1"></td>
            <td><input type="number" name="items[${rowCount}][price]" class="form-control price" readonly></td>
            <td><input type="text" name="items[${rowCount}][subtotal]" class="form-control subtotal" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">x</button></td>
        </tr>`;
        document.getElementById('notaBody').insertAdjacentHTML('beforeend', row);
    });

    // ✅ Ganti item → otomatis isi harga
    document.body.addEventListener('change', function (e) {
        if (e.target.classList.contains('item-select')) {
            const price = e.target.options[e.target.selectedIndex].getAttribute('data-price') || 0;
            const row = e.target.closest('tr');
            row.querySelector('.price').value = price;
            const qty = parseInt(row.querySelector('.qty').value || 1);
            row.querySelector('.subtotal').value = price * qty;
            recalc();
        }
    });

    // ✅ Ubah jumlah → hitung ulang subtotal
    document.body.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty')) {
            const row = e.target.closest('tr');
            const qty = parseInt(row.querySelector('.qty').value || 0);
            const price = parseInt(row.querySelector('.price').value || 0);
            row.querySelector('.subtotal').value = qty * price;
            recalc();
        }
        if (e.target.id === 'uang_muka') recalc();
    });

    // ✅ Hapus baris item
    document.body.addEventListener('click', function (e) {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
            recalc();
        }
    });
});
</script>
@endsection
