@extends('layouts.app')

@section('content')
<style>
    /* 🎨 Semua style dibungkus agar hanya berlaku di halaman ini */
    .macos-nota {
        font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        background: linear-gradient(180deg, #f5f6fa, #eef1f4);
        color: #2d3436;
        padding-bottom: 40px;
    }

    .macos-nota h3,
    .macos-nota h5 {
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    .macos-nota .container {
        max-width: 880px;
    }

    .macos-nota-header {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .macos-nota-header-title h3 {
        margin-bottom: .2rem;
    }

    .macos-nota-header-eyebrow {
        font-size: 0.8rem;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: #9ca3af;
        margin-bottom: 0.1rem;
    }

    .macos-nota-header-subtitle {
        font-size: 0.9rem;
        color: #6b7280;
        margin: 0;
    }

    .macos-nota-status {
        text-align: right;
    }

    .macos-nota-pill {
        display: inline-flex;
        align-items: center;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.06em;
        text-transform: uppercase;
    }

    .macos-nota-pill-success {
        background: rgba(16, 185, 129, 0.12);
        color: #047857;
    }

    .macos-nota-pill-warning {
        background: rgba(245, 158, 11, 0.12);
        color: #92400e;
    }

    .macos-nota-id {
        font-size: 0.88rem;
        color: #6b7280;
        margin-top: 4px;
    }

    /* 💎 Kartu ala macOS (Glassmorphism) */
    .macos-nota .card {
        border: none;
        border-radius: 18px;
        backdrop-filter: blur(12px);
        background: rgba(255, 255, 255, 0.75);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .macos-nota .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
    }

    .macos-nota .fw-bold.text-primary {
        color: #007aff !important;
    }

    /* 🩵 Table ala macOS */
    .macos-nota table.table {
        border-radius: 12px;
        overflow: hidden;
    }

    .macos-nota table thead {
        background-color: #f9fafc;
    }

    .macos-nota table th {
        font-weight: 600;
        color: #007aff;
        border-bottom: 2px solid #e0e6ef !important;
        font-size: 14px;
    }

    .macos-nota table td {
        vertical-align: middle !important;
        border-color: #f0f2f5 !important;
        font-size: 14px;
    }

    /* 💰 Total Section - jadi tabel */
    .macos-nota .total-table {
        width: 100%;
        margin-top: 25px;
        border-collapse: collapse;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }

    .macos-nota .total-table th,
    .macos-nota .total-table td {
        padding: 12px 16px;
        border: 1px solid #e6e9ef;
        font-size: 15px;
    }

    .macos-nota .total-table th {
        background-color: #f9fafc;
        color: #007aff;
        font-weight: 600;
        text-align: left;
    }

    .macos-nota .total-table td {
        text-align: right;
        color: #333;
    }

    .macos-nota .total-table tr:last-child td {
        font-weight: 700;
        color: #007aff;
    }

    /* 🔘 Tombol ala macOS Premium */
    .macos-nota .btn {
        border-radius: 12px;
        font-weight: 600;
        letter-spacing: -0.2px;
        padding: 10px 20px;
        transition: all 0.25s ease;
        border: none;
        color: #fff;
        box-shadow: 0 4px 14px rgba(0, 122, 255, 0.35);
    }

    /* Semua tombol utama disamakan biru premium */
    .macos-nota .btn-primary,
    .macos-nota .btn-secondary,
    .macos-nota .btn-danger {
        background-color: #007aff;
    }

    .macos-nota .btn-primary:hover,
    .macos-nota .btn-secondary:hover,
    .macos-nota .btn-danger:hover {
        background-color: #0066d6;
        box-shadow: 0 6px 18px rgba(0, 122, 255, 0.45);
        transform: translateY(-2px);
    }

    /* Tombol outline (kembali) */
    .macos-nota .btn-outline-dark {
        border: 1.5px solid #d0d3da;
        color: #333;
        background-color: transparent;
        box-shadow: none;
    }

    .macos-nota .btn-outline-dark:hover {
        background-color: #f1f2f6;
        color: #000;
    }

    .macos-nota .text-center {
        margin-top: 1.8rem;
    }

    .macos-nota a.btn {
        transform: translateY(0);
    }

    .macos-nota a.btn:hover {
        transform: translateY(-2px);
    }

    /* ✨ Responsif */
    @media (max-width: 768px) {
        .macos-nota .container {
            padding: 0 15px;
        }

        .macos-nota table th,
        .macos-nota table td,
        .macos-nota .total-table th,
        .macos-nota .total-table td {
            font-size: 13px;
        }

        .macos-nota .btn {
            padding: 8px 14px;
            font-size: 14px;
        }
    }
</style>

<div class="macos-nota">
    <div class="container mt-4">
        @php
            $lastPayment = $nota->payments ? $nota->payments->sortByDesc('created_at')->first() : null;
        @endphp
        <div class="macos-nota-header">
            <div class="macos-nota-header-title">
                <div class="macos-nota-header-eyebrow">Nota Digital</div>
                <h3 class="fw-bold text-primary">Detail Nota Laundry</h3>
                <p class="macos-nota-header-subtitle">Ringkasan lengkap transaksi dan status pembayaran pelanggan.</p>
            </div>
            <div class="macos-nota-status">
                <span class="macos-nota-pill {{ $nota->sisa <= 0 ? 'macos-nota-pill-success' : 'macos-nota-pill-warning' }}">
                    {{ $nota->sisa <= 0 ? 'Lunas' : 'Belum Lunas' }}
                </span>
                <div class="macos-nota-id">Nota #{{ $nota->id }}</div>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-secondary">Informasi Pelanggan</h5>
                {{-- Tambahan Nomor Nota --}}
                <p><strong>Nomor Nota:</strong> {{ $nota->id }}</p>
                <p><strong>Nama:</strong> {{ $nota->customer_name }}</p>
                <p><strong>Alamat:</strong> {{ $nota->customer_address ?? '-' }}</p>
                <p><strong>Tanggal Keluar:</strong> 
                    {{ $nota->tgl_keluar ? \Carbon\Carbon::parse($nota->tgl_keluar)->format('d/m/Y') : '-' }}
                </p>
                <p><strong>Dibuat Oleh:</strong> {{ $nota->user->name ?? '-' }}</p>
                <p>
                    <strong>Tanggal & Jam Pembayaran Terakhir:</strong>
                    @if($lastPayment)
                        {{ \Carbon\Carbon::parse($lastPayment->created_at)->format('d/m/Y H:i') }}
                    @else
                        - (belum ada pembayaran)
                    @endif
                </p>
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="fw-bold mb-3 text-secondary">Daftar Item</h5>
                <table class="table align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Jenis Pakaian</th>
                            <th>Harga (Rp)</th>
                            <th>Jumlah</th>
                            <th>Subtotal (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($nota->items as $i)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $i->item->name ?? '-' }}</td>
                                <td>{{ number_format($i->price, 0, ',', '.') }}</td>
                                <td>{{ $i->quantity }}</td>
                                <td>{{ number_format($i->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted">Belum ada item pada nota ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @php
                    $kembalian = $nota->payments
                        ? $nota->payments->reduce(function ($carry, $p) {
                            $cash = (float) ($p->cash_given ?? 0);
                            $applied = (float) ($p->amount ?? 0);
                            return $carry + max(0, $cash - $applied);
                        }, 0)
                        : 0;
                @endphp

                <table class="total-table mt-4">
                    <tr>
                        <th>Total Banyak Pakaian</th>
                        <td>{{ $nota->items->sum('quantity') }}</td>
                    </tr>
                    <tr>
                        <th>Total Awal (Rp)</th>
                        <td id="originalTotal">{{ number_format($nota->items ? $nota->items->sum('subtotal') : $nota->total, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Total (Rp)</th>
                        <td id="totalAmount">{{ number_format($nota->total, 0, ',', '.') }}</td>
                    </tr>
                        <tr>
                            <th>Diskon (Rp)</th>
                            <td id="diskonAmount">{{ number_format($nota->payments ? $nota->payments->sum('discount_amount') : 0, 0, ',', '.') }}</td>
                        </tr>
                      @if($nota->sisa > 0)
                      <tr>
                          <th>Sisa Pembayaran (Rp)</th>
                          <td id="sisaAmount">{{ number_format($nota->sisa, 0, ',', '.') }}</td>
                      </tr>
                      @endif
                      <tr>
                          <th>Total Uang Kembali (Rp)</th>
                          <td id="kembalianAmount">{{ number_format($kembalian, 0, ',', '.') }}</td>
                      </tr>
                </table>
            </div>
        </div>

        <div class="text-center">
            <!-- Tombol Bayar Sekarang & Edit hanya muncul jika belum lunas -->
            @if($nota->sisa > 0)
                <button type="button" id="btnBayarNow" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#bayarModal">Bayar Sekarang</button>
                <a href="{{ route('admin.nota.edit', $nota->id) }}" id="btnEditNota" class="btn btn-primary me-2">Edit Nota</a>
            @endif

            <a href="{{ route('admin.nota.print_direct', $nota->id) }}" class="btn btn-primary" target="_blank">Print Langsung</a>
            <a href="{{ route('admin.nota.index') }}" class="btn btn-outline-dark">Kembali</a>
        </div>
    </div>
</div>
@endsection

@if($nota->sisa > 0)
<div class="modal fade" id="bayarModal" tabindex="-1" aria-labelledby="bayarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bayarModalLabel">Pembayaran Nota #{{ $nota->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="payForm">
                <div class="modal-body">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tipe Pembayaran</label>
                        <select name="type" id="paymentType" class="form-select" required>
                            <option value="cash">CASH</option>
                            <option value="transfer">TRANSFER</option>
                        </select>
                    </div>

                    <div class="mb-3" id="methodWrapper" style="display:none;">
                        <label class="form-label">Metode Transfer</label>
                        <select name="method" id="paymentMethod" class="form-select">
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

                    @if($nota->sisa > 0)
                        <div class="mb-3">
                            <label class="form-label">Sisa Pembayaran Saat Ini (Rp)</label>
                            <input type="text" id="currentRemaining" class="form-control" value="{{ number_format($nota->sisa,0,',','.') }}" readonly>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Diskon (%) <small class="text-muted">opsional</small></label>
                        <input type="number" step="0.01" min="0" max="100" name="discount_percent" id="discountPercent" class="form-control" value="0">
                        <div class="form-text">Masukkan diskon yang diberikan kepada pelanggan (persentase dari total nota).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Bayar (Rp)</label>
                        <input type="text" name="amount" id="payAmount" class="form-control" value="{{ number_format($nota->sisa,0,',','.') }}" required>
                        <div class="form-text">Masukkan jumlah uang yang dibayarkan. Untuk lunas, biarkan sama dengan sisa setelah diskon.</div>
                    </div>

                    <div class="mb-3" id="cashGivenWrapperShow">
                        <label class="form-label">Uang Diterima (Cash) (Rp)</label>
                        <input type="text" id="cashGiven" class="form-control">
                        <div class="form-text">Isi jika pembayaran tunai, untuk menghitung kembalian.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kembalian (Rp)</label>
                        <input type="text" id="changeAmount" class="form-control" readonly>
                    </div>

                    <div id="payAlert" class="alert alert-danger d-none" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Proses Bayar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function(){
        const paymentType = document.getElementById('paymentType');
        const methodWrapper = document.getElementById('methodWrapper');
        const payForm = document.getElementById('payForm');
        const payAlert = document.getElementById('payAlert');
        const btnBayarNow = document.getElementById('btnBayarNow');
        const btnEditNota = document.getElementById('btnEditNota');
        const payAmount = document.getElementById('payAmount');
        const discountPercent = document.getElementById('discountPercent');
        const discountedTotal = null;
        const discountAmount = document.getElementById('discountAmount');
        const paymentsTable = document.getElementById('paymentsTable');
        const cashGivenWrapper = document.getElementById('cashGivenWrapperShow');
        const cashGiven = document.getElementById('cashGiven');
        const changeAmount = document.getElementById('changeAmount');

        paymentType.addEventListener('change', function(){
            if (this.value === 'transfer') {
                methodWrapper.style.display = '';
                if (changeAmount) changeAmount.value = '';
            } else {
                methodWrapper.style.display = 'none';
            }
        });

        // Helpers to parse/format rupiah robustly
        function parseCurrency(elOrStr){
            const s = (typeof elOrStr === 'string') ? elOrStr : (elOrStr ? (elOrStr.innerText || elOrStr.value || '') : '');
            // remove anything that's not a digit or minus sign
            const digits = s.toString().replace(/[^0-9-]/g,'');
            return digits === '' ? 0 : parseInt(digits, 10);
        }
        function formatCurrency(n){
            try{
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(Number(n || 0)).replace('IDR', '');
            } catch(e){ return n; }
        }

        function updateChangeShow(){
            if (!cashGiven || !changeAmount) return;

            const remainingEl = document.getElementById('currentRemaining') || document.getElementById('sisaAmount');
            const baseRemaining = remainingEl ? parseCurrency(remainingEl) : 0;

            const percentVal = parseFloat(discountPercent?.value || 0) || 0;
            const dAmount = Math.round(baseRemaining * (percentVal / 100));
            const discountedRemaining = Math.max(0, Math.round(baseRemaining - dAmount));

            const tender = parseCurrency(cashGiven) || 0;
            // gunakan nilai "Jumlah Bayar" yang sedang tampil,
            // bukan nilai uang cash yang sedang diketik
            const payValue = payAmount ? parseCurrency(payAmount) : discountedRemaining;
            const applied = Math.min(payValue, discountedRemaining || 0);
            const kembali = Math.max(0, tender - applied);

            if (discountAmount) discountAmount.value = formatCurrency(dAmount);
            // jangan ubah payAmount di sini, agar tidak ikut "mengetik" saat Uang Diterima diisi
            changeAmount.value = tender > 0 ? formatCurrency(kembali) : '';
        }

        // Initialize discounted display and live calculation
        try {
            // ensure default shown values
            const remainingEl = document.getElementById('currentRemaining') || document.getElementById('sisaAmount');
            const remainingBase = remainingEl ? parseCurrency(remainingEl) : 0;
            if (discountAmount) discountAmount.value = formatCurrency(0);
            if (payAmount) payAmount.value = formatCurrency(remainingBase);

            // on input percent -> update discounted fields
            if (discountPercent) {
                discountPercent.addEventListener('input', function(){
                    const remainingEl = document.getElementById('currentRemaining') || document.getElementById('sisaAmount');
                    const base = remainingEl ? parseCurrency(remainingEl) : 0;
                    const percent = parseFloat(this.value || 0) || 0;
                    const dAmount = Math.round((base * (percent/100)));
                    const dTotal = Math.max(0, Math.round(base - dAmount));
                    if (discountAmount) discountAmount.value = formatCurrency(dAmount);
                    // Jumlah Bayar selalu mengikuti total setelah diskon
                    if (payAmount) {
                        payAmount.value = formatCurrency(dTotal);
                    }
                    // Jika cash, cukup hitung ulang kembalian saja
                    if (paymentType.value === 'cash') {
                        updateChangeShow();
                    }
                });
            }

            // When modal opens, compute based on current percent (in case percent was prefilled)
            const modalEl = document.getElementById('bayarModal');
            if (modalEl) {
                modalEl.addEventListener('show.bs.modal', function (){
                    try {
                        const remainingEl = document.getElementById('currentRemaining') || document.getElementById('sisaAmount');
                        const base = remainingEl ? parseCurrency(remainingEl) : 0;
                        const percentVal = parseFloat(discountPercent.value || 0) || 0;
                        const dAmount = Math.round((base * (percentVal/100)));
                        const dTotal = Math.max(0, Math.round(base - dAmount));
                        if (discountAmount) discountAmount.value = formatCurrency(dAmount);
                        if (payAmount) {
                            payAmount.value = formatCurrency(dTotal);
                        }
                        if (paymentType.value === 'cash') {
                            updateChangeShow();
                        }
                    }catch(e){}
                });
            }
        } catch(e) {}

        if (cashGiven) {
            cashGiven.addEventListener('input', updateChangeShow);
        }

        payForm.addEventListener('submit', function(e){
            e.preventDefault();
            payAlert.classList.add('d-none');
            const url = "{{ route('admin.nota.pay', $nota->id) }}";

            const fd = new FormData(payForm);
            fd.append('_token', '{{ csrf_token() }}');
            if (discountPercent) {
                fd.append('discount_percent', discountPercent.value || 0);
            }
            // kirim juga total uang cash yang diterima (jika pembayaran cash)
            try {
                if (paymentType.value === 'cash' && cashGiven) {
                    const cashNumeric = parseCurrency(cashGiven);
                    if (!isNaN(cashNumeric) && cashNumeric > 0) {
                        if (fd.set) fd.set('cash_given', cashNumeric); else fd.append('cash_given', cashNumeric);
                    }
                }
            } catch(e) {}
            // Ensure the amount sent is numeric (unformatted)
            try {
                if (payAmount) {
                    const numeric = parseCurrency(payAmount);
                    if (fd.set) fd.set('amount', numeric); else fd.append('amount', numeric);
                }
            } catch(e) {}

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: fd
            }).then(async res => {
                if (!res.ok) {
                    let msg = await res.text();
                    throw new Error(msg || 'Terjadi kesalahan');
                }
                return res.json();
            }).then(json => {
                const nota = json.nota;
                const payment = json.payment || null;
                const toIdFormat = (v) => {
                    try { return Number(v).toLocaleString('id-ID'); } catch(e) { return v; }
                }

                // Update numbers in the page (tanpa uang muka)
                document.getElementById('totalAmount').innerText = toIdFormat(nota.total);
                document.getElementById('sisaAmount').innerText = toIdFormat(nota.sisa);
                // update diskon amount display
                try {
                    document.getElementById('diskonAmount').innerText = toIdFormat(nota.payments ? nota.payments.reduce((s,p) => s + (p.discount_amount || 0), 0) : 0);
                } catch(e) {}

                // Sembunyikan tombol Bayar Sekarang & Edit Nota jika sudah lunas
                if (parseFloat(nota.sisa) <= 0) {
                    if (btnBayarNow) {
                        btnBayarNow.style.display = 'none';
                        btnBayarNow.remove(); // Hapus dari DOM
                    }
                    if (btnEditNota) {
                        btnEditNota.style.display = 'none';
                        btnEditNota.remove(); // Hapus dari DOM
                    }
                }

                // Add new payment row to history table if present
                try {
                    if (payment && paymentsTable) {
                        const tbody = paymentsTable.querySelector('tbody') || null;
                        if (tbody) {
                            const tr = document.createElement('tr');
                            const createdAt = new Date(payment.created_at || Date.now()).toLocaleString('id-ID');
                            const method = payment.method || '-';
                            const type = payment.type ? (payment.type.charAt(0).toUpperCase() + payment.type.slice(1)) : '-';
                            const amountText = toIdFormat(payment.amount || 0);
                            const userName = (payment.user && payment.user.name) ? payment.user.name : '{{ auth()->user()->name ?? "-" }}';

                            tr.innerHTML = `<td>${createdAt}</td><td>${amountText}</td><td>${type}</td><td>${method}</td><td>${userName}</td>`;
                            // insert as first row
                            tbody.insertBefore(tr, tbody.firstChild);
                        }
                    }
                } catch(e) { console.warn('Failed to append payment row', e); }

                const modalEl = document.getElementById('bayarModal');
                const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modal.hide();

                // Success notification + auto reload
                if (window.Swal) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: json.message || 'Pembayaran berhasil diproses.'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    alert(json.message || 'Pembayaran berhasil.');
                    window.location.reload();
                }

            }).catch(async err => {
                let msg = 'Gagal memproses pembayaran.';
                try {
                    // try to parse JSON error
                    const text = await err.message || err;
                    msg = text;
                } catch(e){}

                payAlert.classList.remove('d-none');
                payAlert.innerText = msg;

                if (window.Swal) {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: msg });
                }
            });
        });
    })();
</script>
@endif
