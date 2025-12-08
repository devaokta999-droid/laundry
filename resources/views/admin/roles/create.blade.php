@extends('layouts.app')

@section('content')
<style>
    .roles-create-shell {
        max-width: 920px;
        margin: 32px auto 64px;
        padding: 0 16px;
    }

    .roles-create-window {
        border-radius: 32px;
        background: #ffffff;
        border: 1px solid rgba(226,232,240,0.9);
        box-shadow: 0 26px 70px rgba(15,23,42,0.16);
        padding: 26px 26px 24px;
    }

    .roles-create-header {
        margin-bottom: 20px;
    }

    .roles-create-pill {
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

    .roles-create-pill-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34,197,94,0.25);
    }

    .roles-create-title {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0b1c4d;
        margin-bottom: 4px;
    }

    .roles-create-subtitle {
        font-size: 0.95rem;
        color: #4b5563;
        margin-bottom: 0;
    }

    .roles-create-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
        gap: 18px 24px;
        margin-top: 18px;
        margin-bottom: 12px;
    }

    .roles-create-grid-full {
        grid-column: 1 / -1;
    }

    .roles-create-label {
        font-size: 0.88rem;
        font-weight: 600;
        color: #111827;
        margin-bottom: 4px;
    }

    .roles-create-input,
    .roles-create-select {
        border-radius: 14px;
        border: 1px solid rgba(148,163,184,0.55);
        padding: 10px 12px;
        font-size: 0.94rem;
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.9),
            0 10px 22px rgba(15,23,42,0.06);
    }

    .roles-create-input:focus,
    .roles-create-select:focus {
        border-color: rgba(37,99,235,0.9);
        box-shadow:
            0 0 0 1px rgba(191,219,254,0.9),
            0 18px 38px rgba(37,99,235,0.25);
    }

    .roles-create-footer {
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
        .roles-create-grid {
            grid-template-columns: minmax(0,1fr);
        }

        .roles-create-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .roles-create-footer .btn-ghost,
        .roles-create-footer .btn-primary-mac {
            width: 100%;
            justify-content: center;
        }
    }
</style>
<div class="roles-create-shell">
    <div class="roles-create-window">
        <div class="roles-create-header">
            <div class="roles-create-pill">
                <span class="roles-create-pill-dot"></span>
                ROLE & PERMISSION
            </div>
            <div class="roles-create-title">Tambah user baru</div>
            <p class="roles-create-subtitle">
                Lengkapi data di bawah untuk menambahkan user baru beserta rolenya.
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

    <form method="POST" action="{{ route('admin.roles.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="roles-create-grid">
            <div>
                <label class="roles-create-label">Nama</label>
                <input type="text" name="name" class="form-control roles-create-input" value="{{ old('name') }}" required>
            </div>

            <div>
                <label class="roles-create-label">Email</label>
                <input type="email" name="email" class="form-control roles-create-input" value="{{ old('email') }}" required>
            </div>

            <div class="roles-create-grid-full">
                <label class="roles-create-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="passwordField" class="form-control roles-create-input" required>
                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                        <span id="iconShow">Show</span>
                        <span id="iconHide" style="display:none;">Hide</span>
                    </button>
                </div>
                <div class="form-text">Minimal 6 karakter. Gunakan tombol Show/Hide untuk melihat password.</div>
            </div>

            <div>
                <label class="roles-create-label">Foto Profil</label>
                <input type="file" name="avatar" class="form-control roles-create-input" accept="image/*">
                <div class="form-text">Pilih file gambar (opsional) untuk foto profil pada daftar role.</div>
            </div>

            <div>
                <label class="roles-create-label">Role</label>
                <select name="role" class="form-select roles-create-select" required>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>
                            {{ ucfirst($role) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="roles-create-footer">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-ghost">Kembali</a>
            <button type="submit" class="btn btn-primary-mac">Simpan user</button>
        </div>
    </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const field = document.getElementById('passwordField');
        const toggle = document.getElementById('togglePassword');
        const iconShow = document.getElementById('iconShow');
        const iconHide = document.getElementById('iconHide');

        if (field && toggle) {
            toggle.addEventListener('click', function () {
                const isHidden = field.type === 'password';
                field.type = isHidden ? 'text' : 'password';
                iconShow.style.display = isHidden ? 'none' : 'inline';
                iconHide.style.display = isHidden ? 'inline' : 'none';
            });
        }
    });
</script>
@endpush
