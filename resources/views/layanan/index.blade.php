@extends('layouts.app')

@section('content')
<style>
    :root {
        --apple-card: rgba(255, 255, 255, 0.95);
        --apple-border: rgba(225, 230, 244, 0.9);
        --apple-glow: rgba(15, 23, 42, 0.08);
        --apple-muted: #6b7280;
        --apple-accent: #0b74ff;
        --apple-soft: rgba(255, 255, 255, 0.4);
    }

    #page-content {
        padding: 2.5rem 1.75rem;
    }

    .layanan-macos-page {
        padding: 48px 0 56px;
        background: linear-gradient(135deg, #f6f7fb, #eef2ff);
        min-height: calc(100vh - 70px);
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .layanan-shell {
        width: min(1750px, calc(100% - 32px));
        margin: 0 auto;
        padding: 0 12px;
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
        gap: 0.25rem;
    }

    .layanan-eyebrow {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.24em;
        color: #9aa2b9;
    }

    .layanan-shell-title h3 {
        font-weight: 800;
        letter-spacing: -0.03em;
        color: #111827;
        margin: 0;
    }

    .layanan-shell-title p {
        margin: 0;
        font-size: 0.9rem;
        color: #53617c;
    }

    .layanan-window {
        border-radius: 32px;
        background: #ffffff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        box-shadow: 0 30px 70px rgba(15, 23, 42, 0.25);
        overflow: hidden;
        position: relative;
    }

    .layanan-window-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 2rem;
        border-bottom: 1px solid rgba(226, 232, 240, 1);
        background: #fdfdfd;
    }

    .mac-traffic-lights {
        display: inline-flex;
        gap: 0.45rem;
        align-items: center;
    }

    .mac-traffic-light {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }

    .mac-traffic-light.red { background: #ff5f57; }
    .mac-traffic-light.yellow { background: #febc35; }
    .mac-traffic-light.green { background: #2fcc71; }

    .layanan-window-title {
        font-size: 0.95rem;
        color: #454f6b;
        font-weight: 600;
        letter-spacing: 0.045em;
    }

    .btn-mac-primary {
        background: linear-gradient(135deg, var(--apple-accent), #0035a8);
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.7);
        color: #fff;
        font-weight: 600;
        font-size: 0.92rem;
        padding: 10px 20px;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        text-decoration: none;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.25);
        transition: all 0.25s ease;
    }

    .btn-mac-primary:hover {
        background: linear-gradient(135deg, #3a8bff, var(--apple-accent));
        transform: translateY(-1px);
        box-shadow: 0 18px 40px rgba(10, 20, 60, 0.32);
        color: #fff;
    }

    .btn-mac-primary-icon {
        width: 18px;
        height: 18px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.2);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .layanan-window-body {
        padding: 2rem;
        background: #ffffff;
    }

    .layanan-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.4rem;
        flex-wrap: wrap;
    }

    .layanan-count {
        font-size: 0.85rem;
        color: #020202;
    }

    .layanan-search {
        position: relative;
        max-width: 280px;
        width: 100%;
    }

    .layanan-search input {
        border-radius: 999px;
        padding: 10px 14px 10px 38px;
        font-size: 0.9rem;
        border: 1px solid rgba(15, 23, 42, 0.12);
        background: #f4f7ff;
        color: #020202;
        box-shadow: inset 0 2px 6px rgba(15, 23, 42, 0.08);
    }

    .layanan-search-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .layanan-search-icon::after {
        content: "⌕";
        font-size: 0.95rem;
        color: #9ca3af;
    }

    .layanan-table-wrapper {
        border-radius: 22px;
        overflow: hidden;
        border: 1px solid rgba(226, 232, 240, 0.95);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.82), rgba(244, 247, 255, 0.95));
        box-shadow: 0 24px 50px rgba(15, 23, 42, 0.18);
    }

    .layanan-table {
        margin-bottom: 0;
        font-size: 0.94rem;
    }

    .layanan-table thead {
        background: #f8f9ff;
        border-bottom: 2px solid rgba(15, 23, 42, 0.08);
    }

    .layanan-table thead th {
        border-bottom: none;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-size: 0.74rem;
        color: #030303;
        text-align: center;
    }

    .layanan-table tbody tr {
        background: #ffffff;
        transition: background 0.2s ease;
    }

    .layanan-table tbody tr:nth-child(even) {
        background: #f4f6ff;
    }

    .layanan-table tbody tr:hover {
        background: rgba(10, 30, 120, 0.08);
    }

    .layanan-table td {
        vertical-align: middle !important;
        color: #020202;
        padding: 1rem 1.25rem;
    }

    .layanan-name {
        font-weight: 700;
        color: #0b204a;
        font-size: 1rem;
    }

    .layanan-description {
        margin: 0 auto;
        font-size: 0.86rem;
        color: #4b5268;
        max-width: 540px;
    }

    .layanan-actions {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .btn-chip {
        border-radius: 999px;
        border: 0;
        font-size: 0.85rem;
        padding: 6px 16px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: #f9fafb;
        box-shadow: 0 8px 20px rgba(15,23,42,0.2);
        transition: all 0.2s ease;
    }

    .btn-chip-edit {
        background: linear-gradient(135deg, #00c6ff, #6366f1);
    }

    .btn-chip-edit:hover {
        background: linear-gradient(135deg, #46b1ff, #a5b4fc);
        transform: translateY(-1px);
        box-shadow: 0 12px 26px rgba(63, 81, 181, 0.35);
    }

    .btn-chip-delete {
        background: linear-gradient(135deg, #fb7185, #ef4444);
    }

    .btn-chip-delete:hover {
        background: linear-gradient(135deg, #fb7185, #f43f5e);
        transform: translateY(-1px);
        box-shadow: 0 12px 26px rgba(220,38,38,0.35);
    }

    .empty-state {
        color: #94a3b8;
        text-align: center;
        padding: 1.75rem;
        font-size: 0.95rem;
    }

    @media (max-width: 1200px) {
        #page-content {
            padding: 2rem 1rem;
        }
    }

    @media (max-width: 992px) {
        .layanan-shell-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .layanan-window-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
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
                <p>Atur dan kelola layanan yang tampil untuk pelanggan.</p>
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
                    <span class="layanan-window-title">Layanan Deva Laundry</span>
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
