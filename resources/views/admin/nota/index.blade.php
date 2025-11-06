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

        {{-- ✅ Daftar Jenis Pakaian --}}
        <table class="table table-bordered align-middle text-center" id="notaTable">
            <thead class="table-primary">
                <tr>
                    <th>No</th>
                    <th>Jenis Pakaian</th>
                    <th>Harga (Rp)</th>
                    <th>Jumlah</th>
                    <th>Subtotal (Rp)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="notaBody"></tbody>
        </table>

        <button type="button" id="addRow" class="btn btn-outline-primary mb-3">+ Tambah Item Baru</button>

        {{-- ✅ Total & Uang Muka --}}
        <div class="row text-end">
            <div class="col-md-4 offset-md-8">
                <div class="mb-2">
                    <label class="fw-semibold">Total Banyak Pakaian</label>
                    <input type="text" id="total_qty" class="form-control" readonly>
                </div>
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
                <button class="btn btn-success w-100 mt-3 fw-semibold" id="submitNota">Simpan Nota</button>
            </div>
        </div>
    </form>

    {{-- ✅ Riwayat Nota --}}
    <hr class="my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-primary fw-bold mb-0">Riwayat Nota Laundry</h4>
        <input type="text" id="searchNota" class="form-control w-25" placeholder="🔍 Cari nama pelanggan atau tanggal...">
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle text-center" id="tableRiwayat">
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
                    <td class="nama-customer">{{ $n->customer_name }}</td>
                    <td class="tgl-masuk">{{ $n->tgl_masuk ? \Carbon\Carbon::parse($n->tgl_masuk)->format('Y-m-d') : '-' }}</td>
                    <td class="tgl-keluar">{{ $n->tgl_keluar ? \Carbon\Carbon::parse($n->tgl_keluar)->format('Y-m-d') : '-' }}</td>
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
    const notaBody = document.getElementById('notaBody');
    const itemList = [
        { name: 'Baju Kaos', price: 1000 },
        { name: 'Celana', price: 2000 },
        { name: 'Kemeja', price: 2500 },
        { name: 'Jaket', price: 3000 },
        { name: 'Sprei', price: 4000 },
        { name: 'Bed Cover', price: 5000 },
        { name: 'Handuk', price: 1500 },
        { name: 'Sarung', price: 1500 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'Kaos Kaki', price: 1000 },
        { name: 'setrika baju dewasa', price: 1000 },
        { name: 'setrika baju anak', price: 1000 },
        { name: 'Sepatu', price: 3000 },
    ];

    // ✅ Generate tabel awal
    itemList.forEach((item, index) => addRow(item.name, item.price, '', index + 1));

    // ✅ Tambah baris baru manual
    document.getElementById('addRow').addEventListener('click', () => addRow());

    function addRow(name = '', price = '', qty = '', no = null) {
        const rowCount = notaBody.rows.length + 1;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${no ?? rowCount}</td>
            <td>
                <select name="items[${rowCount}][name]" class="form-select item-name">
                    ${itemList.map(i => `<option value="${i.name}" ${i.name === name ? 'selected' : ''}>${i.name}</option>`).join('')}
                </select>
            </td>
            <td><input type="number" name="items[${rowCount}][price]" class="form-control item-price" value="${price || 0}" min="0" readonly></td>
            <td><input type="number" name="items[${rowCount}][quantity]" class="form-control item-qty" value="${qty || 0}" min="0"></td>
            <td><input type="text" class="form-control subtotal" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">x</button></td>
        `;
        notaBody.appendChild(tr);
        recalc();
    }

    // ✅ Update harga otomatis dari dropdown
    notaBody.addEventListener('change', e => {
        if (e.target.classList.contains('item-name')) {
            const selected = itemList.find(i => i.name === e.target.value);
            e.target.closest('tr').querySelector('.item-price').value = selected ? selected.price : 0;
            recalc();
        }
    });

    // ✅ Hitung total & subtotal
    function recalc() {
        let total = 0, totalQty = 0;
        notaBody.querySelectorAll('tr').forEach(tr => {
            const qty = parseInt(tr.querySelector('.item-qty').value) || 0;
            const price = parseInt(tr.querySelector('.item-price').value) || 0;
            const subtotal = qty * price;
            tr.querySelector('.subtotal').value = subtotal;
            total += subtotal;
            totalQty += qty;
        });
        document.getElementById('total').value = total;
        document.getElementById('total_qty').value = totalQty;
        const uangMuka = parseInt(document.getElementById('uang_muka').value || 0);
        document.getElementById('sisa').value = total - uangMuka;
    }

    // ✅ Listener input realtime
    notaBody.addEventListener('input', e => {
        if (e.target.classList.contains('item-qty')) recalc();
    });
    document.getElementById('uang_muka').addEventListener('input', recalc);

    // ✅ Hapus baris
    notaBody.addEventListener('click', e => {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
            recalc();
        }
    });

    // ✅ Validasi sebelum submit
    document.getElementById('notaForm').addEventListener('submit', function (e) {
        let totalQty = 0;
        notaBody.querySelectorAll('.item-qty').forEach(input => totalQty += parseInt(input.value || 0));
        if (totalQty <= 0) {
            e.preventDefault();
            alert('❌ Tidak dapat menyimpan nota kosong! Isi jumlah pakaian terlebih dahulu.');
        }
    });

    // ✅ Fitur Pencarian Riwayat Laundry (Nama & Tanggal)
    const searchInput = document.getElementById('searchNota');
    const rows = document.querySelectorAll('#tableRiwayat tbody tr');
    searchInput.addEventListener('input', function() {
        const keyword = this.value.toLowerCase();
        rows.forEach(row => {
            const nama = row.querySelector('.nama-customer')?.textContent.toLowerCase() || '';
            const tglMasuk = row.querySelector('.tgl-masuk')?.textContent.toLowerCase() || '';
            const tglKeluar = row.querySelector('.tgl-keluar')?.textContent.toLowerCase() || '';
            row.style.display = (nama.includes(keyword) || tglMasuk.includes(keyword) || tglKeluar.includes(keyword)) ? '' : 'none';
        });
    });
});
</script>
@endsection
