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
        padding: 2.75rem 0.75rem;
    }

    .layanan-macos-page {
        padding: 56px 0 64px;
        background: radial-gradient(circle at top left, #eef2ff, #e0f2fe 40%, #f5f5ff 100%);
        min-height: calc(100vh - 70px);
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .layanan-shell {
        width: min(1920px, calc(100% - 24px));
        margin: 0 auto;
        padding: 0 4px;
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
        border-radius: 36px;
        background: rgba(255,255,255,0.98);
        border: 1px solid rgba(148, 163, 184, 0.35);
        box-shadow: 0 38px 90px rgba(15, 23, 42, 0.30);
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

    .layanan-grid-wrapper {
        border-radius: 24px;
        border: 1px solid rgba(226, 232, 240, 0.95);
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(244, 247, 255, 0.98));
        box-shadow: 0 24px 50px rgba(15, 23, 42, 0.18);
        padding: 1.4rem 1.4rem 1.6rem;
    }

    .layanan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 1rem 1.25rem;
    }

    .layanan-card {
        position: relative;
        border-radius: 20px;
        background: #ffffff;
        border: 1px solid rgba(226, 232, 240, 0.9);
        box-shadow:
            0 16px 40px rgba(15, 23, 42, 0.12),
            0 0 0 1px rgba(255,255,255,0.8);
        padding: 0.95rem 1.05rem 0.9rem;
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
    }

    .layanan-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .layanan-name {
        font-weight: 700;
        color: #0b204a;
        font-size: 1rem;
        margin-bottom: 0.15rem;
    }

    .layanan-description {
        margin: 0;
        font-size: 0.86rem;
        color: #4b5268;
    }

    .layanan-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.35rem;
        margin-top: 0.45rem;
        flex-wrap: wrap;
    }

    .btn-chip {
        border-radius: 999px;
        border: 1px solid transparent;
        font-size: 0.85rem;
        padding: 6px 16px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        background: transparent;
        box-shadow: none;
        transition: all 0.2s ease;
    }

    .btn-chip-edit {
        border-color: #2563eb;
        color: #1d4ed8;
    }

    .btn-chip-edit:hover {
        background: rgba(37, 99, 235, 0.06);
        color: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.25);
    }

    .btn-chip-delete {
        border-color: #ef4444;
        color: #b91c1c;
    }

    .btn-chip-delete:hover {
        background: rgba(239, 68, 68, 0.06);
        color: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 0 0 1px rgba(239, 68, 68, 0.25);
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

                {{-- Daftar layanan (grid card Apple-style) --}}
                <div class="layanan-grid-wrapper">
                    @if($services->isEmpty())
                        <div class="empty-state">
                            <em>Belum ada layanan yang ditambahkan.</em>
                        </div>
                    @else
                        <div class="layanan-grid">
                            @foreach ($services as $service)
                                <div class="layanan-card">
                                    <div class="layanan-card-header">
                                        <div>
                                            <div class="layanan-name">{{ $service->name }}</div>
                                            <p class="layanan-description mb-0">
                                                {{ $service->description ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
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
                                </div>
                            @endforeach
                        </div>
                    @endif
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

        var cards = Array.prototype.slice.call(
            document.querySelectorAll('.layanan-card')
        );

        searchInput.addEventListener('input', function () {
            var q = (this.value || '').toLowerCase().trim();

            cards.forEach(function (card) {
                var nameEl = card.querySelector('.layanan-name');
                var descEl = card.querySelector('.layanan-description');

                var name = nameEl ? (nameEl.textContent || '').toLowerCase() : '';
                var desc = descEl ? (descEl.textContent || '').toLowerCase() : '';

                if (!q) {
                    card.style.display = '';
                } else {
                    card.style.display = (name.indexOf(q) !== -1 || desc.indexOf(q) !== -1)
                        ? ''
                        : 'none';
                }
            });
        });
    });
</script>
@endsection
