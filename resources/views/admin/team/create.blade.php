@extends('layouts.app')

@section('content')
<style>
    .team-create-shell {
        max-width: 920px;
        margin: 32px auto 64px;
        padding: 0 16px;
    }

    .team-create-window {
        border-radius: 32px;
        background: #ffffff;
        border: 1px solid rgba(226,232,240,0.9);
        box-shadow: 0 26px 70px rgba(15,23,42,0.16);
        padding: 26px 26px 24px;
    }

    .team-create-header {
        margin-bottom: 20px;
    }

    .team-create-pill {
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

    .team-create-pill-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34,197,94,0.25);
    }

    .team-create-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0b1c4d;
        margin-bottom: 4px;
    }

    .team-create-subtitle {
        font-size: 0.95rem;
        color: #4b5563;
        margin-bottom: 0;
    }

    .team-create-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(0, 1fr);
        gap: 18px 24px;
        margin-top: 18px;
        margin-bottom: 12px;
    }

    .team-create-grid-full {
        grid-column: 1 / -1;
    }

    .team-create-label {
        font-size: 0.88rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 4px;
    }

    .team-create-input,
    .team-create-textarea {
        border-radius: 14px;
        border: 1px solid rgba(148,163,184,0.55);
        padding: 10px 12px;
        font-size: 0.94rem;
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.9),
            0 10px 22px rgba(15,23,42,0.06);
    }

    .team-create-input:focus,
    .team-create-textarea:focus {
        border-color: rgba(37,99,235,0.9);
        box-shadow:
            0 0 0 1px rgba(191,219,254,0.9),
            0 18px 38px rgba(37,99,235,0.25);
    }

    .team-create-footer {
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
    }

    .btn-ghost:hover {
        background: rgba(15,23,42,0.03);
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
        .team-create-grid {
            grid-template-columns: minmax(0,1fr);
        }

        .team-create-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .team-create-footer .btn-ghost,
        .team-create-footer .btn-primary-mac {
            width: 100%;
            justify-content: center;
        }
    }
</style>
<div class="team-create-shell">
    <div class="team-create-window">
        <div class="team-create-header">
            <div class="team-create-pill">
                <span class="team-create-pill-dot"></span>
                TEAM PROFESIONAL
            </div>
            <div class="team-create-title">Tambah anggota tim</div>
            <p class="team-create-subtitle">
                Isi profil singkat anggota tim agar tampil rapi di halaman depan.
            </p>
        </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.team.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="team-create-grid">
            <div>
                <label class="team-create-label">Nama</label>
                <input type="text" name="name" class="form-control team-create-input" value="{{ old('name') }}" required>
            </div>

            <div>
                <label class="team-create-label">Jabatan</label>
                <input type="text" name="position" class="form-control team-create-input" value="{{ old('position') }}" required>
            </div>

            <div>
                <label class="team-create-label">Foto Profil</label>
                <input type="file" name="photo" class="form-control team-create-input" accept="image/*">
                <div class="form-text">Pilih gambar (jpg, jpeg, png, webp, maks. 2MB).</div>
            </div>

            <div class="team-create-grid-full">
                <label class="team-create-label">Deskripsi Singkat</label>
                <textarea name="description" class="form-control team-create-textarea" rows="3">{{ old('description') }}</textarea>
            </div>
        </div>

        <div class="team-create-footer">
            <a href="{{ route('admin.team.index') }}" class="btn btn-ghost">Kembali</a>
            <button type="submit" class="btn btn-primary-mac">Simpan anggota tim</button>
        </div>
    </form>
    </div>
</div>
@endsection
