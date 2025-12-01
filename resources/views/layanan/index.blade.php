@extends('layouts.app')

@section('content')
<style>
    /* Apple Pro / macOS-style layout untuk halaman layanan */
    .layanan-macos-page {
        padding: 48px 0 56px;
        background: radial-gradient(circle at top left, #fdfdfd 0, #e5edff 30%, #e0ecff 55%, #f5f7fb 100%);
        min-height: calc(100vh - 70px);
    }

    .layanan-shell {
        width: 100%;
        max-width: 100%;
        margin: 0;
        padding: 0 24px 0 18px; /* beri sedikit ruang dari sidebar dan sisi kanan */
    }

    .layanan-shell-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        margin-bottom: 1.75rem;
    }

    .layanan-shell-title {
        display: flex;
        flex-direction: column;
        gap: .25rem;
    }

    .layanan-eyebrow {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.18em;
        color: #9ca3af;
    }

    .layanan-shell-title h3 {
        font-weight: 800;
        letter-spacing: -0.03em;
        color: #0f172a;
        margin: 0;
    }

    .layanan-shell-title p {
        margin: 0;
        font-size: .9rem;
        color: #6b7280;
    }

    .layanan-window {
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.9);
        box-shadow:
            0 26px 60px rgba(15, 23, 42, 0.15),
            0 0 0 1px rgba(148, 163, 184, 0.18);
        backdrop-filter: blur(22px);
        -webkit-backdrop-filter: blur(22px);
        overflow: hidden;
    }

    .layanan-window-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.8rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.9);
        background: linear-gradient(135deg, #f9fafb, #e5edff);
    }

    .mac-traffic-lights {
        display: inline-flex;
        gap: 0.4rem;
        align-items: center;
    }

    .mac-traffic-light {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }

    .mac-traffic-light.red { background: #ff5f57; }
    .mac-traffic-light.yellow { background: #febc2e; }
    .mac-traffic-light.green { background: #28c840; }

    .layanan-window-title {
        font-size: .95rem;
        color: #374151;
        font-weight: 600;
    }

    .btn-mac-primary {
        background: linear-gradient(135deg, #0a84ff, #0051cc);
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.7);
        color: #fff;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 9px 18px;
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        text-decoration: none;
        box-shadow: 0 10px 26px rgba(15, 23, 42, 0.3);
        transition: all 0.22s ease;
    }

    .btn-mac-primary:hover {
        background: linear-gradient(135deg, #4ba5ff, #0a84ff);
        transform: translateY(-1px);
        box-shadow: 0 16px 34px rgba(15, 23, 42, 0.35);
        color: #fff;
    }

    .btn-mac-primary-icon {
        width: 18px;
        height: 18px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.18);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .layanan-window-body {
        padding: 1.9rem 1.8rem 1.8rem;
    }

    .layanan-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .layanan-count {
        font-size: .85rem;
        color: #6b7280;
    }

    .layanan-search {
        position: relative;
        max-width: 260px;
        width: 100%;
    }

    .layanan-search input {
        border-radius: 999px;
        padding: 7px 12px 7px 32px;
        font-size: .85rem;
        border: 1px solid rgba(148, 163, 184, 0.4);
        background: #f9fafb;
    }

    .layanan-search-icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: .85rem;
        color: #9ca3af;
    }

    .layanan-table-wrapper {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid rgba(226, 232, 240, 0.9);
        background: rgba(255, 255, 255, 0.96);
        box-shadow: 0 14px 40px rgba(15, 23, 42, 0.08);
    }

    .layanan-table {
        margin-bottom: 0;
        font-size: .92rem;
    }

    .layanan-table thead {
        background: linear-gradient(135deg, #f3f4f6, #e5f1ff);
    }

    .layanan-table thead th {
        border-bottom: 1px solid rgba(209, 213, 219, 0.9);
        text-transform: uppercase;
        letter-spacing: 0.06em;
        font-size: .74rem;
        color: #6b7280;
    }

    .layanan-table tbody tr:nth-child(even) {
        background: #f9fafb;
    }

    .layanan-table tbody tr:hover {
        background: #eef4ff;
    }

    .layanan-table td {
        vertical-align: middle !important;
        color: #111827;
        }

    .layanan-name {
        font-weight: 700;
        color: #0f172a;
        font-size: .98rem;
    }

    .layanan-description {
        max-width: 520px;
        margin: 0 auto;
        font-size: .85rem;
        color: #6b7280;
    }

    .layanan-actions {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .btn-chip {
        border-radius: 999px;
        border: 0;
        font-size: .8rem;
        padding: 6px 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        color: #f9fafb;
        box-shadow: 0 10px 24px rgba(15,23,42,0.18);
        transition: all .18s ease;
    }

    .btn-chip svg {
        width: 15px;
        height: 15px;
    }

    .btn-chip-edit {
        background: linear-gradient(135deg, #0ea5e9, #6366f1);
    }

    .btn-chip-edit:hover {
        background: linear-gradient(135deg, #38bdf8, #818cf8);
        transform: translateY(-1px);
        box-shadow: 0 14px 30px rgba(37,99,235,0.35);
    }

    .btn-chip-delete {
        background: linear-gradient(135deg, #f97373, #ef4444);
    }

    .btn-chip-delete:hover {
        background: linear-gradient(135deg, #fca5a5, #f97373);
        transform: translateY(-1px);
        box-shadow: 0 14px 30px rgba(220,38,38,0.35);
    }

    .empty-state {
        color: #94a3b8;
        text-align: center;
        padding: 1.75rem;
        font-size: 0.95rem;
    }

    @media (max-width: 768px) {
        .layanan-shell-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .layanan-window-header {
            flex-direction: column;
            align-items: flex-start;
            gap: .75rem;
        }

        .layanan-toolbar {
            flex-direction: column;
            align-items: flex-start;
        }

        .layanan-search {
            max-width: 100%;
        }
    }
</style>

<div class="layanan-macos-page">
    <div class="layanan-shell">
        <div class="layanan-shell-header">
            <div class="layanan-shell-title">
                <span class="layanan-eyebrow">Service Catalog</span>
                <h3>Layanan Deva Laundry</h3>
                <p>Atur dan kelola layanan yang tampil untuk pelanggan dengan tampilan premium.</p>
            </div>
            <a href="{{ route('layanan.create') }}" class="btn-mac-primary">
                <span class="btn-mac-primary-icon">+</span>
                <span>Layanan Baru</span>
            </a>
        </div>

        <div class="layanan-window">
            <div class="layanan-window-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="mac-traffic-lights">
                        <span class="mac-traffic-light red"></span>
                        <span class="mac-traffic-light yellow"></span>
                        <span class="mac-traffic-light green"></span>
                    </div>
                    <span class="layanan-window-title">Layanan Laundry • Deva Studio</span>
                </div>
            </div>

            <div class="layanan-window-body">
                <div class="layanan-toolbar">
                    <div class="layanan-count">
                        {{ $services->count() }} layanan aktif terdaftar.
                    </div>
                    <div class="layanan-search">
                        <span class="layanan-search-icon"></span>
                        <input type="text" id="serviceSearch" class="form-control" placeholder="Cari nama atau deskripsi layanan...">
                    </div>
                </div>

                {{-- Notifikasi sukses (akan ditampilkan via modal pop-up) --}}
                @if(session('success'))
                    <div id="layananSuccessMessage" class="d-none">{{ session('success') }}</div>
                @endif

                {{-- Jika ada error --}}
                @if($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Tabel layanan --}}
                <div class="table-responsive layanan-table-wrapper">
                    <table class="table table-hover align-middle text-center layanan-table">
                        <thead>
                            <tr>
                                <th width="6%">No</th>
                                <th>Nama Layanan</th>
                                <th>Deskripsi</th>
                                <th width="22%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($services as $service)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="layanan-name">{{ $service->name }}</td>
                                    <td>
                                        <p class="layanan-description mb-0">
                                            {{ $service->description ?? '-' }}
                                        </p>
                                    </td>
                                    <td>
                                        <div class="layanan-actions">
                                            <a href="{{ route('layanan.edit', $service->id) }}" class="btn-chip btn-chip-edit">
                                                <span>Edit</span>
                                            </a>
                                            <form action="{{ route('layanan.destroy', $service->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-chip btn-chip-delete"
                                                    onclick="return confirm('Yakin ingin menghapus layanan ini?')">
                                                <span>Hapus</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="empty-state">
                                        <em>Belum ada layanan yang ditambahkan.</em>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    {{-- SweetAlert2 untuk modal notifikasi layanan --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // SweetAlert success
            var msgEl = document.getElementById('layananSuccessMessage');
            if (!msgEl) return;
            var text = msgEl.textContent || msgEl.innerText || 'Layanan berhasil ditambahkan.';

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: text,
                confirmButtonText: 'OK',
                confirmButtonColor: '#0a84ff',
                timer: 2200,
                timerProgressBar: true
            });
        });
    </script>
@endif

<script>
    // Pencarian client-side untuk tabel layanan
    document.addEventListener('DOMContentLoaded', function () {
        var searchInput = document.getElementById('serviceSearch');
        if (!searchInput) return;

        var rows = Array.prototype.slice.call(
            document.querySelectorAll('.layanan-table tbody tr')
        );

        searchInput.addEventListener('input', function () {
            var q = (this.value || '').toLowerCase().trim();

            rows.forEach(function (row) {
                var nameEl = row.querySelector('.layanan-name');
                var descEl = row.querySelector('.layanan-description');

                var name = nameEl ? (nameEl.textContent || '').toLowerCase() : '';
                var desc = descEl ? (descEl.textContent || '').toLowerCase() : '';

                if (!q) {
                    row.style.display = '';
                } else {
                    row.style.display = (name.indexOf(q) !== -1 || desc.indexOf(q) !== -1)
                        ? ''
                        : 'none';
                }
            });
        });
    });
</script>
@endsection
