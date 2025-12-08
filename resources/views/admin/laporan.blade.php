@extends('layouts.app')

@section('content')
<style>
    /* Reset + system font */
    :root{
        --bg: #f3f6fb;
        --panel: rgba(255,255,255,0.85);
        --muted: #6b7280;
        --glass-border: rgba(0,0,0,0.06);
        --accent: #0f172a;
        /* shadow biru lembut (bukan hitam pekat) */
        --card-shadow: 0 18px 40px rgba(37,99,235,0.30);
        --traffic-size: 12px;
    }
    html,body,#app, .container-full {
        height: 100%;
        background: linear-gradient(180deg, #ffffff 0%, var(--bg) 100%);
        font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        color: var(--accent);
    }

    /* Fullscreen macOS-like window */
    .mac-window{
        width: 100%;
        min-height: calc(100vh - 80px);
        padding: 24px 22px 30px 22px;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        gap: 20px;
        background: transparent;
    }

    /* Titlebar */
    .titlebar{
        -webkit-app-region: drag;
        display:flex;
        align-items:center;
        gap:16px;
        padding: 10px 14px;
        border-radius: 16px;
        background:
            radial-gradient(circle at 0% 0%, rgba(255,255,255,0.9), rgba(239,246,255,0.9)),
            linear-gradient(180deg, rgba(255,255,255,0.85), rgba(248,250,252,0.95));
        box-shadow: 0 20px 50px rgba(15,23,42,0.18);
        border: 1px solid rgba(226,232,240,0.9);
    }

    .traffic {
        display:flex;
        gap:8px;
        align-items:center;
        padding-left:6px;
    }
    .traffic .dot{
        width: var(--traffic-size);
        height: var(--traffic-size);
        border-radius: 50%;
        box-shadow: inset 0 -1px 0 rgba(0,0,0,0.06);
        border: 1px solid rgba(0,0,0,0.06);
    }
    .dot.close{ background: #ff5f57; }
    .dot.min{ background: #ffbd2e; }
    .dot.max{ background: #28c840; }

    .title-text{
        display:flex;
        flex-direction:column;
        line-height:1;
    }
    .title-text h3{ margin:0; font-size:26px; font-weight:800; letter-spacing:.02em; text-transform:uppercase; }
    .title-text p{ margin:2px 0 0; font-size:13px; color:var(--muted); }

    /* Toolbar */
    .toolbar{
        display:flex;
        gap:12px;
        align-items:center;
        justify-content:space-between;
        flex-wrap: wrap;
    }
    .toolbar-left{ display:flex; gap:10px; align-items:center; flex-wrap: wrap; }
    .search-box{ display:flex; gap:8px; align-items:center; background:var(--panel); padding:7px 12px; border-radius:999px; border:1px solid var(--glass-border); box-shadow: 0 4px 14px rgba(15,23,42,0.06); }
    .search-box input{ border:none; outline:none; width:280px; background:transparent; font-size:14px; }

    .filters{ display:flex; gap:8px; align-items:center; flex-wrap: wrap; }
    .btn-filter{ padding:7px 12px; border-radius:999px; border:1px solid rgba(148,163,184,0.4); background:rgba(255,255,255,0.7); cursor:pointer; font-weight:600; font-size:12px; transition:all 0.2s; }
    .btn-filter.active{ background: linear-gradient(135deg,#e0ecff,#ffffff); box-shadow: 0 10px 24px rgba(37,99,235,0.28); border-color: rgba(59,130,246,0.6); }

    /* Summary cards */
    .stats{
        display:grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap:16px;
        align-items:stretch;
    }

    .card{
        padding:16px;
        border-radius:20px;
        border:1px solid rgba(226,232,240,0.9);
        background: linear-gradient(145deg, #ffffff, #f3f4f6);
        box-shadow: 0 14px 40px rgba(15,23,42,0.12);
        color:#111827;
        transition: all 0.25s ease;
        transform: translateY(0);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
    }
    .card::before{
        content:"";
        position:absolute;
        inset:-40%;
        opacity:0.16;
        background: radial-gradient(circle at 0 0, rgba(248,250,252,0.45), transparent 60%);
        pointer-events:none;
    }
    /* Make all .card elements consistent size and layout */
    .card {
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        box-sizing: border-box;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(15,23,42,0.18);
    }
    .card h6{
        margin:0;
        font-weight:700;
        font-size:13px;
        text-transform:uppercase;
        letter-spacing:.08em;
        color:#4b5563;
    }
    .card h4{
        margin:10px 0 4px 0;
        font-size:24px;
        font-weight:800;
        color:#0f172a;
    }
    .card p.small{
        margin:0;
        font-size:13px;
        font-weight:700;
        color:#6b7280;
    }

    /* 🌈 Warna khusus untuk tiap kartu pendapatan */
    .card.harian {
        background: linear-gradient(135deg, #0f172a, #1d4ed8);
    }
    .card.mingguan {
        background: linear-gradient(135deg, #0f172a, #1d4ed8);
    }
    .card.bulanan {
        background: linear-gradient(135deg, #0f172a, #1d4ed8);
    }
    .card.tahunan {
        background: linear-gradient(135deg, #0f172a, #1d4ed8);
    }
  
    /* Tambahan styling untuk card sisa total dan total pembayaran */
    .card.sisa-total {
        background: linear-gradient(135deg, #7f1d1d, #ef4444);
    }
    .card.cash-total {
        background: linear-gradient(135deg,#022c22,#10b981);
    }
    .card.transfer-total {
        background: linear-gradient(135deg,#1e1b4b,#8b5cf6);
    }

    /* Buat semua kartu di dalam .stats sama ukuran dan rapi */
    .stats .card {
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-start;
        padding: 18px;
        box-sizing: border-box;
    }
    .stats .card h6 { font-size: 13px; margin-bottom: 6px; }
    .stats .card h4 { margin: 10px 0 4px 0; font-size: 24px; }
    .stats .card p.small { margin: 0; font-size: 13px; font-weight:700; }

    /* Table area - biarkan tinggi mengikuti konten, scroll pakai browser */
    .content-panel{ flex:none; overflow:visible; padding:10px; border-radius:18px; background: linear-gradient(180deg, rgba(255,255,255,0.85), rgba(255,255,255,0.95)); border:1px solid rgba(226,232,240,0.9); box-shadow: 0 18px 45px rgba(15,23,42,0.14); }
    table{ width:100%; border-collapse:collapse; min-width:1080px; }
    thead th{ text-align:center; padding:12px; font-weight:700; background:transparent; border-bottom:1px solid rgba(226,232,240,0.9); font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#6b7280; }
    tbody td{ padding:12px; vertical-align:middle; }

    .badge-lunas{ background:#e6ffef; color:#04683a; padding:6px 10px; border-radius:999px; font-weight:700; font-size:11px; }
    .badge-belum{ background:#fff4e6; color:#7a4b0b; padding:6px 10px; border-radius:999px; font-weight:700; font-size:11px; }
    .badge-cash{ background:#e6ffef; color:#04683a; padding:4px 8px; border-radius:999px; font-weight:700; display:inline-block; font-size:11px; }
    .badge-transfer{ background:#e6f0ff; color:#0b3d91; padding:4px 8px; border-radius:999px; font-weight:700; display:inline-block; font-size:11px; }

    /* Responsive */
    @media(max-width:1100px){
        .stats{ grid-template-columns: repeat(2,1fr); }
        .search-box input{ width:160px; }
        table{ min-width:760px; }
    }

    /* Override akhir: haluskan shadow kartu */
    .card {
        box-shadow: 0 14px 40px rgba(15,23,42,0.12) !important;
    }
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(15,23,42,0.18) !important;
    }

    /* Kartu pendapatan + ringkasan mingguan dengan nuansa biru lembut */
    .card.harian,
    .card.mingguan,
    .card.bulanan,
    .card.tahunan,
    .card.weekly-card {
        background: linear-gradient(135deg, #eff6ff, #dbeafe) !important;
        color:#0f172a !important;
    }

    /* Warna khusus: total sisa merah lembut, cash hijau lembut, transfer ungu lembut */
    .card.sisa-total {
        background: linear-gradient(135deg, #fef2f2, #fee2e2) !important;
        color:#991b1b !important;
    }
    .card.cash-total {
        background: linear-gradient(135deg,#ecfdf5,#bbf7d0) !important;
        color:#064e3b !important;
    }
    .card.transfer-total {
        background: linear-gradient(135deg,#eef2ff,#e0e7ff) !important;
        color:#312e81 !important;
    }
</style>

<div class="mac-window container-fluid">
    <div class="titlebar">
        <div class="traffic" aria-hidden>
            <div class="dot close"></div>
            <div class="dot min"></div>
            <div class="dot max"></div>
        </div>
        <div class="title-text">
            <h3>Laporan Keuangan — Deva Laundry</h3>
            <p>Ringkasan transaksi & nota harian, mingguan, bulanan, tahunan</p>
        </div>
    </div>

    <div class="toolbar">
        <div class="toolbar-left">
            <div class="search-box">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input id="searchInput" placeholder="Cari nama pelanggan, tanggal, atau total..." />
                <button id="clearSearch" title="Bersihkan" class="btn-filter" style="padding:6px 8px">✕</button>
            </div>
            <div class="filters">
                <button class="btn-filter active" data-filter="all">Semua</button>
                <button class="btn-filter" data-filter="lunas">Lunas</button>
                <button class="btn-filter" data-filter="belum">Belum Lunas</button>
                <!-- Payment type filters -->
                <button class="btn-filter active" data-pay-filter="all">Semua Pembayaran</button>
                <button class="btn-filter" data-pay-filter="cash">Hanya Cash</button>
                <button class="btn-filter" data-pay-filter="transfer">Hanya Transfer</button>
            </div>
        </div>

        <div style="margin-left:12px; display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
            <div style="display:flex; gap:6px; align-items:center;">
                <label for="start_date">Exp Dari:</label>
                <input type="date" id="start_date" style="padding:6px; border-radius:8px; border:1px solid rgba(0,0,0,0.1)">
                <label for="end_date">Exp Sampai:</label>
                <input type="date" id="end_date" style="padding:6px; border-radius:8px; border:1px solid rgba(0,0,0,0.1)">
            </div>
            <button class="btn-filter" id="exportExcelBtn">Export Excel</button>
            <button class="btn-filter" id="exportToday">Harian</button>
            <button class="btn-filter" id="exportWeek">Mingguan</button>
            <button class="btn-filter" id="exportMonth">Bulanan</button>
            <button class="btn-filter" id="exportYear">Tahunan</button>
            <button class="btn-filter" id="refreshBtn">Refresh</button>
        </div>
    </div>

    <div style="display: flex; align-items: center; gap: 12px; margin-top: -10px;">
        <label for="monthFilter" style="font-weight: 600; font-size: 14px;">Filter Bulan:</label>
        <select id="monthFilter" class="btn-filter" style="padding: 8px 12px; border-radius: 10px; cursor: pointer;">
            <option value="all">Semua Bulan</option>
            <option value="1">Januari</option>
            <option value="2">Februari</option>
            <option value="3">Maret</option>
            <option value="4">April</option>
            <option value="5">Mei</option>
            <option value="6">Juni</option>
            <option value="7">Juli</option>
            <option value="8">Agustus</option>
            <option value="9">September</option>
            <option value="10">Oktober</option>
            <option value="11">November</option>
            <option value="12">Desember</option>
        </select>

        <label for="yearFilter" style="font-weight: 600; font-size: 14px;">Filter Tahun:</label>
        <select id="yearFilter" class="btn-filter" style="padding: 8px 12px; border-radius: 10px; cursor: pointer;">
            <option value="all">Semua Tahun</option>
            </select>
    </div>

    <div class="stats">
        <div class="card harian">
            <h6>Pendapatan Hari Ini</h6>
            <h4 id="cardHarian">Rp {{ number_format($harian,0,',','.') }}</h4>
            <p class="small">Nota: <span id="countHarian">0</span></p>
        </div>
        <div class="card mingguan">
            <h6>Pendapatan Minggu Ini</h6>
            <h4 id="cardMingguan">Rp {{ number_format($mingguan,0,',','.') }}</h4>
            <p class="small">Nota: <span id="countMingguan">0</span></p>
        </div>
        <div class="card bulanan">
            <h6>Pendapatan Bulan Ini</h6>
            <h4 id="cardBulanan">Rp {{ number_format($bulanan,0,',','.') }}</h4>
            <p class="small">Nota: <span id="countBulanan">0</span></p>
        </div>
        <div class="card tahunan">
            <h6>Pendapatan Tahun Ini</h6>
            <h4 id="cardTahunan">Rp {{ number_format($tahunan,0,',','.') }}</h4>
            <p class="small">Nota: <span id="countTahunan">0</span></p>
        </div>

        <!-- ➕ CARD BARU TOTAL SISA (ditambahkan tanpa mengubah kode lain) -->
        <div class="card sisa-total">
            <h6>Total Sisa Belum Dibayar</h6>
            <h4 id="totalSisaCard">Rp 0</h4>
            <p class="small">Jumlah Nota Belum Lunas: <span id="countBelumLunas">0</span></p>
        </div>

        <!-- Total pembayaran menurut metode (Cash / Transfer) -->
        <div class="card cash-total">
            <h6>Total Pembayaran Cash</h6>
            <h4 id="totalCash">Rp {{ number_format($totalCash ?? 0,0,',','.') }}</h4>
            <p class="small">Nota: <span id="countCashNotes">0</span></p>
        </div>
        <div class="card transfer-total">
            <h6>Total Pembayaran Transfer</h6>
            <h4 id="totalTransfer">Rp {{ number_format($totalTransfer ?? 0,0,',','.') }}</h4>
            <p class="small">Nota: <span id="countTransferNotes">0</span></p>
        </div>
    </div>

    <div style="margin-top:18px;">
        <h5 id="weeklySummaryTitle" style="margin:0 0 8px 2px;">Ringkasan Mingguan (Bulan Ini)</h5>
        <div id="weeklySummaryContainer" style="display:flex; gap:12px; flex-wrap:wrap;"></div>
    </div>

    <!-- Tambahan: Dashboard visual sederhana -->
    <div class="content-panel mt-3">
        <h5 style="margin:6px 8px">Dashboard Visual</h5>
        <div style="display:flex; gap:16px; flex-wrap:wrap;">
            <div style="flex:1; min-width:320px; padding:12px;">
                <canvas id="incomeChart" height="160"></canvas>
            </div>
            <div style="flex:1; min-width:320px; padding:12px;">
                <canvas id="statusDonut" height="160"></canvas>
            </div>
        </div>

        <h5 style="margin:6px 8px">Detail Nota Laundry</h5>
        <div style="overflow-x:auto; padding:8px">
            <table class="table table-hover align-middle text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Pelanggan</th>
                        <th>Kasir</th>
                        <th>Tanggal</th>
                        <th>Pembayaran</th>
                        <th>Terbayar (Rp)</th>
                        <th>Total Awal (Rp)</th>
                        <th>Total (Rp)</th>
                        <th>Diskon (Rp)</th>
                        <th>Sisa</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="notesTable">
                    @forelse($notas as $n)
                            @php 
                                $status = $n->sisa <= 0 ? 'lunas' : 'belum'; 
                                $date_obj = \Carbon\Carbon::parse($n->created_at);
                                $formatted_date = $date_obj->format('d/m/Y');
                                $month_num = $date_obj->month;
                                $year_num = $date_obj->year; // Ambil tahun
                                $cashSum = $n->payments->where('type','cash')->sum('amount');
                                $transferSum = $n->payments->where('type','transfer')->sum('amount');
                                $paid = $cashSum + $transferSum;
                                $gap = max(0, $n->total - $paid);
                            @endphp
                            <tr data-status="{{ $status }}" data-month="{{ $month_num }}" data-year="{{ $year_num }}" data-cash="{{ $cashSum }}" data-transfer="{{ $transferSum }}" data-paid="{{ $paid }}">
                                <td>{{ $loop->iteration }}</td>
                                <td class="cell-name">{{ $n->customer_name }}</td>
                                <td class="cell-kasir">{{ optional($n->kasir)->name ?? '-' }}</td>
                                <td class="cell-date">{{ $formatted_date }}</td>
                                <td style="text-align:left;">
                                    @if($n->payments && $n->payments->count() > 0)
                                        @if($cashSum > 0)
                                            <span class="badge-cash">Cash: Rp {{ number_format($cashSum,0,',','.') }}</span>
                                        @endif
                                        @php $transferGrouped = $n->payments->where('type','transfer')->groupBy('method'); @endphp
                                        @foreach($transferGrouped as $method => $group)
                                            @php $methodLabel = $method ? ucfirst($method) : 'Transfer'; @endphp
                                            <span class="badge-transfer">{{ $methodLabel }}: Rp {{ number_format($group->sum('amount'),0,',','.') }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="cell-paid">{{ number_format($paid,0,',','.') }}</td>
                                <td class="cell-original">{{ number_format($n->items ? $n->items->sum('subtotal') : $n->total,0,',','.') }}</td>
                                <td class="cell-total">{{ number_format($n->total,0,',','.') }}</td>
                                <td class="cell-diskon">{{ number_format($n->payments ? $n->payments->sum('discount_amount') : 0,0,',','.') }}</td>
                                <td class="cell-sisa">{{ number_format($n->sisa,0,',','.') }}</td>
                                <td>
                                    @if($n->sisa <= 0)
                                        <span class="badge-lunas">Lunas</span>
                                    @else
                                        <span class="badge-belum">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="text-muted">Belum ada nota ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function(){
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const statusFilters = document.querySelectorAll('.filters [data-filter]');
    const payFilters = document.querySelectorAll('.filters [data-pay-filter]');
    const monthFilter = document.getElementById('monthFilter');
    const yearFilter = document.getElementById('yearFilter'); // Variabel untuk filter tahun
    const table = document.getElementById('notesTable');

    function normalizeText(t){ return t? t.toString().toLowerCase() : '' }

    // Fungsi untuk mengisi opsi tahun secara dinamis
    function populateYearFilter() {
        const years = new Set();
        const rows = table.querySelectorAll('tr[data-year]');
        rows.forEach(r => {
            const year = r.getAttribute('data-year');
            years.add(year);
        });

        // Urutkan tahun dari terbaru ke terlama
        const sortedYears = Array.from(years).sort((a, b) => b - a);

        // Hapus opsi lama kecuali "Semua Tahun"
        yearFilter.innerHTML = '<option value="all">Semua Tahun</option>';

        // Tambahkan opsi tahun yang ditemukan
        sortedYears.forEach(year => {
            const option = document.createElement('option');
            option.value = year;
            option.textContent = year;
            yearFilter.appendChild(option);
        });
    }

    function applyFilter(){
        const q = normalizeText(searchInput.value);
        const activeStatus = document.querySelector('.filters .btn-filter.active')?.getAttribute('data-filter') || 'all';
        const activeMonth = monthFilter.value;
        const activeYear = yearFilter.value; // Ambil nilai tahun yang dipilih
            const rows = table.querySelectorAll('tr[data-status]');
            const activePay = document.querySelector('.filters .btn-filter[data-pay-filter].active')?.getAttribute('data-pay-filter') || 'all';
        
        // Skip filtering jika tidak ada data selain pesan 'Belum ada nota'
        if(rows.length === 0 && table.querySelector('tr td[colspan="11"]')) {
            return;
        }

        rows.forEach(r => {
            const name = normalizeText(r.querySelector('.cell-name')?.textContent);
            const date = normalizeText(r.querySelector('.cell-date')?.textContent);
            const total = normalizeText(r.querySelector('.cell-total')?.textContent);
            const status = r.getAttribute('data-status');
            const rowMonth = r.getAttribute('data-month');
            const rowYear = r.getAttribute('data-year'); // Ambil data tahun dari baris

            const matchesQuery = q === '' || name.includes(q) || date.includes(q) || total.includes(q);
            const matchesStatus = activeStatus === 'all' || (activeStatus === 'lunas' && status === 'lunas') || (activeStatus === 'belum' && status === 'belum');
            
            // Logika filter bulan dan tahun digabungkan
            const matchesMonth = activeMonth === 'all' || rowMonth === activeMonth;
            const matchesYear = activeYear === 'all' || rowYear === activeYear; // Bandingkan tahun

            // matches by payment filter
            let matchesPay = true;
            if(activePay === 'cash'){
                matchesPay = parseFloat(r.getAttribute('data-cash') || 0) > 0;
            } else if(activePay === 'transfer'){
                matchesPay = parseFloat(r.getAttribute('data-transfer') || 0) > 0;
            }

            // Tampilkan baris jika semua filter cocok
            r.style.display = (matchesQuery && matchesStatus && matchesMonth && matchesYear && matchesPay) ? '' : 'none';
        });

        // Setelah filtering, update ringkasan counts & totals (gunakan fungsi global bila ada)
        if (window.computeTotalsIncludingUnpaid) {
            window.computeTotalsIncludingUnpaid();
        }
        if (window.computeTotalSisa) {
            window.computeTotalSisa();
        }
        if (window.updateDashboardCharts) {
            window.updateDashboardCharts();
        }
        if (window.buildWeeklySummaryCards) {
            window.buildWeeklySummaryCards();
        }
    }

    searchInput.addEventListener('input', applyFilter);
    clearBtn.addEventListener('click', () => { searchInput.value=''; applyFilter(); });
    
    // Event listener untuk Status Filter
    statusFilters.forEach(btn => {
        btn.addEventListener('click', ()=>{
            statusFilters.forEach(b=>b.classList.remove('active'));
            btn.classList.add('active');
            applyFilter();
        });
    });

    // Event listener untuk Payment Filter
    payFilters.forEach(btn => {
        btn.addEventListener('click', ()=>{
            payFilters.forEach(b=>b.classList.remove('active'));
            btn.classList.add('active');
            applyFilter();
        });
    });

    // Event listener untuk Filter Bulan dan TAHUN
    monthFilter.addEventListener('change', applyFilter);
    yearFilter.addEventListener('change', applyFilter); // Tambahkan listener untuk filter tahun


    // ✅ Refresh otomatis tanpa konfirmasi
    document.getElementById('refreshBtn').addEventListener('click', ()=>{
        // Reset filter
        searchInput.value = '';
        monthFilter.value = 'all';
        yearFilter.value = 'all'; // Reset filter tahun
        statusFilters.forEach(b=>b.classList.remove('active'));
        document.querySelector('.filters [data-filter="all"]').classList.add('active');
        // reset payment filters
        payFilters.forEach(b=>b.classList.remove('active'));
        document.querySelector('.filters [data-pay-filter="all"]').classList.add('active');
        
        applyFilter();

        Swal.fire({
            icon: 'success',
            title: 'Berhasil di-refresh!',
            text: 'Halaman laporan berhasil diperbarui.',
            showConfirmButton: false,
            timer: 1500
        });
        setTimeout(function () {
            window.location.reload();
        }, 1600);
    });

    // ✅ Export Excel Buttons + validasi tanggal + notifikasi sukses
    const excelBtn = document.getElementById('exportExcelBtn');
    const start = document.getElementById('start_date');
    const end = document.getElementById('end_date');

    function exportFile(url, msg=''){
        Swal.fire({
            title: 'Sedang menyiapkan file...',
            text: 'Mohon tunggu beberapa detik',
            icon: 'info',
            showConfirmButton: false,
            timerProgressBar: true,
            timer: 1500
        });
        window.location.href = url;
        setTimeout(()=>{
            Swal.fire({
                title: 'Berhasil!',
                html: msg || 'File Excel telah diunduh.',
                icon: 'success',
                timer: 2500,
                showConfirmButton: false
            });
        }, 2000);
    }

    excelBtn.addEventListener('click', ()=>{
        // ✅ Validasi tanggal kosong
        if(!start.value || !end.value){
            Swal.fire({
                icon: 'warning',
                title: 'Tanggal belum dipilih!',
                text: 'Silakan isi tanggal mulai dan tanggal sampai terlebih dahulu.',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        // ✅ Validasi tanggal salah urutan
        if(new Date(start.value) > new Date(end.value)){
            Swal.fire({
                icon: 'error',
                title: 'Tanggal tidak valid!',
                text: 'Tanggal mulai tidak boleh lebih besar dari tanggal sampai.',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        let url = "{{ route('admin.laporan.exportExcel') }}";
        url += `?start_date=${start.value}&end_date=${end.value}`;

        // ✅ Pesan sukses khusus untuk tanggal
        const msg = `Export berhasil untuk rentang tanggal <b>${start.value}</b> – <b>${end.value}</b>`;
        exportFile(url, msg);
    });

    document.getElementById('exportToday').addEventListener('click', ()=>{
        exportFile("{{ route('admin.laporan.exportExcel') }}?filter=daily", "Export berhasil untuk data <b>Harian</b>");
    });
    document.getElementById('exportWeek').addEventListener('click', ()=>{
        exportFile("{{ route('admin.laporan.exportExcel') }}?filter=weekly", "Export berhasil untuk data <b>Mingguan</b>");
    });
    document.getElementById('exportMonth').addEventListener('click', ()=>{
        exportFile("{{ route('admin.laporan.exportExcel') }}?filter=monthly", "Export berhasil untuk data <b>Bulanan</b>");
    });
    document.getElementById('exportYear').addEventListener('click', ()=>{
        exportFile("{{ route('admin.laporan.exportExcel') }}?filter=yearly", "Export berhasil untuk data <b>Tahunan</b>");
    });

    // Panggil ini saat halaman dimuat
    populateYearFilter(); 
    applyFilter();
})();
</script>

<!-- Tambahan: Hitung ulang kartu pendapatan di sisi klien agar nota BELUM LUNAS juga dihitung (pilihan A) dan jumlah nota per pendapatan -->
<script>
(function(){
    // Parse string rupiah seperti "1.234.567" atau "Rp 1.234.567" menjadi number
    function parseRupiah(str){
        if(!str) return 0;
        // Hapus semua selain digit dan minus
        const digits = str.toString().replace(/[^0-9-]/g, '');
        return digits === '' ? 0 : parseInt(digits, 10);
    }

    function formatRupiah(num){
        if(isNaN(num)) num = 0;
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function getRows(){
        return Array.from(document.querySelectorAll('#notesTable tr[data-year]'));
    }

    function getFilterContext(){
        const now = new Date();
        const monthEl = document.getElementById('monthFilter');
        const yearEl = document.getElementById('yearFilter');

        let month = now.getMonth() + 1;
        let year = now.getFullYear();

        if (monthEl && monthEl.value && monthEl.value !== 'all') {
            const parsedMonth = parseInt(monthEl.value, 10);
            if (!isNaN(parsedMonth) && parsedMonth >= 1 && parsedMonth <= 12) {
                month = parsedMonth;
            }
        }

        if (yearEl && yearEl.value && yearEl.value !== 'all') {
            const parsedYear = parseInt(yearEl.value, 10);
            if (!isNaN(parsedYear)) {
                year = parsedYear;
            }
        }

        return { month, year };
    }

    function formatDateId(dateObj){
        const d = ('0' + dateObj.getDate()).slice(-2);
        const m = ('0' + (dateObj.getMonth() + 1)).slice(-2);
        const y = dateObj.getFullYear();
        return `${d}/${m}/${y}`;
    }

    // Chart instances
    let incomeChart = null;
    let statusDonut = null;

    function computeTotalsIncludingUnpaid(){
        const allRows = getRows();
        const visibleRows = allRows.filter(r => r.style.display !== 'none');
        const nowReal = new Date();
        const { month: contextMonth, year: contextYear } = getFilterContext();

        // Gunakan tanggal berdasarkan filter (bulan/tahun), tapi tetap pakai hari yang sama dengan hari ini
        const baseDate = new Date(contextYear, contextMonth - 1, nowReal.getDate());

        // week start (Monday) and end (Sunday) untuk konteks filter
        const day = baseDate.getDay(); // 0 (Sun) - 6 (Sat)
        const diffToMonday = (day + 6) % 7; // days since Monday
        const monday = new Date(baseDate);
        monday.setDate(baseDate.getDate() - diffToMonday);
        monday.setHours(0,0,0,0);
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6);
        sunday.setHours(23,59,59,999);

        let harian = 0, mingguan = 0, bulanan = 0, tahunan = 0;
        let countHarian = 0, countMingguan = 0, countBulanan = 0, countTahunan = 0;

        // Hitung harian, mingguan, bulanan dari baris yang sedang terlihat (terpengaruh filter bulan)
        visibleRows.forEach(r => {
            const dateText = r.querySelector('.cell-date')?.textContent?.trim() || '';
            const totalText = r.querySelector('.cell-total')?.textContent?.trim() || '';
            const total = parseRupiah(totalText);

            // Parse date dd/mm/yyyy
            const parts = dateText.split('/');
            let rowDate = null;
            if(parts.length === 3){
                const d = parseInt(parts[0],10);
                const m = parseInt(parts[1],10);
                const y = parseInt(parts[2],10);
                rowDate = new Date(y, m-1, d);
            }

            // Semua nota (termasuk belum lunas) dihitung ke pendapatan
            if(rowDate){
                // harian: tanggal sama dengan tanggal pada konteks filter
                if(
                    rowDate.getDate() === baseDate.getDate() &&
                    rowDate.getMonth() === baseDate.getMonth() &&
                    rowDate.getFullYear() === baseDate.getFullYear()
                ){
                    harian += total;
                    countHarian += 1;
                }

                // mingguan: berada di rentang monday..sunday untuk konteks filter
                if(rowDate >= monday && rowDate <= sunday){
                    mingguan += total;
                    countMingguan += 1;
                }

                // bulanan: month & year sama dengan filter
                if(
                    (rowDate.getMonth() + 1) === contextMonth &&
                    rowDate.getFullYear() === contextYear
                ){
                    bulanan += total;
                    countBulanan += 1;
                }

                // tahunan: year sama dengan filter
                if(rowDate.getFullYear() === contextYear){
                    tahunan += total;
                    countTahunan += 1;
                }
            }
        });

        // Update kartu (tanpa mengubah markup asli server-side, hanya mengganti teks jumlah)
        const harianEl = document.querySelector('#cardHarian');
        const mingguanEl = document.querySelector('#cardMingguan');
        const bulananEl = document.querySelector('#cardBulanan');
        const tahunanEl = document.querySelector('#cardTahunan');

        if(harianEl) harianEl.textContent = formatRupiah(harian);
        if(mingguanEl) mingguanEl.textContent = formatRupiah(mingguan);
        if(bulananEl) bulananEl.textContent = formatRupiah(bulanan);
        if(tahunanEl) tahunanEl.textContent = formatRupiah(tahunan);

        // Update note counts on cards
        const countHarianEl = document.getElementById('countHarian');
        const countMingguanEl = document.getElementById('countMingguan');
        if(countHarianEl) countHarianEl.textContent = countHarian;
        if(countMingguanEl) countMingguanEl.textContent = countMingguan;
        // Compute totals & counts for payment methods (from visible rows)
        let cashSum = 0, transferSum = 0, countCash = 0, countTransfer = 0;
        visibleRows.forEach(r => {
            const c = parseInt(r.getAttribute('data-cash') || 0, 10) || 0;
            const t = parseInt(r.getAttribute('data-transfer') || 0, 10) || 0;
            if(c > 0){ cashSum += c; countCash += 1; }
            if(t > 0){ transferSum += t; countTransfer += 1; }
        });

        const totalCashEl = document.getElementById('totalCash');
        const totalTransferEl = document.getElementById('totalTransfer');
        if(totalCashEl) totalCashEl.textContent = formatRupiah(cashSum);
        if(totalTransferEl) totalTransferEl.textContent = formatRupiah(transferSum);

        const countCashEl = document.getElementById('countCashNotes');
        const countTransferEl = document.getElementById('countTransferNotes');
        if(countCashEl) countCashEl.textContent = countCash;
        if(countTransferEl) countTransferEl.textContent = countTransfer;

        // Update remaining counts (bulanan & tahunan only)
        document.getElementById('countBulanan').textContent = countBulanan;
        document.getElementById('countTahunan').textContent = countTahunan;

        // Tambahan: pendapatan TAHUNAN harus hanya mengikuti filter tahun,
        // tidak terpengaruh filter bulan. Jadi kita ulangi perhitungan tahunan
        // memakai SEMUA baris yang tahunnya sama dengan contextYear.
        let tahunanYearOnly = 0;
        let countTahunanYearOnly = 0;
        allRows.forEach(r => {
            const dateText = r.querySelector('.cell-date')?.textContent?.trim() || '';
            const totalText = r.querySelector('.cell-total')?.textContent?.trim() || '';
            const total = parseRupiah(totalText);

            const parts = dateText.split('/');
            if (parts.length === 3) {
                const d = parseInt(parts[0],10);
                const m = parseInt(parts[1],10);
                const y = parseInt(parts[2],10);
                const rowDate = new Date(y, m-1, d);

                if (rowDate.getFullYear() === contextYear) {
                    tahunanYearOnly += total;
                    countTahunanYearOnly += 1;
                }
            }
        });

        if (tahunanEl) tahunanEl.textContent = formatRupiah(tahunanYearOnly);
        document.getElementById('countTahunan').textContent = countTahunanYearOnly;
    }

    function buildWeeklySummaryCards(){
        const container = document.getElementById('weeklySummaryContainer');
        const titleEl = document.getElementById('weeklySummaryTitle');
        if (!container || !titleEl) return;

        const { month, year } = getFilterContext();

        const monthNames = [
            'Januari','Februari','Maret','April','Mei','Juni',
            'Juli','Agustus','September','Oktober','November','Desember'
        ];

        const monthLabel = monthNames[month - 1] || 'Bulan Ini';
        titleEl.textContent = `Ringkasan Mingguan (${monthLabel} ${year})`;

        const startOfMonth = new Date(year, month - 1, 1);
        const endOfMonth = new Date(year, month, 0); // last day of month

        const weeks = [];
        let cursor = new Date(startOfMonth);

        while (cursor <= endOfMonth) {
            let weekStart = new Date(cursor);
            const dayOfWeek = weekStart.getDay();
            const diffToMonday = (dayOfWeek + 6) % 7;
            weekStart.setDate(weekStart.getDate() - diffToMonday);
            if (weekStart < startOfMonth) {
                weekStart = new Date(startOfMonth);
            }

            let weekEnd = new Date(weekStart);
            weekEnd.setDate(weekEnd.getDate() + 6);
            if (weekEnd > endOfMonth) {
                weekEnd = new Date(endOfMonth);
            }

            weeks.push({ start: weekStart, end: weekEnd });

            cursor = new Date(weekEnd);
            cursor.setDate(cursor.getDate() + 1);
        }

        const visibleRows = getRows().filter(r => r.style.display !== 'none');

        const weekData = weeks.map((w, index) => {
            let total = 0;
            let count = 0;

            visibleRows.forEach(r => {
                const dateText = r.querySelector('.cell-date')?.textContent?.trim() || '';
                const totalText = r.querySelector('.cell-total')?.textContent?.trim() || '';

                const parts = dateText.split('/');
                if (parts.length === 3) {
                    const d = parseInt(parts[0],10);
                    const m = parseInt(parts[1],10);
                    const y = parseInt(parts[2],10);
                    const rowDate = new Date(y, m-1, d);

                    if (rowDate >= w.start && rowDate <= w.end) {
                        total += parseRupiah(totalText);
                        count += 1;
                    }
                }
            });

            return {
                label: 'Minggu ' + (index + 1),
                start: w.start,
                end: w.end,
                total,
                count
            };
        });

        container.innerHTML = '';
        weekData.forEach(week => {
            const card = document.createElement('div');
            card.className = 'card weekly-card';
            card.style.flex = '1 1 220px';
            card.style.maxWidth = '260px';
            card.innerHTML = `
                <h6>${week.label.toUpperCase()}</h6>
                <p class="small" style="margin-top:4px; font-size:12px; opacity:.75;">
                    ${formatDateId(week.start)} &mdash; ${formatDateId(week.end)}
                </p>
                <h4 style="margin-top:10px;">${formatRupiah(week.total)}</h4>
                <p class="small">Nota: ${week.count}</p>
            `;
            container.appendChild(card);
        });
    }

    // Jalankan saat halaman siap
    document.addEventListener('DOMContentLoaded', () => {
        computeTotalsIncludingUnpaid();
        buildWeeklySummaryCards();
    });

    // Jalankan juga setelah klik Refresh
    const refreshBtn = document.getElementById('refreshBtn');
    if(refreshBtn){
        refreshBtn.addEventListener('click', function(){
            // beri sedikit delay agar perubahan DOM (filter reset) selesai
            setTimeout(computeTotalsIncludingUnpaid, 250);
        });
    }

    // Jika tabel berubah dinamis melalui AJAX (tidak ada sekarang), developer bisa memanggil computeTotalsIncludingUnpaid() setelah update.

    // Jalankan sekali langsung
    computeTotalsIncludingUnpaid();

    // Chart helpers: kumpulkan data bulanan (total & jumlah nota) dari DOM (semua tahun yang terlihat pada tabel)
    function collectMonthlyData(yearFilterValue){
        const rows = getRows().filter(r => r.style.display !== 'none');
        const months = Array.from({length:12},()=>({total:0,count:0}));
        rows.forEach(r => {
            const dateText = r.querySelector('.cell-date')?.textContent?.trim() || '';
            const totalText = r.querySelector('.cell-total')?.textContent?.trim() || '';
            const total = parseRupiah(totalText);
            const parts = dateText.split('/');
            if(parts.length === 3){
                const d = parseInt(parts[0],10);
                const m = parseInt(parts[1],10);
                const y = parseInt(parts[2],10);
                if(yearFilterValue === 'all' || String(y) === String(yearFilterValue)){
                    months[m-1].total += total;
                    months[m-1].count += 1;
                }
            }
        });
        return months;
    }

    function initCharts(){
        const ctx = document.getElementById('incomeChart').getContext('2d');
        const ctx2 = document.getElementById('statusDonut').getContext('2d');

        const monthsLabel = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
        const monthly = collectMonthlyData('all');
        const totals = monthly.map(m=>m.total);

        incomeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthsLabel,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: totals,
                    // colors are left to Chart.js defaults (do not set explicit colors)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        // status donut
        const statusCounts = computeStatusCounts();
        statusDonut = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Lunas','Belum Lunas'],
                datasets: [{
                    data: [statusCounts.lunas, statusCounts.belum],
                }]
            },
            options: { responsive: true, maintainAspectRatio: false }
        });
    }

    function computeStatusCounts(){
        const rows = getRows().filter(r => r.style.display !== 'none');
        let lunas = 0, belum = 0;
        rows.forEach(r => {
            const status = r.getAttribute('data-status');
            if(status === 'lunas') lunas += 1; else belum += 1;
        });
        return { lunas, belum };
    }

    function updateDashboardCharts(){
        if(!incomeChart || !statusDonut) return;
        const yearVal = document.getElementById('yearFilter')?.value || 'all';
        const monthly = collectMonthlyData(yearVal);
        const totals = monthly.map(m=>m.total);
        incomeChart.data.datasets[0].data = totals;
        incomeChart.update();

        const statusCounts = computeStatusCounts();
        statusDonut.data.datasets[0].data = [statusCounts.lunas, statusCounts.belum];
        statusDonut.update();

        // update jumlah belum lunas pada card
        document.getElementById('countBelumLunas').textContent = statusCounts.belum;
    }

    // Init charts after DOM ready
    document.addEventListener('DOMContentLoaded', ()=>{
        // small delay to ensure populateYearFilter has run
        setTimeout(()=>{
            initCharts();
            updateDashboardCharts();
        }, 300);
    });

    // Recompute charts when filters change (bulan & tahun)
    document.getElementById('yearFilter')?.addEventListener('change', ()=>{
        setTimeout(()=>{ updateDashboardCharts(); }, 200);
    });
    document.getElementById('monthFilter')?.addEventListener('change', ()=>{
        setTimeout(()=>{ updateDashboardCharts(); }, 200);
    });

    // Also recompute when search / filters / refresh happen
    document.getElementById('searchInput')?.addEventListener('input', ()=> setTimeout(()=>{ updateDashboardCharts(); }, 250));
    document.querySelectorAll('.btn-filter[data-filter]').forEach(btn=>{
        btn.addEventListener('click', ()=> setTimeout(()=>{ updateDashboardCharts(); }, 250));
    });

    // Ekspor fungsi ke global agar script lain dapat memanggilnya
    window.computeTotalsIncludingUnpaid = computeTotalsIncludingUnpaid;
    window.updateDashboardCharts = updateDashboardCharts;
    window.buildWeeklySummaryCards = buildWeeklySummaryCards;

})();
</script>

<!-- ➕ SCRIPT TOTAL SISA (ditambahkan tanpa mengubah script exist) -->
<script>
// ➕ SCRIPT TOTAL SISA
(function(){
    function parseRupiah(str){
        if(!str) return 0;
        // Hapus semua selain digit
        const digits = str.toString().replace(/[^0-9-]/g,'');
        return digits === '' ? 0 : parseInt(digits,10);
    }

    function formatRupiah(num){
        if(isNaN(num)) num = 0;
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

        function computeTotalSisa(){
        const rows = document.querySelectorAll('#notesTable tr[data-status]');
        let totalSisa = 0;
        let countBelum = 0;

        // Jika tidak ada baris data (hanya pesan kosong), hasil tetap 0
        rows.forEach(r => {
            // cari cell dengan kelas .cell-sisa
            const sisaCell = r.querySelector('.cell-sisa');
            if(sisaCell){
                const s = parseRupiah(sisaCell.textContent.trim());
                totalSisa += s;
                if(s > 0) countBelum += 1;
            }
        });

        const sisaCard = document.getElementById('totalSisaCard');
        if(sisaCard){
            sisaCard.textContent = formatRupiah(totalSisa);
        }
        document.getElementById('countBelumLunas').textContent = countBelum;
    }

    document.addEventListener('DOMContentLoaded', computeTotalSisa);

    // Hitung ulang saat refresh
    const refreshBtn = document.getElementById('refreshBtn');
    if(refreshBtn){
        refreshBtn.addEventListener('click', () => {
            setTimeout(computeTotalSisa, 350);
        });
    }

    // Hitung ulang saat filter berubah atau pencarian
    const searchInput = document.getElementById('searchInput');
    if(searchInput) searchInput.addEventListener('input', ()=> setTimeout(computeTotalSisa, 200));
    const monthFilter = document.getElementById('monthFilter');
    if(monthFilter) monthFilter.addEventListener('change', ()=> setTimeout(computeTotalSisa, 200));
    const yearFilter = document.getElementById('yearFilter');
    if(yearFilter) yearFilter.addEventListener('change', ()=> setTimeout(computeTotalSisa, 200));

    // Saat status filter diklik
    document.querySelectorAll('.btn-filter[data-filter]').forEach(btn=>{
        btn.addEventListener('click', ()=> setTimeout(computeTotalSisa, 250));
    });

    // Jika tombol export mempengaruhi view lokal, hitung ulang setelah klik
    ['exportToday','exportWeek','exportMonth','exportYear'].forEach(id=>{
        const el = document.getElementById(id);
        if(el) el.addEventListener('click', ()=> setTimeout(computeTotalSisa, 400));
    });

    // Jalankan sekali lagi untuk memastikan nilai ter-update jika DOM sudah dimodifikasi
    setTimeout(computeTotalSisa, 500);

    // Ekspor ke global agar bisa dipanggil dari script filter utama
    window.computeTotalSisa = computeTotalSisa;
})();
</script>
@endsection
