@extends('layouts.app')

@section('content')
<!---- Deva Laundry — macOS Fullscreen Light Mode Blade (dengan warna + animasi hover di kartu pendapatan) ---->
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
    .stats{ display:grid; grid-template-columns: repeat(4,1fr); gap:16px; flex-wrap: wrap; }

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

    /* 🌈 Warna khusus untuk tiap kartu pendapatan */
    .card.harian {
        background: linear-gradient(135deg, #0051ffff);
    }
    .card.mingguan {
        background: linear-gradient(135deg, #0051ffff);
    }
    .card.bulanan {
        background: linear-gradient(135deg,  #0051ffff);
    }
    .card.tahunan {
        background: linear-gradient(135deg,  #0051ffff);
    }

    /* Table area */
    .content-panel{ flex:1; overflow:auto; padding:8px; border-radius:12px; background: linear-gradient(180deg, rgba(255,255,255,0.6), rgba(255,255,255,0.75)); border:1px solid var(--glass-border); }
    table{ width:100%; border-collapse:collapse; min-width:1080px; }
    thead th{ text-align:center; padding:12px; font-weight:700; background:transparent; border-bottom:1px solid rgba(0,0,0,0.06); }
    tbody td{ padding:12px; vertical-align:middle; }

    .badge-lunas{ background:#e6ffef; color:#04683a; padding:6px 10px; border-radius:999px; font-weight:700; }
    .badge-belum{ background:#fff4e6; color:#7a4b0b; padding:6px 10px; border-radius:999px; font-weight:700; }

    /* Responsive */
    @media(max-width:900px){
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

    <!-- Toolbar -->
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

        <!-- Bagian Ekspor & Filter Tanggal -->
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

    <!-- Stats -->
    <div class="stats">
        <div class="card harian">
            <h6>Pendapatan Hari Ini</h6>
            <h4>Rp {{ number_format($harian,0,',','.') }}</h4>
        </div>
        <div class="card mingguan">
            <h6>Pendapatan Minggu Ini</h6>
            <h4>Rp {{ number_format($mingguan,0,',','.') }}</h4>
        </div>
        <div class="card bulanan">
            <h6>Pendapatan Bulan Ini</h6>
            <h4>Rp {{ number_format($bulanan,0,',','.') }}</h4>
        </div>
        <div class="card tahunan">
            <h6>Pendapatan Tahun Ini</h6>
            <h4>Rp {{ number_format($tahunan,0,',','.') }}</h4>
        </div>
    </div>

    <!-- Table -->
    <div class="content-panel mt-3">
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
                        @endphp
                        <tr data-status="{{ $status }}">
                            <td>{{ $loop->iteration }}</td>
                            <td class="cell-name">{{ $n->customer_name }}</td>
                            <td class="cell-date">{{ \Carbon\Carbon::parse($n->created_at)->format('d/m/Y') }}</td>
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

<!-- Interactivity -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function(){
    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearch');
    const filters = document.querySelectorAll('[data-filter]');
    const table = document.getElementById('notesTable');

    function normalizeText(t){ return t? t.toString().toLowerCase() : '' }

    function applyFilter(){
        const q = normalizeText(searchInput.value);
        const active = document.querySelector('.btn-filter.active')?.getAttribute('data-filter') || 'all';
        const rows = table.querySelectorAll('tr[data-status]');
        rows.forEach(r => {
            const name = normalizeText(r.querySelector('.cell-name')?.textContent);
            const date = normalizeText(r.querySelector('.cell-date')?.textContent);
            const total = normalizeText(r.querySelector('.cell-total')?.textContent);
            const status = r.getAttribute('data-status');

            const matchesQuery = q === '' || name.includes(q) || date.includes(q) || total.includes(q);
            const matchesStatus = active === 'all' || (active === 'lunas' && status === 'lunas') || (active === 'belum' && status === 'belum');

            r.style.display = (matchesQuery && matchesStatus) ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', applyFilter);
    clearBtn.addEventListener('click', () => { searchInput.value=''; applyFilter(); });
    filters.forEach(btn => {
        btn.addEventListener('click', ()=>{
            filters.forEach(b=>b.classList.remove('active'));
            btn.classList.add('active');
            applyFilter();
        });
    });

    // ✅ Refresh otomatis tanpa konfirmasi
    document.getElementById('refreshBtn').addEventListener('click', ()=>{
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

    applyFilter();
})();
</script>
@endsection
