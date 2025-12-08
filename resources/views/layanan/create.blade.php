@extends('layouts.app')

@section('content')
<style>
    .layanan-create-shell {
        max-width: 920px;
        margin: 32px auto 64px;
        padding: 0 16px;
    }

    .layanan-create-window {
        border-radius: 32px;
        background: #ffffff;
        border: 1px solid rgba(226,232,240,0.9);
        box-shadow: 0 26px 70px rgba(15,23,42,0.16);
        padding: 26px 26px 24px;
    }

    .layanan-create-header {
        margin-bottom: 20px;
    }

    .layanan-create-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 4px 11px;
        border-radius: 999px;
        background: rgba(15,23,42,0.03);
        border: 1px solid rgba(148,163,184,0.35);
        font-size: 0.78rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #6b7280;
        margin-bottom: 6px;
    }

    .layanan-create-pill-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34,197,94,0.25);
    }

    .layanan-create-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0b1c4d;
        margin-bottom: 4px;
    }

    .layanan-create-subtitle {
        font-size: 0.95rem;
        color: #4b5563;
        margin-bottom: 0;
    }

    .layanan-create-grid {
        display: grid;
        grid-template-columns: minmax(0,1fr);
        gap: 18px;
        margin-top: 18px;
        margin-bottom: 12px;
    }

    .layanan-create-label {
        font-size: 0.88rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 4px;
    }

    .layanan-create-input,
    .layanan-create-textarea {
        border-radius: 14px;
        border: 1px solid rgba(148,163,184,0.55);
        padding: 10px 12px;
        font-size: 0.94rem;
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.9),
            0 10px 22px rgba(15,23,42,0.06);
    }

    .layanan-create-input:focus,
    .layanan-create-textarea:focus {
        border-color: rgba(37,99,235,0.9);
        box-shadow:
            0 0 0 1px rgba(191,219,254,0.9),
            0 18px 38px rgba(37,99,235,0.25);
    }

    .layanan-create-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 6px;
        gap: 10px;
    }

    .btn-ghost {
        border-radius: 999px;
        padding: 8px 18px;
        border: 1px solid rgba(148,163,184,0.8);
        background: transparent;
        color: #111827;
        font-weight: 600;
        text-decoration: none;
    }

    .btn-ghost:hover {
        background: rgba(15,23,42,0.03);
        color: #111827;
    }

    .btn-primary-mac {
        border-radius: 999px;
        padding: 9px 22px;
        font-weight: 700;
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border: none;
        color: #f9fafb;
        box-shadow:
            0 14px 30px rgba(37,99,235,0.35),
            0 0 0 1px rgba(59,130,246,0.6);
    }

    .btn-primary-mac:hover {
        background: linear-gradient(135deg, #1d4ed8, #1d4ed8);
        color: #ffffff;
    }

    @media (max-width: 768px) {
        .layanan-create-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .layanan-create-footer .btn-ghost,
        .layanan-create-footer .btn-primary-mac {
            width: 100%;
            justify-content: center;
            text-align: center;
        }
    }
</style>

<div class="layanan-create-shell">
    <div class="layanan-create-window">
        <div class="layanan-create-header">
            <div class="layanan-create-pill">
                <span class="layanan-create-pill-dot"></span>
                LAYANAN
            </div>
            <div class="layanan-create-title">Tambah layanan baru</div>
            <p class="layanan-create-subtitle">
                Lengkapi nama dan deskripsi singkat layanan agar tampil rapi di katalog.
            </p>
        </div>

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan error --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('layanan.store') }}" method="POST">
            @csrf

            <div class="layanan-create-grid">
                <div>
                    <label for="name" class="layanan-create-label">Nama Layanan</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control layanan-create-input @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Contoh: Cuci Kering, Setrika, Laundry Kilat"
                        required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="description" class="layanan-create-label">Deskripsi</label>
                    <textarea
                        id="description"
                        name="description"
                        class="form-control layanan-create-textarea @error('description') is-invalid @enderror"
                        rows="3"
                        placeholder="Tambahkan deskripsi singkat (opsional)...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Kolom harga telah dihapus --}}

            <div class="layanan-create-footer">
                <a href="{{ route('layanan.index') }}" class="btn-ghost">
                    Kembali ke daftar layanan
                </a>
                <button type="submit" class="btn-primary-mac">
                    Simpan layanan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

