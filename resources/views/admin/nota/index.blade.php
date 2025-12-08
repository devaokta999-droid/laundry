@extends('layouts.app')

@section('content')
<div class="container mt-4 nota-page-container">
    <style>
        .nota-page-container{
            width: 100%;
            max-width: 100%;
            background: radial-gradient(circle at top left, rgba(255,255,255,0.97), rgba(239,244,255,0.95));
            border-radius: 24px;
            padding: 24px 24px 30px;
            box-shadow: 0 26px 70px rgba(15,23,42,0.16);
            border: 1px solid rgba(255,255,255,0.9);
        }
        /* Action buttons layout for nota rows: horizontal, spaced, wrap if needed */
        .nota-actions{
            display:flex;
            gap:0.5rem;
            flex-wrap:wrap;
            align-items:center;
            justify-content:flex-start;
        }
        .nota-actions .btn{
            white-space:nowrap;
        }
        @media (max-width: 992px){
            .nota-actions{gap:0.35rem}
        }
        /* Badges Lunas / Belum Lunas (lebih besar & jelas) */
        .badge-lunas,
        .badge-belum{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:4px 10px;
            border-radius:999px;
            font-size:12px;
            font-weight:600;
            line-height:1.2;
            min-width:80px;
            text-align:center;
        }
        .badge-lunas{
            background:#22c55e;
            color:#fff;
        }
        .badge-belum{
            background:#ff4d4f;
            color:#fff;
        }
        /* macOS-style date picker wrapper */
        .macos-date-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .macos-date-input {
            border-radius: 999px;
            padding: 10px 42px 10px 16px;
            height: 44px;
            font-size: 0.95rem;
            border: 1px solid rgba(0,0,0,0.06);
            background: linear-gradient(135deg, #fdfdfd, #f4f5f9);
            box-shadow: 0 6px 18px rgba(15,23,42,0.06);
        }
        .macos-date-input::-webkit-calendar-picker-indicator {
            opacity: 0;
            -webkit-appearance: none;
            display: none;
        }
        .macos-date-input:focus {
            border-color: #007aff;
            box-shadow: 0 0 0 2px rgba(0,122,255,0.25);
            outline: none;
        }
        .macos-date-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #007aff;
            cursor: pointer;
            font-size: 18px;
            pointer-events: auto;
        }
        /* macOS glassy refresh button */
        .mac-refresh-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            width: 100%;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.6);
            padding: 10px 16px;
            font-weight: 600;
            font-size: 0.95rem;
            color: #0f172a;
            background: linear-gradient(135deg, #f9fafb, #e5f1ff);
            box-shadow: 0 6px 18px rgba(15,23,42,0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            transition: all 0.18s ease;
        }
        .mac-refresh-btn-icon {
            width: 16px;
            height: 16px;
        }
        .mac-refresh-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 26px rgba(15,23,42,0.18);
            background: linear-gradient(135deg, #e8f2ff, #ffffff);
        }
        .mac-refresh-btn:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(15,23,42,0.18) inset;
        }
        .traffic-lights {
            display: inline-flex;
            gap: 0.35rem;
            margin-right: 0.6rem;
        }
        .traffic-light {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            border: 1px solid rgba(0,0,0,0.08);
        }
        .traffic-light.red { background: #ff0000ff; }
        .traffic-light.yellow { background: #ffae00ff; }
        .traffic-light.green { background: #00ff26ff; }

        /* Apple-style card sections */
        .nota-section-card{
            border-radius:18px;
            background: rgba(255,255,255,0.94);
            border:1px solid rgba(226,232,240,0.9);
            box-shadow: 0 10px 32px rgba(15,23,42,0.06);
            padding:18px 18px 20px;
            margin-bottom:22px;
        }
        .nota-section-card h4{
            font-size:1.05rem;
            font-weight:700;
            color:#111827;
        }
        .nota-table-wrapper{
            border-radius:18px;
            overflow:hidden;
            border:1px solid rgba(226,232,240,0.9);
            background:rgba(255,255,255,0.98);
            box-shadow: 0 14px 40px rgba(15,23,42,0.08);
        }
        .nota-table-wrapper table{
            margin-bottom:0;
        }
        #notaTable thead,
        #tableRiwayat thead,
        .nota-table-wrapper thead{
            background: linear-gradient(135deg,#f3f4f6,#e5f2ff);
        }
        #notaTable thead th,
        #tableRiwayat thead th,
        .nota-table-wrapper thead th{
            border-bottom:1px solid rgba(209,213,219,0.9);
            font-weight:600;
            font-size:.82rem;
            text-transform:uppercase;
            letter-spacing:.06em;
        }
        #notaTable tbody tr:nth-child(even),
        #tableRiwayat tbody tr:nth-child(even),
        .nota-table-wrapper tbody tr:nth-child(even){
            background:#fafbff;
        }
        #notaTable tbody tr:hover,
        #tableRiwayat tbody tr:hover,
        .nota-table-wrapper tbody tr:hover{
            background:#eef4ff;
        }

        .btn-mac-pill{
            border-radius:999px !important;
            font-weight:600;
            padding:.4rem 1rem;
            border-width:1.3px;
        }
        .btn-mac-primary{
            background:linear-gradient(135deg,#0a84ff,#0051cc);
            border-color:rgba(255,255,255,0.4);
            color:#fff;
            box-shadow:0 8px 22px rgba(15,23,42,0.25);
        }
        .btn-mac-primary:hover{
            background:linear-gradient(135deg,#5eb1ff,#0a84ff);
            color:#fff;
        }
        .btn-mac-ghost{
            background:rgba(255,255,255,0.7);
            border-color:rgba(148,163,184,0.7);
            color:#0f172a;
        }
        .btn-mac-ghost:hover{
            background:#e5f1ff;
            color:#0f172a;
        }

        .nota-filters-chip-group .btn{
            border-radius:999px;
            font-size:.8rem;
            padding:.25rem .8rem;
        }
        .nota-filters-chip-group .btn.active{
            background:#0f172a;
            color:#fff;
            border-color:transparent;
        }

        .nota-search-wrapper{
            max-width:280px;
        }
        .nota-search-input{
            border-radius:999px;
            padding-left:2.2rem;
        }
        .nota-search-input:focus{
            box-shadow:0 0 0 2px rgba(0,122,255,0.3);
            border-color:#0a84ff;
        }

        .nota-section-title{
            font-size:1.05rem;
            font-weight:700;
            color:#0f172a;
        }
        .nota-header-pill{
            display:inline-flex;
            align-items:center;
            gap:0.4rem;
            padding:4px 11px;
            border-radius:999px;
            background:rgba(15,23,42,0.03);
            border:1px solid rgba(148,163,184,0.35);
            font-size:0.78rem;
            letter-spacing:0.18em;
            text-transform:uppercase;
            color:#6b7280;
            margin-bottom:6px;
        }
        .nota-header-pill-dot{
            width:7px;
            height:7px;
            border-radius:999px;
            background:#22c55e;
            box-shadow:0 0 0 4px rgba(34,197,94,0.25);
        }
        .nota-page-container .form-control,
        .nota-page-container .form-select{
            border-radius:12px;
            border-color:rgba(209,213,219,0.9);
            box-shadow:0 1px 2px rgba(15,23,42,0.04);
        }
        .nota-page-container .form-control:focus,
        .nota-page-container .form-select:focus{
            border-color:#0a84ff;
            box-shadow:
                0 0 0 1px rgba(59,130,246,0.25),
                0 0 0 4px rgba(191,219,254,0.85);
            outline:none;
        }

        @media (max-width: 768px){
            .nota-search-wrapper{
                max-width:100%;
                margin-top:.75rem;
            }
        }
    </style>
    <div class="d-flex flex-wrap justify-content-between align-items-start mb-3 gap-2">
        <div>
            <div class="nota-header-pill">
                <span class="nota-header-pill-dot"></span>
                NOTA DIGITAL
            </div>
            <h3 class="mb-1 text-primary fw-bold">Nota Deva Laundry Satuan Digital</h3>
            <span class="text-muted" style="font-size:.9rem;">Buat nota baru, pantau total, dan kelola riwayat pembayaran.</span>
        </div>
        <div class="d-flex align-items-center mt-2 mt-md-0">
            <div class="traffic-lights me-2">
                <span class="traffic-light red"></span>
                <span class="traffic-light yellow"></span>
                <span class="traffic-light green"></span>
            </div>
        </div>
    </div>

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
                <input type="text" name="customer_name" class="form-control" required
                       value="{{ old('customer_name', $prefill['customer_name'] ?? '') }}">
            </div>
            <div class="col-md-4">
                <label class="fw-semibold">Alamat</label>
                <input type="text" name="customer_address" class="form-control"
                       value="{{ old('customer_address', $prefill['customer_address'] ?? '') }}">
            </div>
            <div class="col-md-2">
                <label class="fw-semibold">Tanggal Keluar</label>
                <div class="macos-date-wrapper">
                    <input type="date" name="tgl_keluar" id="tgl_keluar" class="form-control macos-date-input">
                    <span class="macos-date-icon" id="tgl_keluar_icon">&#128197;</span>
                </div>
            </div>
            {{-- ✅ Tambahan: Tombol Refresh (Diubah menggunakan tag <img>) --}}
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="mac-refresh-btn" onclick="window.location.reload();">
                    {{-- Ganti SVG dengan tag IMG yang merujuk ke refresh.png. Sesuaikan path asset() jika perlu. --}}
                    <img src="{{ asset('images/rfsh.png') }}" alt="Refresh" class="mac-refresh-btn-icon">
                    Refresh
                </button>
            </div>
            {{-- ❌ Perhatikan penyesuaian kelas col-md-X di atas untuk menampung tombol ini --}}
            {{-- Sebelumnya: col-md-2 untuk Tanggal Keluar. Sekarang: col-md-2 untuk Tgl Keluar dan col-md-2 untuk Refresh --}}
        </div>
        <input type="hidden" name="linked_order_id" value="{{ old('linked_order_id', $prefill['order_id'] ?? '') }}">

        {{-- ✅ Daftar Jenis Pakaian --}}
        <table class="table align-middle text-center" id="notaTable">
            <thead>
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

        <button type="button" id="addRow" class="btn btn-outline-primary btn-mac-pill btn-mac-ghost mb-3">+ Tambah Item Baru</button>

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
                    <label class="fw-semibold">Sisa (Rp)</label>
                    <input type="text" id="sisa" class="form-control" readonly>
                </div>
                <button type="submit" class="btn btn-success w-100 mt-3 fw-semibold btn-mac-pill btn-mac-primary" id="submitNota">Simpan Nota</button>
            </div>
        </div>
    </form>

    {{-- ✅ Riwayat Nota --}}
    <hr class="my-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-primary fw-bold mb-0">Riwayat Nota Laundry</h4>
        
        {{-- ✅ Perubahan hanya di bagian ini --}}
        <div class="position-relative nota-search-wrapper">
            <input type="text" id="searchNota" class="form-control nota-search-input" placeholder="Cari nama pelanggan atau tanggal...">
        </div>
    </div>
    <div class="d-flex justify-content-end mb-3 gap-2 nota-filters-chip-group">
        <button type="button" class="btn btn-sm btn-outline-secondary filter-status-riwayat active" data-status="all">
            Semua
        </button>
        <button type="button" class="btn btn-sm btn-outline-success filter-status-riwayat" data-status="lunas">
            Lunas
        </button>
        <button type="button" class="btn btn-sm btn-outline-warning filter-status-riwayat" data-status="belum">
            Belum Lunas
        </button>
    </div>

    <div class="table-responsive">
        <table class="table table-striped align-middle text-center" id="tableRiwayat">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Nama Pelanggan</th>
                    <th>Kasir</th>
                    <th>Tanggal</th>
                    <th>Pembayaran</th>
                    <th>Terbayar (Rp)</th>
                    <th>Sisa (Rp)</th>
                    <th>Total Awal (Rp)</th>
                    <th>Total (Rp)</th>
                    <th>Diskon (Rp)</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($notas as $n)
                <tr data-nota-id="{{ $n->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td class="nama-customer">{{ $n->customer_name }}</td>
                    <td class="nama-kasir">{{ optional($n->user)->name ?? '-' }}</td>
                    <td class="tgl-keluar">{{ $n->tgl_keluar ? \Carbon\Carbon::parse($n->tgl_keluar)->format('Y-m-d') : '-' }}</td>
                    <td class="pembayaran-cell">
                        @php
                            $payments = $n->payments ?? collect();
                            $cashTotal = $payments->where('type','cash')->sum('amount');
                            $transferGrouped = $payments->where('type','transfer')->groupBy('method');
                            $totalDiscount = $payments->sum('discount_amount');
                        @endphp
                        @if($cashTotal > 0)
                            <span class="badge bg-success">Cash: {{ number_format($cashTotal,0,',','.') }}</span>
                        @endif
                        @foreach($transferGrouped as $method => $group)
                            @php $methodLabel = $method ? ucfirst($method) : 'Transfer'; @endphp
                            <span class="badge bg-info text-dark">Transfer ({{ $methodLabel }}): {{ number_format($group->sum('amount'),0,',','.') }}</span>
                        @endforeach
                        @if($totalDiscount > 0)
                            <span class="badge bg-warning text-dark">Diskon: {{ number_format($totalDiscount,0,',','.') }}</span>
                        @endif
                        @if($payments->count() == 0)
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="cell-terbayar">{{ number_format($n->payments ? $n->payments->sum('amount') : 0, 0, ',', '.') }}</td>
                    <td class="cell-sisa">{{ number_format($n->sisa ?? max(0, $n->total - ($n->payments ? $n->payments->sum('amount') : 0)), 0, ',', '.') }}</td>
                    <td class="cell-original">{{ number_format($n->items ? $n->items->sum('subtotal') : $n->total, 0, ',', '.') }}</td>
                    <td class="cell-total">{{ number_format($n->total, 0, ',', '.') }}</td>
                    <td class="cell-diskon">{{ number_format($n->payments ? $n->payments->sum('discount_amount') : 0, 0, ',', '.') }}</td>
                    <td>
                        <div class="nota-actions">
                            @php
                                $terbayar = $n->payments ? $n->payments->sum('amount') : 0;
                                $sisa_now = max(0, $n->total - $terbayar);
                            @endphp

                            {{-- Tombol Print langsung (kiri dari Cetak PDF) --}}
                            <a href="{{ route('admin.nota.print_direct', $n->id) }}" class="btn btn-sm btn-outline-secondary btn-mac-pill" target="_blank" title="Cetak langsung ke printer">Print</a>

                            {{-- Tombol Show Nota (pindah halaman di tab yang sama) --}}
                            <a href="{{ route('admin.nota.show', $n->id) }}" class="btn btn-sm btn-outline-info btn-mac-pill" title="Lihat detail nota">Show Nota</a>

                            {{-- Badge Lunas / Belum Lunas --}}
                            @if($sisa_now <= 0)
                                <span class="badge bg-success badge-lunas">Lunas</span>
                            @else
                                <span class="badge-belum">Belum Lunas</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="13" class="text-muted">Belum ada nota dibuat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ✅ Script Dinamis --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Modal Pembayaran (digunakan oleh index) -->
<div class="modal fade" id="modalPayIndex" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="payFormIndex">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="payNotaId" name="nota_id" value="">

                    <div class="mb-3">
                        <label class="form-label">Tipe Pembayaran</label>
                        <select name="type" id="paymentTypeIdx" class="form-select" required>
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>

                    <div class="mb-3" id="methodWrapperIdx" style="display:none;">
                        <label class="form-label">Metode Transfer</label>
                        <select name="method" id="paymentMethodIdx" class="form-select">
                            <option value="GOPAY">Gopay</option>
                            <option value="DANA">Dana</option>
                            <option value="SHOPEEPAY">ShopeePay</option>
                            <option value="PAYLATER">PayLater</option>
                            <option value="BCA">BCA</option>
                            <option value="BNI">BNI</option>
                            <option value="QRIS">QRIS</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Diskon (%) <small class="text-muted">opsional</small></label>
                        <input type="number" step="0.01" min="0" max="100" name="discount_percent" id="discountPercentIdx" class="form-control" value="0">
                        <div class="form-text mb-2">Diskon persentase yang diterapkan ke total nota sebelum menghitung sisa.</div>

                        <div class="mb-2">
                            <label class="form-label">Total Setelah Diskon (Rp)</label>
                            <input type="text" id="discountedTotalIdx" class="form-control" readonly>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Diskon (Rp)</label>
                            <input type="text" id="discountAmountIdx" class="form-control" readonly>
                        </div>

                        <div class="mb-2" id="cashGivenWrapperIdx">
                            <label class="form-label">Uang Diterima (Cash) (Rp)</label>
                            <input type="number" step="0.01" min="0.01" id="cashGivenIdx" class="form-control">
                            <div class="form-text">Isi jika pembayaran tunai, untuk menghitung kembalian.</div>
                        </div>
                        <label class="form-label">Jumlah Bayar (Rp)</label>
                        <input type="number" step="0.01" min="0.01" name="amount" id="payAmountIdx" class="form-control" value="0" required>
                        <div class="form-text">Masukkan jumlah yang dibayarkan (default: sisa setelah diskon).</div>
                    </div>

                    <div id="payAlertIdx" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses Bayar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // macOS-style date input: click icon to open native picker
    const tglKeluarInput = document.getElementById('tgl_keluar');
    const tglKeluarIcon = document.getElementById('tgl_keluar_icon');
    if (tglKeluarInput && tglKeluarIcon) {
        tglKeluarIcon.addEventListener('click', function () {
            try {
                if (tglKeluarInput.showPicker) {
                    tglKeluarInput.showPicker();
                } else {
                    tglKeluarInput.focus();
                }
            } catch (e) {
                tglKeluarInput.focus();
            }
        });
    }

    const notaBody = document.getElementById('notaBody');

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

    itemList.forEach((item, index) => addDropdownRow(item.name, item.price, '', index + 1));

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

    notaBody.addEventListener('change', e => {
        if (e.target.classList.contains('item-name')) {
            const selected = itemList.find(i => i.name === e.target.value);
            const priceInput = e.target.closest('tr').querySelector('.item-price');
            if (priceInput) priceInput.value = selected ? selected.price : 0;
            recalc();
        }
    });

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
        // compute formatted sisa = total (uang muka tidak digunakan lagi)
        const sisaEl = document.getElementById('sisa');
        const formatter = new Intl.NumberFormat('id-ID');
        const sisaVal = total;
        if (sisaEl) sisaEl.value = formatter.format(sisaVal);
    }

    notaBody.addEventListener('input', e => {
        if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price')) recalc();
    });

    notaBody.addEventListener('click', e => {
        if (e.target.classList.contains('removeRow')) {
            e.target.closest('tr').remove();
            renumberRows();
            recalc();
        }
    });

    function renumberRows() {
        const trs = notaBody.querySelectorAll('tr');
        trs.forEach((tr, idx) => {
            const newIndex = idx + 1;
            tr.querySelector('td:first-child').textContent = newIndex;
            tr.querySelectorAll('input, select').forEach(el => {
                const attrName = el.getAttribute('name');
                if (!attrName) return;
                const match = attrName.match(/items\[\d+\]\[(.+)\]/);
                if (match) {
                    const key = match[1];
                    el.setAttribute('name', `items[${newIndex}][${key}]`);
                }
            });
        });
    }

    document.getElementById('notaForm').addEventListener('submit', function (e) {
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

        rows.forEach(tr => {
            const qty = parseInt(tr.querySelector('.item-qty')?.value || 0);
            if (qty <= 0) tr.remove();
        });

        renumberRows();
        recalc();
    });

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

    recalc();

    // Update badges based on sisa value for all existing rows
    function refreshBadgesFromSisa(){
        document.querySelectorAll('#tableRiwayat tbody tr[data-nota-id]').forEach(row => {
            try {
                const sisaText = row.querySelector('.cell-sisa')?.textContent || '0';
                const digits = sisaText.toString().replace(/[^0-9-]/g,'');
                const sisaVal = digits === '' ? 0 : parseInt(digits,10);
                const actionsDiv = row.querySelector('.nota-actions');
                if (!actionsDiv) return;
                // remove existing badge elements
                const oldLunas = actionsDiv.querySelector('.badge-lunas');
                const oldBelum = actionsDiv.querySelector('.badge-belum');
                if (oldLunas) oldLunas.remove();
                if (oldBelum) oldBelum.remove();
                if (sisaVal <= 0) {
                    const el = document.createElement('span'); el.className = 'badge bg-success badge-lunas'; el.textContent = 'Lunas'; actionsDiv.appendChild(el);
                } else {
                    const el = document.createElement('span'); el.className = 'badge-belum'; el.textContent = 'Belum Lunas'; actionsDiv.appendChild(el);
                }
            } catch(e){}
        });
    }

    // run once on load to ensure badges match server values
    refreshBadgesFromSisa();

    const csrfToken = '{{ csrf_token() }}';
    // Payment modal logic for index
    const payFormIdx = document.getElementById('payFormIndex');
    const payNotaIdInput = document.getElementById('payNotaId');
    const payAmountIdx = document.getElementById('payAmountIdx');
    const discountPercentIdx = document.getElementById('discountPercentIdx');
    const paymentTypeIdx = document.getElementById('paymentTypeIdx');
    const methodWrapperIdx = document.getElementById('methodWrapperIdx');
    const paymentMethodIdx = document.getElementById('paymentMethodIdx');
    const cashGivenWrapperIdx = document.getElementById('cashGivenWrapperIdx');
    const cashGivenIdx = document.getElementById('cashGivenIdx');
    const changeIdx = document.getElementById('changeIdx');
    const payAlertIdx = document.getElementById('payAlertIdx');

    document.querySelectorAll('.btn-pay').forEach(btn => {
        btn.addEventListener('click', function () {
            const notaId = this.dataset.id;
            const row = document.querySelector('tr[data-nota-id="' + notaId + '"]');
            // compute sisa = total - terbayar
            // prefer using original total (sum of items) when available
            const totalText = row?.querySelector('.cell-original')?.textContent || row?.querySelector('.cell-total')?.textContent || '0';
            const terbayarText = row?.querySelector('.cell-terbayar')?.textContent || '0';
            const rawTotal = totalText.replace(/\./g,'').replace(/,/g,'');
            const rawTerbayar = terbayarText.replace(/\./g,'').replace(/,/g,'');
            const totalVal = parseFloat(rawTotal) || 0;
            const terbayarVal = parseFloat(rawTerbayar) || 0;
            const sisaVal = Math.max(0, totalVal - terbayarVal);

            payNotaIdInput.value = notaId;
            payAmountIdx.value = sisaVal;
            discountPercentIdx.value = 0;
            paymentTypeIdx.value = 'cash';
            methodWrapperIdx.style.display = 'none';
            if (cashGivenWrapperIdx) cashGivenWrapperIdx.style.display = '';
            if (cashGivenIdx) cashGivenIdx.value = sisaVal;
            if (changeIdx) changeIdx.value = '0';

            // initialize discount display fields (use original total when present)
            const discountedTotalEl = document.getElementById('discountedTotalIdx');
            const discountAmountEl = document.getElementById('discountAmountIdx');
            if (discountedTotalEl) discountedTotalEl.value = new Intl.NumberFormat('id-ID').format(totalVal);
            if (discountAmountEl) discountAmountEl.value = new Intl.NumberFormat('id-ID').format(0);

            const modalEl = document.getElementById('modalPayIndex');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        });
    });

    paymentTypeIdx.addEventListener('change', function(){
        if (this.value === 'transfer') {
            methodWrapperIdx.style.display = '';
            if (cashGivenWrapperIdx) cashGivenWrapperIdx.style.display = 'none';
            if (changeIdx) changeIdx.value = '';
        } else {
            methodWrapperIdx.style.display = 'none';
            if (cashGivenWrapperIdx) cashGivenWrapperIdx.style.display = '';
        }
    });

    // live update discounted total in modal (index)
    const discountedTotalEl = document.getElementById('discountedTotalIdx');
    const discountAmountEl = document.getElementById('discountAmountIdx');

    function updateChangeIdx(){
        if (!cashGivenIdx || !changeIdx) return;
        const notaId = payNotaIdInput.value || null;
        const row = notaId ? document.querySelector('tr[data-nota-id=\"' + notaId + '\"]') : null;
        const totalText = row?.querySelector('.cell-original')?.textContent || row?.querySelector('.cell-total')?.textContent || '0';
        const terbayarText = row?.querySelector('.cell-terbayar')?.textContent || '0';
        const rawTotal = totalText.replace(/\\.|,/g,'');
        const rawTerbayar = terbayarText.replace(/\\.|,/g,'');
        const totalVal = parseFloat(rawTotal) || 0;
        const terbayarVal = parseFloat(rawTerbayar) || 0;
        const percent = parseFloat(discountPercentIdx?.value || 0);
        const discountAmount = Math.round((totalVal * (percent/100)) * 100) / 100;
        const discounted = Math.max(0, Math.round((totalVal - discountAmount) * 100) / 100);
        const remaining = Math.max(0, discounted - terbayarVal);
        const cashVal = parseFloat(cashGivenIdx.value || '0') || 0;
        const applied = Math.min(cashVal || remaining, remaining);
        const change = Math.max(0, cashVal - applied);
        if (!isNaN(applied) && payAmountIdx) payAmountIdx.value = applied;
        if (!isNaN(change)) changeIdx.value = new Intl.NumberFormat('id-ID').format(change);
    }

    if (cashGivenIdx) {
        cashGivenIdx.addEventListener('input', updateChangeIdx);
    }

    if (discountPercentIdx) {
        discountPercentIdx.addEventListener('input', function(){
            const notaId = payNotaIdInput.value || null;
            const row = notaId ? document.querySelector('tr[data-nota-id="' + notaId + '"]') : null;
            // prefer original total (before discounts) when present
            const totalText = row?.querySelector('.cell-original')?.textContent || row?.querySelector('.cell-total')?.textContent || '0';
            const terbayarText = row?.querySelector('.cell-terbayar')?.textContent || '0';
            const rawTotal = totalText.replace(/\\.|,/g,'');
            const rawTerbayar = terbayarText.replace(/\\.|,/g,'');
            const totalVal = parseFloat(rawTotal) || 0;
            const terbayarVal = parseFloat(rawTerbayar) || 0;
            const percent = parseFloat(this.value || 0);
            const discountAmount = Math.round((totalVal * (percent/100)) * 100) / 100;
            const discounted = Math.max(0, Math.round((totalVal - discountAmount) * 100) / 100);
            if (discountedTotalEl) discountedTotalEl.value = new Intl.NumberFormat('id-ID').format(discounted);
            if (discountAmountEl) discountAmountEl.value = new Intl.NumberFormat('id-ID').format(discountAmount);
            const remaining = Math.max(0, discounted - terbayarVal);
            if (paymentTypeIdx.value === 'cash') {
                // jika cash, jumlah bayar diambil dari uang diterima dan kembalian dihitung
                updateChangeIdx();
            } else {
                payAmountIdx.value = remaining;
            }
        });
    }

    // Note: riwayat toggle removed — payments shown inline in columns now.

    // Bulk select / delete for nota
    const selectAllNota = document.getElementById('select_all_nota');
    const notaCheckboxes = document.querySelectorAll('.nota-checkbox');
    if (selectAllNota) {
        selectAllNota.addEventListener('change', function () {
            notaCheckboxes.forEach(cb => {
                cb.checked = selectAllNota.checked;
            });
        });
    }

    // Filter Lunas / Belum Lunas untuk Riwayat Nota
    const statusFilterButtons = document.querySelectorAll('.filter-status-riwayat');
    const riwayatRows = document.querySelectorAll('#tableRiwayat tbody tr');

    function applyStatusFilterRiwayat(status) {
        riwayatRows.forEach(row => {
            const sisaCell = row.querySelector('.cell-sisa');
            if (!sisaCell) {
                row.style.display = '';
                return;
            }
            const raw = sisaCell.textContent.replace(/[^0-9\-]/g, '');
            const sisaVal = parseInt(raw || '0', 10);
            const isLunas = sisaVal <= 0;

            if (status === 'all') {
                row.style.display = '';
            } else if (status === 'lunas') {
                row.style.display = isLunas ? '' : 'none';
            } else if (status === 'belum') {
                row.style.display = !isLunas ? '' : 'none';
            }
        });
    }

    statusFilterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            statusFilterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const status = btn.getAttribute('data-status') || 'all';
            applyStatusFilterRiwayat(status);
        });
    });

    payFormIdx.addEventListener('submit', function (e) {
        e.preventDefault();
        payAlertIdx.classList.add('d-none');
        const notaId = payNotaIdInput.value;
        const url = "{{ url('admin/nota') }}/" + notaId + "/pay";

        const fd = new FormData(payFormIdx);
        fd.append('_token', csrfToken);
        // kirim juga total uang cash yang diterima (jika pembayaran cash)
        try {
            if (paymentTypeIdx.value === 'cash' && cashGivenIdx) {
                const cashVal = parseFloat(cashGivenIdx.value || '0');
                if (!isNaN(cashVal) && cashVal > 0) {
                    if (fd.set) fd.set('cash_given', cashVal); else fd.append('cash_given', cashVal);
                }
            }
        } catch (e2) {}

        fetch(url, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        }).then(async res => {
            if (!res.ok) {
                const txt = await res.text(); throw new Error(txt || 'Terjadi kesalahan');
            }
            return res.json();
        }).then(json => {
            const nota = json.nota;
            const payment = json.payment || null;
            const discountAmount = json.discount_amount || 0;

            const row = document.querySelector('tr[data-nota-id="' + nota.id + '"]');
            if (row) {
                const formatter = new Intl.NumberFormat('id-ID');
                row.querySelector('.cell-total').textContent = formatter.format(nota.total || 0);

                // update terbayar cell (sum of payments)
                row.querySelector('.cell-terbayar').textContent = formatter.format(nota.payments ? nota.payments.reduce((s, p) => s + (p.amount || 0), 0) : 0);
                // Toggle badges: show Lunas if sisa <= 0, else show Belum Lunas
                const badgeBelum = row.querySelector('.badge-belum');
                const badgeLunas = row.querySelector('.badge-lunas');
                const actionsDiv = row.querySelector('.nota-actions');
                if (parseFloat(nota.sisa) <= 0) {
                    if (badgeBelum) badgeBelum.remove();
                    if (badgeLunas) {
                        badgeLunas.style.display = 'inline-block';
                    } else if (actionsDiv) {
                        const el = document.createElement('span'); el.className = 'badge bg-success badge-lunas'; el.textContent = 'Lunas'; actionsDiv.appendChild(el);
                    }
                } else {
                    if (badgeLunas) badgeLunas.style.display = 'none';
                    if (badgeBelum) {
                        badgeBelum.style.display = 'inline-block';
                    } else if (actionsDiv) {
                        const el = document.createElement('span'); el.className = 'badge-belum'; el.textContent = 'Belum Lunas'; actionsDiv.appendChild(el);
                    }
                }
            }

            // Update pembayaran summary and terbayar/kekurangan in the nota row (payments are now summarized in columns)
            if (payment) {
                const row = document.querySelector('tr[data-nota-id="' + nota.id + '"]');
                if (row) {
                    // Update Terbayar and Kekurangan cells
                    const formatter = new Intl.NumberFormat('id-ID');
                    row.querySelector('.cell-terbayar').textContent = formatter.format(nota.payments ? nota.payments.reduce((s, p) => s + (p.amount || 0), 0) : 0);
                    // Update pembayaran badges: cash & transfer
                    try {
                        const cash = nota.payments ? nota.payments.filter(p=>p.type==='cash').reduce((s,p)=>s+(p.amount||0),0) : 0;
                        const transfer = nota.payments ? nota.payments.filter(p=>p.type==='transfer').reduce((s,p)=>s+(p.amount||0),0) : 0;
                        const pembCell = row.querySelector('.pembayaran-cell');
                        if (pembCell) {
                            let html = '';
                            if (cash > 0) html += '<span class="badge bg-success">Cash: '+formatter.format(cash)+'</span> ';
                            if (transfer > 0) html += '<span class="badge bg-info text-dark">Transfer: '+formatter.format(transfer)+'</span>';
                            if (!html) html = '<span class="text-muted">-</span>';
                            pembCell.innerHTML = html;
                        }
                        // update sisa and diskon display
                        try {
                            const sisaCell = row.querySelector('.cell-sisa');
                            const diskonCell = row.querySelector('.cell-diskon');
                            if (sisaCell) sisaCell.textContent = formatter.format(nota.sisa || 0);
                            if (diskonCell) diskonCell.textContent = formatter.format(nota.payments ? nota.payments.reduce((s,p) => s + (p.discount_amount || 0), 0) : 0);
                        } catch(e) {}
                    } catch(e){}
                }
            }

            const modalEl = document.getElementById('modalPayIndex');
            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modal.hide();

            if (window.Swal) {
                let msg = json.message || 'Pembayaran berhasil.';
                if (discountAmount && discountAmount > 0) msg += ' (Diskon diterapkan: ' + new Intl.NumberFormat('id-ID').format(discountAmount) + '.)';
                Swal.fire({ icon: 'success', title: 'Berhasil', text: msg });
            } else {
                alert(json.message || 'Pembayaran berhasil.');
            }
        }).catch(async err => {
            let msg = err.message || 'Gagal memproses pembayaran.';
            try {
                const t = await err.text(); if (t) msg = t;
            } catch(e){}
            payAlertIdx.classList.remove('d-none');
            payAlertIdx.innerText = msg;
            if (window.Swal) Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
        });
    });
    // Removed explicit "Tandai Lunas" button handler — badges now reflect lunas/belum states.
});
</script>
@endsection
