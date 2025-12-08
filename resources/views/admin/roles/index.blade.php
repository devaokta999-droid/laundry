@extends('layouts.app')

@section('content')
<style>
    :root {
        --apple-accent: #0a74ff;
        --apple-glass: rgba(255, 255, 255, 0.94);
        --apple-border: rgba(229, 233, 244, 0.95);
        --apple-shadow: 0 30px 80px rgba(15, 23, 42, 0.22);
        --apple-muted: #6b7280;
        --apple-bg: linear-gradient(135deg, #f6f7fb, #eef2ff);
    }

    .roles-shell {
        max-width: none;
        width: 100%;
        margin: 32px 0 64px;
        padding: 0 8px;
    }

    .roles-window {
        width: 100%;
        border-radius: 34px;
        background: rgba(255, 255, 255, 0.96);
        border: 1px solid rgba(148, 163, 184, 0.35);
        box-shadow:
            0 28px 80px rgba(15, 23, 42, 0.22),
            0 0 0 1px rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(26px);
        -webkit-backdrop-filter: blur(26px);
        overflow: hidden;
        position: relative;
    }

    .roles-window-header {
        position: relative;
        display: flex;
        align-items: center;
        padding: 0.8rem 1.2rem;
        border-bottom: 1px solid rgba(226, 232, 240, 0.95);
        background: var(--apple-bg);
    }

    .traffic-lights {
        display: flex;
        gap: 0.45rem;
        margin-right: 1rem;
    }

    .traffic-light {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }

    .traffic-light.red { background: #ff0d00ff; }
    .traffic-light.yellow { background: #ffaa00ff; }
    .traffic-light.green { background: #00ff6aff; }

    .roles-window-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #000000ff;
        letter-spacing: 0.05em;
    }

    .roles-window-body {
        position: relative;
        padding: 2.4rem 2.6rem 2.8rem;
        background: #ffffff;
        border-bottom-left-radius: 32px;
        border-bottom-right-radius: 32px;
    }

    .roles-window-body,
    .roles-window-body p,
    .roles-window-body span {
        color: #020202;
    }

    .roles-header-main {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
    }

    .roles-header-main h3 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0b1c4d;
        margin-bottom: 0.25rem;
    }

    .roles-header-main p {
        font-size: 0.95rem;
        color: #4b5568;
        margin: 0;
        max-width: 460px;
    }

    .roles-status {
        font-size: 0.8rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: var(--apple-muted);
    }

    .roles-summary {
        font-size: 0.9rem;
        color: #4b5568;
        margin-bottom: 1.25rem;
        line-height: 1.5;
    }

    .roles-summary strong {
        color: #0b1c4d;
    }

    .roles-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.25rem 0.85rem;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.03);
        border: 1px solid rgba(148, 163, 184, 0.35);
        font-size: 0.74rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #6b7280;
        margin-bottom: 0.55rem;
    }

    .roles-pill-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.28);
    }

    .roles-note {
        font-size: 0.92rem;
        color: #050505;
        margin-bottom: 0.85rem;
        line-height: 1.5;
    }

    .btn-apple {
        border-radius: 999px;
        padding: 0.65rem 1.8rem;
        background: linear-gradient(145deg, #1c82ff, #0a5bfa);
        color: #ffffff;
        font-weight: 700;
        border: none;
        box-shadow: 0 16px 30px rgba(10, 77, 173, 0.35);
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        letter-spacing: 0.04em;
        text-transform: none;
        font-size: 0.95rem;
    }

    .btn-apple:hover {
        transform: translateY(-1px);
        box-shadow: 0 22px 36px rgba(10, 45, 107, 0.4);
        background: linear-gradient(145deg, #2a8fff, #0f6fff);
    }

    .btn-apple span {
        color: #ffffff;
        text-decoration: none;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.25);
    }

    .btn-apple-ghost {
        border-radius: 999px;
        padding: 0.55rem 1.2rem;
        background: rgba(15, 23, 42, 0.05);
        color: #0b1c4d;
        border: 1px solid rgba(15, 23, 42, 0.12);
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .roles-table-wrapper {
        border-radius: 20px;
        border: 1px solid rgba(15, 23, 42, 0.1);
        overflow: hidden;
        background: #ffffff;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
    }

    .roles-table {
        margin-bottom: 0;
        font-size: 0.94rem;
    }

    .roles-table thead {
        background: #ffffff;
        border-bottom: 2px solid rgba(15, 23, 42, 0.08);
    }

    .roles-table thead th {
        text-transform: uppercase;
        font-size: 0.74rem;
        letter-spacing: 0.14em;
        color: #030303;
        border-bottom: none;
        text-align: center;
    }

    .roles-table tbody tr {
        background: #ffffff;
        box-shadow: inset 0 -1px 0 rgba(15, 23, 42, 0.05);
        transition: background 0.2s ease;
    }

    .roles-table tbody tr:nth-child(even) {
        background: #f1f4ff;
    }

    .roles-table tbody tr:hover {
        background: rgba(30, 110, 255, 0.07);
    }

    .roles-table td {
        vertical-align: middle !important;
        padding: 1rem 1.2rem;
        text-align: center;
        color: #020202;
    }

    .roles-table thead th:nth-child(2),
    .roles-table tbody td:nth-child(2),
    .roles-table thead th:nth-child(3),
    .roles-table tbody td:nth-child(3) {
        text-align: left;
    }

    .roles-table .roles-avatar-wrap {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        justify-content: center;
    }

    .roles-avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 999px;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #dbe4ff, #cbd5f5);
        font-size: 0.85rem;
        font-weight: 700;
        color: #1f2b5b;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.15);
    }

    .roles-avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .roles-table select.form-select {
        min-width: 150px;
        border-radius: 12px;
        border: 1px solid rgba(15, 23, 42, 0.15);
        box-shadow: inset 0 1px 4px rgba(15, 23, 42, 0.07);
        font-size: 0.85rem;
        padding-right: 1.25rem;
        background: rgba(255, 255, 255, 0.9);
    }

    .roles-actions {
        display: flex;
        justify-content: center;
        gap: 0.35rem;
        flex-wrap: wrap;
    }

    .badge-apple {
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        background: rgba(10, 116, 255, 0.08);
        color: #0b4fbd;
        font-weight: 600;
        letter-spacing: 0.05em;
        font-size: 0.78rem;
    }

    .small-ghost-btn {
        border-radius: 999px;
        padding: 0.3rem 0.85rem;
        background: rgba(14, 165, 233, 0.1);
        color: #0c65c7;
        font-weight: 600;
        border: 1px solid rgba(14, 165, 233, 0.35);
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .small-ghost-btn:hover {
        background: rgba(14, 165, 233, 0.2);
        transform: translateY(-1px);
    }

    .small-danger-btn {
        border-radius: 999px;
        padding: 0.28rem 0.75rem;
        background: rgba(248, 113, 113, 0.15);
        color: #c92b2b;
        font-weight: 600;
        border: 1px solid rgba(220, 38, 38, 0.4);
    }

    .small-danger-btn:hover {
        background: rgba(248, 113, 113, 0.25);
        transform: translateY(-1px);
    }

    .small-primary-btn {
        border-radius: 999px;
        padding: 0.28rem 0.95rem;
        background: linear-gradient(135deg, #0f6fff, #1c7dff);
        color: #fff;
        font-weight: 600;
        border: none;
        box-shadow: 0 10px 24px rgba(8, 32, 135, 0.35);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .small-primary-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 14px 28px rgba(8, 32, 135, 0.45);
    }

    @media (max-width: 992px) {
        .roles-window-body {
            padding: 1.5rem 1.4rem 1.8rem;
        }

        .roles-header-main {
            flex-direction: column;
            align-items: flex-start;
        }

        .roles-table thead th,
        .roles-table td {
            font-size: 0.72rem;
        }
    }
</style>
<div class="roles-shell">
    <div class="roles-window">
        <div class="roles-window-header">
            <div class="traffic-lights">
                <span class="traffic-light red"></span>
                <span class="traffic-light yellow"></span>
                <span class="traffic-light green"></span>
            </div>
            <div class="roles-window-title">Role & Permission · Deva Laundry</div>
        </div>
        <div class="roles-window-body">
            <div class="roles-header-main">
                <div>
                    <h3>Kelola Role & Permission</h3>
                    <p>Atur akses Admin, Kasir, Karyawan, dan pengguna lain.</p>
                </div>
                <a href="{{ route('admin.roles.create') }}" class="btn-apple">
                    <span>+ Tambah User dengan Role</span>
                </a>
            </div>

            @if(session('success'))
                <div id="rolesSuccessMessage" class="d-none">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <p class="roles-note mb-1">
                <strong>Ringkasan hak akses:</strong><br>
                - <strong>Admin</strong> dapat mengakses semua menu dan fitur.<br>
                - <strong>Karyawan</strong> hanya dapat mengakses menu <strong>Layanan</strong>, <strong>Nota</strong>, dan <strong>Laporan</strong>.<br>
                - <strong>Kasir</strong> hanya dapat mengakses menu <strong>Nota</strong> saja.
            </p>

            <p class="roles-note mb-3">
                Hanya admin yang dapat mengubah role pengguna. Role menentukan hak akses menu dan fitur (Layanan, Nota, Laporan, dll).
            </p>

            <div class="table-responsive">
                <table class="table align-middle roles-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role Saat Ini</th>
                            <th>Ubah Role Cepat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="roles-avatar-circle">
                                            @if($user->avatar)
                                                <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}">
                                            @else
                                                {{ strtoupper(mb_substr($user->name,0,1,'UTF-8')) }}
                                            @endif
                                        </div>
                                        <span>{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge-apple">{{ $user->role ?? '-' }}</span></td>
                                <td>
                                    <form method="POST" action="{{ route('admin.roles.update', $user) }}" class="d-flex gap-2">
                                        @csrf
                                        @method('PUT')
                                        <select name="role" class="form-select form-select-sm" style="max-width: 160px;">
                                            @foreach($roles as $role)
                                                <option value="{{ $role }}" {{ $user->role === $role ? 'selected' : '' }}>
                                                    {{ ucfirst($role) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="small-primary-btn">Simpan</button>
                                    </form>
                                </td>
                                <td>
                                    <div class="roles-actions">
                                        <a href="{{ route('admin.roles.edit', $user) }}" class="small-ghost-btn">Edit</a>
                                        <form method="POST" action="{{ route('admin.roles.destroy', $user) }}" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="small-danger-btn">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        try {
            var msgEl = document.getElementById('rolesSuccessMessage');
            if (msgEl && window.Swal) {
                var text = msgEl.textContent || msgEl.innerText || 'User baru berhasil dibuat.';
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: text,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
        } catch (e) {}
    });
</script>
@endpush
