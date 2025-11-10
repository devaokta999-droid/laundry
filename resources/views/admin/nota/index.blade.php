@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-primary fw-bold"> Nota Deva Laundry Satuan Digital</h3>

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
                    <input type="number" name="uang_muka" id="uang_muka" class="form-control" value="0" min="0">
                </div>
                <div class="mb-2">
                    <label class="fw-semibold">Sisa Pembayaran (Rp)</label>
                    <input type="text" id="sisa" class="form-control" readonly>
                </div>
                <button type="submit" class="btn btn-success w-100 mt-3 fw-semibold" id="submitNota">Simpan Nota</button>
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
                <tr data-nota-id="{{ $n->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td class="nama-customer">{{ $n->customer_name }}</td>
                    <td class="tgl-keluar">{{ $n->tgl_keluar ? \Carbon\Carbon::parse($n->tgl_keluar)->format('Y-m-d') : '-' }}</td>
                    <td class="cell-total">{{ number_format($n->total, 0, ',', '.') }}</td>
                    <td class="cell-uang-muka">{{ number_format($n->uang_muka, 0, ',', '.') }}</td>
                    <td class="cell-sisa">{{ number_format($n->sisa, 0, ',', '.') }}</td>
                    <td>{{ $n->user->name ?? '-' }}</td>
                    <td>
                        {{-- Tombol Print langsung (kiri dari Cetak PDF) --}}
                        <a href="{{ route('admin.nota.print_direct', $n->id) }}" class="btn btn-sm btn-secondary" target="_blank" title="Cetak langsung ke printer">
                            Print
                        </a>

                        {{-- Tombol Cetak PDF (sudah ada, jangan dihapus) --}}
                        <a href="{{ route('admin.nota.print', $n->id) }}" class="btn btn-sm btn-primary" target="_blank">
                            Cetak PDF
                        </a>

                        {{-- Tombol Lunas (di sebelah kanan Cetak PDF) --}}
                        <button type="button"
                                class="btn btn-sm btn-success btn-lunas"
                                data-id="{{ $n->id }}"
                                @if($n->sisa <= 0) disabled @endif>
                            @if($n->sisa <= 0) Lunas @else Tandai Lunas @endif
                        </button>
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

    // daftar default item (bisa kamu ganti / ambil dari server)
    const itemList = [
        { name: 'Baju Kaos/panjang', price: 1500 },
        { name: 'kemeja berkerah/panjang', price: 1500 },
        { name: 'Safari', price: 2000 },
        { name: 'kaos dalam', price: 1000 },
        { name: 'kaos kaki', price: 500 },
        { name: 'sapu tangan', price: 500 },
        { name: 'celana panjang', price: 2000 },
        { name: 'celana pendek', price: 1500 },
        { name: 'piyama', price: 1500 },
        { name: 'rompi kain/jeans', price: 1500 },
        { name: 'Sarung/kamen', price: 1500 },
        { name: 'baju hangat', price: 1500 },
        { name: 'jaket/jas', price: 2500 },
        { name: 'udeng', price: 500 },
        { name: 'topi', price: 1500 },
        { name: 'baju anak', price: 1000 },
        { name: 'dasi', price: 500 },
        { name: 'kebaya', price: 1500 },
        { name: 'rok', price: 1500 },
        { name: 'longdress', price: 2000 },
        { name: 'BH', price: 500 },
        { name: 'celana dalam', price: 500 },
        { name: 'Selendang', price: 500 },
        { name: 'longtorse/saput', price: 1000 },
        { name: 'sprei s/m/l', price: 4000 },
        { name: 'Sarung bantal', price: 1000 },
        { name: 'Handuk mandi S/M/L', price: 2500},
        { name: 'Handuk tangan', price: 1000 },
        { name: 'Bed cover S/M/L', price: 11000 },
        { name: 'selimut blangket', price: 5000 },
        { name: 'sepatu kain', price: 4000 },
        { name: 'korden L/S dll', price: 4000 },
        { name: 'Boneka K/S/B', price: 4000 },
        { name: 'Tas', price: 3000 },
        { name: 'karpet per meter ', price: 2500 },
        { name: 'Setrika baju anak', price: 500 },
        { name: 'Setrika Baju dewasa', price: 1000 },
        { name: 'Setrika baju bayi', price: 500 },
    ];

    // === Inisialisasi: buat beberapa baris dropdown default untuk mempercepat input ===
    // (Jika kamu ingin kosong awal, ganti loop ini atau hapus)
    itemList.forEach((item, index) => addDropdownRow(item.name, item.price, '', index + 1));

    // Tambah baris dropdown (pakai itemList)
    function addDropdownRow(name = '', price = '', qty = '', no = null) {
        const rowCount = notaBody.rows.length + 1;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${no ?? rowCount}</td>
            <td>
                <select name="items[${rowCount}][name]" class="form-select item-name" required>
                    ${itemList.map(i => `<option value="${i.name}" ${i.name === name ? 'selected' : ''}>${i.name}</option>`).join('')}
                </select>
            </td>
            <td><input type="number" name="items[${rowCount}][price]" class="form-control item-price" value="${price || 0}" min="0" readonly required></td>
            <td><input type="number" name="items[${rowCount}][quantity]" class="form-control item-qty" value="${qty || 0}" min="0" required></td>
            <td><input type="text" class="form-control subtotal" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">x</button></td>
        `;
        notaBody.appendChild(tr);
        recalc();
    }

    // Tombol "Tambah Item Baru" --> baris kosong yang boleh diisi nama & harga manual
    document.getElementById('addRow').addEventListener('click', () => {
        const rowCount = notaBody.rows.length + 1;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${rowCount}</td>
            <td>
                <input type="text" name="items[${rowCount}][name]" class="form-control" placeholder="Masukkan jenis item baru" required>
            </td>
            <td>
                <input type="number" name="items[${rowCount}][price]" class="form-control item-price" placeholder="Harga (Rp)" min="0" required>
            </td>
            <td>
                <input type="number" name="items[${rowCount}][quantity]" class="form-control item-qty" placeholder="Jumlah" min="0" required>
            </td>
            <td><input type="text" class="form-control subtotal" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm removeRow">x</button></td>
        `;
        notaBody.appendChild(tr);
        recalc();
    });

    // Ketika dropdown item berubah -> set harga otomatis
    notaBody.addEventListener('change', e => {
        if (e.target.classList.contains('item-name')) {
            const selected = itemList.find(i => i.name === e.target.value);
            const priceInput = e.target.closest('tr').querySelector('.item-price');
            if (priceInput) priceInput.value = selected ? selected.price : 0;
            recalc();
        }
    });

    // Hitung subtotal, total, total qty, sisa
    function recalc() {
        let total = 0, totalQty = 0;
        notaBody.querySelectorAll('tr').forEach(tr => {
            const qtyInput = tr.querySelector('.item-qty');
            const priceInput = tr.querySelector('.item-price');
            const subtotalEl = tr.querySelector('.subtotal');

            const qty = parseInt(qtyInput?.value || 0);
            const price = parseInt(priceInput?.value || 0);
            const subtotal = qty * price;

            if (subtotalEl) subtotalEl.value = subtotal;
            total += subtotal;
            totalQty += qty;
        });

        document.getElementById('total').value = total;
        document.getElementById('total_qty').value = totalQty;
        const uangMuka = parseInt(document.getElementById('uang_muka').value || 0);
        document.getElementById('sisa').value = Math.max(0, total - uangMuka);
    }

    // Input listener realtime (qty & price)
    notaBody.addEventListener('input', e => {
        if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price')) recalc();
    });
    document.getElementById('uang_muka').addEventListener('input', recalc);

    // Hapus baris
    notaBody.addEventListener('click', e => {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
            // re-numbering nomor baris supaya rapi setelah hapus
            renumberRows();
            recalc();
        }
    });

    // Renumber rows -> update nomor dan name indexes so server menerima items[...] array sequentially
    function renumberRows() {
        const trs = notaBody.querySelectorAll('tr');
        trs.forEach((tr, idx) => {
            const newIndex = idx + 1;
            // nomor kolom (td pertama)
            tr.querySelector('td:first-child').textContent = newIndex;

            // update name attributes for inputs/selects inside row
            tr.querySelectorAll('input, select').forEach(el => {
                // extract the input type suffix: e.g. items[3][name] -> [name]
                const attrName = el.getAttribute('name');
                if (!attrName) return;
                const match = attrName.match(/items\[\d+\]\[(.+)\]/);
                if (match) {
                    const key = match[1]; // name, price, quantity
                    el.setAttribute('name', `items[${newIndex}][${key}]`);
                }
            });
        });
    }

    // Sebelum submit: - hapus baris yang qty <= 0 (agar backend tidak menolak)
    //               - renumber rows agar nama array berurutan
    document.getElementById('notaForm').addEventListener('submit', function (e) {
        // validasi minimal 1 item dengan qty >= 1
        const rows = Array.from(notaBody.querySelectorAll('tr'));
        let hasValid = false;
        rows.forEach(tr => {
            const qty = parseInt(tr.querySelector('.item-qty')?.value || 0);
            if (qty > 0) hasValid = true;
        });

        if (!hasValid) {
            e.preventDefault();
            alert('❌ Tidak dapat menyimpan nota kosong! Isi minimal 1 item dengan jumlah >= 1.');
            return;
        }

        // Hapus baris yang qty <= 0 (tidak akan dikirim ke server)
        rows.forEach(tr => {
            const qty = parseInt(tr.querySelector('.item-qty')?.value || 0);
            if (qty <= 0) tr.remove();
        });

        // renumber agar items[1], items[2], ... berurutan
        renumberRows();

        // recalc satu kali lagi sebelum submit
        recalc();

        // setelah ini form akan submit normal (POST) ke route admin.nota.store
    });

    // Pencarian riwayat (filter)
    const searchInput = document.getElementById('searchNota');
    const rowsRiwayat = document.querySelectorAll('#tableRiwayat tbody tr');
    searchInput.addEventListener('input', function() {
        const keyword = this.value.toLowerCase();
        rowsRiwayat.forEach(row => {
            const nama = row.querySelector('.nama-customer')?.textContent.toLowerCase() || '';
            const tglKeluar = row.querySelector('.tgl-keluar')?.textContent.toLowerCase() || '';
            row.style.display = (nama.includes(keyword) || tglKeluar.includes(keyword)) ? '' : 'none';
        });
    });

    // inisialisasi hitung awal
    recalc();

    // ==========================
    // Tombol LUNAS (AJAX + disable after click)
    // ==========================
    const csrfToken = '{{ csrf_token() }}';
    document.querySelectorAll('.btn-lunas').forEach(btn => {
        btn.addEventListener('click', async function () {
            if (!confirm('Tandai nota ini sebagai LUNAS? Tindakan ini akan mengatur uang muka = total dan sisa = 0.')) return;

            const notaId = this.dataset.id;
            const self = this;
            // disable segera untuk mencegah double click
            self.disabled = true;
            self.textContent = 'Memproses...';

            try {
                const res = await fetch("{{ url('admin/nota') }}/" + notaId + "/lunas", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                });

                const data = await res.json();
                if (res.ok) {
                    // update UI row: sisa => 0, uang_muka => total
                    const row = document.querySelector('tr[data-nota-id="'+notaId+'"]');
                    if (row) {
                        const totalCell = row.querySelector('.cell-total');
                        const uangMukaCell = row.querySelector('.cell-uang-muka');
                        const sisaCell = row.querySelector('.cell-sisa');

                        // parse angka dari cell total (dalam format number_format Indonesian) -> kita harus ambil raw total dari response jika ada
                        if (data.nota) {
                            // server mengembalikan nota; tampilkan formatted numbers
                            const formatter = new Intl.NumberFormat('id-ID');
                            uangMukaCell.textContent = formatter.format(data.nota.uang_muka || 0);
                            sisaCell.textContent = formatter.format(data.nota.sisa || 0);
                        } else if (totalCell) {
                            // fallback: set sisa 0, uang_muka sama dengan total (mencoba parse totalCell)
                            const totalText = totalCell.textContent.replace(/\./g,'').replace(/,/g,'');
                            const totalVal = parseInt(totalText) || 0;
                            uangMukaCell.textContent = new Intl.NumberFormat('id-ID').format(totalVal);
                            sisaCell.textContent = new Intl.NumberFormat('id-ID').format(0);
                        }
                    }

                    self.textContent = 'Lunas';
                    self.disabled = true;
                    alert(data.message || 'Nota berhasil ditandai lunas.');
                } else {
                    // gagal
                    self.disabled = false;
                    self.textContent = 'Tandai Lunas';
                    alert(data.message || 'Gagal menandai lunas. Coba lagi.');
                }
            } catch (err) {
                console.error(err);
                self.disabled = false;
                self.textContent = 'Tandai Lunas';
                alert('Terjadi kesalahan jaringan. Coba lagi.');
            }
        });
    });
});
</script>
@endsection
