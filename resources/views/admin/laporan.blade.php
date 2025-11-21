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
        --card-shadow: 0 8px 24px rgba(15,23,42,0.06);
        --traffic-size: 12px;
    }
    html,body,#app, .container-full {
        height: 100%;
        background: linear-gradient(180deg, #ffffff 0%, var(--bg) 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;
        color: var(--accent);
    }

    /* Fullscreen macOS-like window */
    .mac-window{
        width: 100%;
        height: 100vh;
        padding: 28px 24px 32px 24px;
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
        padding: 10px 12px;
        border-radius: 12px;
        background: linear-gradient(180deg, rgba(255,255,255,0.7), rgba(250,250,250,0.85));
        box-shadow: var(--card-shadow);
        border: 1px solid var(--glass-border);
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
    .title-text h3{ margin:0; font-size:40px; font-weight:700; }
    .title-text p{ margin:0; font-size:20px; color:var(--muted); }

    /* Toolbar */
    .toolbar{
        display:flex;
        gap:12px;
        align-items:center;
        justify-content:space-between;
        flex-wrap: wrap;
    }
    .toolbar-left{ display:flex; gap:10px; align-items:center; flex-wrap: wrap; }
    .search-box{ display:flex; gap:8px; align-items:center; background:var(--panel); padding:6px 10px; border-radius:12px; border:1px solid var(--glass-border); box-shadow: 0 2px 6px rgba(15,23,42,0.03); }
    .search-box input{ border:none; outline:none; width:320px; background:transparent; font-size:14px; }

    .filters{ display:flex; gap:8px; align-items:center; flex-wrap: wrap; }
    .btn-filter{ padding:8px 12px; border-radius:10px; border:1px solid rgba(15,23,42,0.06); background:transparent; cursor:pointer; font-weight:600; transition:all 0.2s; }
    .btn-filter.active{ background: linear-gradient(180deg,#eef2ff,#ffffff); box-shadow: var(--card-shadow); }

    /* Summary cards */
    .stats{ display:grid; grid-template-columns: repeat(5,1fr); gap:16px; flex-wrap: wrap; }

    .card{
        padding:16px;
        border-radius:14px;
        border:1px solid var(--glass-border);
        box-shadow: var(--card-shadow);
        color:#fff;
        transition: all 0.3s ease;
        transform: translateY(0);
        cursor: pointer;
    }
    .card:hover {
        transform: translateY(-4px);
        filter: brightness(1.08);
        box-shadow: 0 12px 30px rgba(15,23,42,0.1);
    }
    .card h6{ margin:0; font-weight:600; font-size:13px; opacity:0.9; }
    .card h4{ margin-top:8px; font-size:22px; font-weight:700; }
    .card p.small{ margin:6px 0 0; font-size:13px; opacity:0.95; }

    /* 🌈 Warna khusus untuk tiap kartu pendapatan */
    .card.harian {
        background: linear-gradient(135deg, #0051ffff);
    }
    .card.mingguan {
        background: linear-gradient(135deg, #0066d6ff);
    }
    .card.bulanan {
        background: linear-gradient(135deg,  #0088e6ff);
    }
    .card.tahunan {
        background: linear-gradient(135deg,  #00a3ffff);
    }

    /* Tambahan styling untuk card sisa total (ditambahkan tanpa merubah yang lain) */
    .card.sisa-total {
        background: linear-gradient(135deg, #ff0000ff);
    }

    /* Table area */
    .content-panel{ flex:1; overflow:auto; padding:8px; border-radius:12px; background: linear-gradient(180deg, rgba(255,255,255,0.6), rgba(255,255,255,0.75)); border:1px solid var(--glass-border); }
    table{ width:100%; border-collapse:collapse; min-width:1080px; }
    thead th{ text-align:center; padding:12px; font-weight:700; background:transparent; border-bottom:1px solid rgba(0,0,0,0.06); }
    tbody td{ padding:12px; vertical-align:middle; }

    .badge-lunas{ background:#e6ffef; color:#04683a; padding:6px 10px; border-radius:999px; font-weight:700; }
    .badge-belum{ background:#fff4e6; color:#7a4b0b; padding:6px 10px; border-radius:999px; font-weight:700; }

    /* Responsive */
    @media(max-width:1100px){
        .stats{ grid-template-columns: repeat(2,1fr); }
        .search-box input{ width:160px; }
        table{ min-width:760px; }
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
        <div style="overflow:auto; padding:8px">
            <table class="table table-hover align-middle text-center">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Total (Rp)</th>
                        <th>Uang Muka</th>
                        <th>Sisa</th>
                        <th>Kasir</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="notesTable">
                    @forelse($notas as $n)
                        @php 
                            $status = $n->sisa <= 0 ? 'lunas' : 'belum'; 
                            $kasir = $n->kasir_name ?? ($n->kasir->name ?? 'Tidak Diketahui'); 
                            $date_obj = \Carbon\Carbon::parse($n->created_at);
                            $formatted_date = $date_obj->format('d/m/Y');
                            $month_num = $date_obj->month;
                            $year_num = $date_obj->year; // Ambil tahun
                        @endphp
                        <tr data-status="{{ $status }}" data-month="{{ $month_num }}" data-year="{{ $year_num }}">
                            <td>{{ $loop->iteration }}</td>
                            <td class="cell-name">{{ $n->customer_name }}</td>
                            <td class="cell-date">{{ $formatted_date }}</td>
                            <td class="cell-total">{{ number_format($n->total,0,',','.') }}</td>
                            <td>{{ number_format($n->uang_muka,0,',','.') }}</td>
                            <td>{{ number_format($n->sisa,0,',','.') }}</td>
                            <td>{{ $kasir }}</td>
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
                            <td colspan="8" class="text-muted">Belum ada nota ditemukan.</td>
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
        
        // Skip filtering jika tidak ada data selain pesan 'Belum ada nota'
        if(rows.length === 0 && table.querySelector('tr td[colspan="8"]')) {
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

            // Tampilkan baris jika semua filter cocok
            r.style.display = (matchesQuery && matchesStatus && matchesMonth && matchesYear) ? '' : 'none';
        });

        // Setelah filtering, update ringkasan counts & totals
        computeTotalsIncludingUnpaid();
        computeTotalSisa();
        updateDashboardCharts();
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
        
        applyFilter();

        Swal.fire({
            icon: 'success',
            title: 'Data diperbarui!',
            showConfirmButton: false,
            timer: 1200
        });
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

    // Chart instances
    let incomeChart = null;
    let statusDonut = null;

    function computeTotalsIncludingUnpaid(){
        const rows = getRows().filter(r => r.style.display !== 'none');
        const now = new Date();
        const todayDay = ('0' + now.getDate()).slice(-2);
        const todayMonth = now.getMonth() + 1; // 1-12
        const todayYear = now.getFullYear();

        // week start (Monday) and end (Sunday)
        const day = now.getDay(); // 0 (Sun) - 6 (Sat)
        const diffToMonday = (day + 6) % 7; // days since Monday
        const monday = new Date(now);
        monday.setDate(now.getDate() - diffToMonday);
        monday.setHours(0,0,0,0);
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6);
        sunday.setHours(23,59,59,999);

        let harian = 0, mingguan = 0, bulanan = 0, tahunan = 0;
        let countHarian = 0, countMingguan = 0, countBulanan = 0, countTahunan = 0;

        rows.forEach(r => {
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

            // Semua nota (termasuk belum lunas) dihitung ke pendapatan sesuai pilihan A
            if(rowDate){
                // harian: tanggal sama dengan hari ini
                if(rowDate.getDate() === now.getDate() && rowDate.getMonth() === now.getMonth() && rowDate.getFullYear() === now.getFullYear()){
                    harian += total;
                    countHarian += 1;
                }

                // mingguan: berada di rentang monday..sunday
                if(rowDate >= monday && rowDate <= sunday){
                    mingguan += total;
                    countMingguan += 1;
                }

                // bulanan: month & year sama
                if(rowDate.getMonth() + 1 === todayMonth && rowDate.getFullYear() === todayYear){
                    bulanan += total;
                    countBulanan += 1;
                }

                // tahunan: year sama
                if(rowDate.getFullYear() === todayYear){
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

        // Update counts
        document.getElementById('countHarian').textContent = countHarian;
        document.getElementById('countMingguan').textContent = countMingguan;
        document.getElementById('countBulanan').textContent = countBulanan;
        document.getElementById('countTahunan').textContent = countTahunan;
    }

    // Jalankan saat halaman siap
    document.addEventListener('DOMContentLoaded', computeTotalsIncludingUnpaid);

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

    // Recompute charts when filters change
    document.getElementById('yearFilter')?.addEventListener('change', ()=>{
        setTimeout(()=>{ updateDashboardCharts(); }, 200);
    });

    // Also recompute when search / filters / refresh happen
    document.getElementById('searchInput')?.addEventListener('input', ()=> setTimeout(()=>{ updateDashboardCharts(); }, 250));
    document.querySelectorAll('.btn-filter[data-filter]').forEach(btn=>{
        btn.addEventListener('click', ()=> setTimeout(()=>{ updateDashboardCharts(); }, 250));
    });

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
            // kolom ke-6 (index 5) adalah Sisa sesuai struktur tabel
            const sisaCell = r.children[5];
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
})();
</script>
@endsection
