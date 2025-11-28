@extends('layouts.app')

@section('content')
<style>
    .roles-shell {
        max-width: 1500px;
        margin: 32px auto 40px;
    }
    .roles-window {
        border-radius: 22px;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 24px 60px rgba(15,23,42,0.16);
        border: 1px solid rgba(255,255,255,0.8);
        overflow: hidden;
    }
    .roles-window-header {
        display: flex;
        align-items: center;
        padding: 0.6rem 1rem;
        border-bottom: 1px solid rgba(226,232,240,0.9);
        background: linear-gradient(135deg, #f5f5f7, #e5e7eb);
    }
    .traffic-lights {
        display: flex;
        gap: 0.4rem;
        margin-right: 0.8rem;
    }
    .traffic-light {
        width: 12px;
        height: 12px;
        border-radius: 999px;
        border: 1px solid rgba(0,0,0,0.08);
    }
    .traffic-light.red { background: #ff5f57; }
    .traffic-light.yellow { background: #febc2e; }
    .traffic-light.green { background: #28c840; }
    .roles-window-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #4b5563;
    }
    .roles-window-body {
        padding: 1.4rem 1.4rem 1.6rem;
    }
    .roles-header-main {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .roles-header-main h3 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #007aff;
        margin-bottom: 2px;
    }
    .roles-header-main p {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 0;
    }
    .roles-table thead th {
        border-bottom: none;
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        font-weight: 600;
        font-size: 0.85rem;
        color: #4b5563;
    }
    .roles-table tbody tr:hover {
        background-color: rgba(0,122,255,0.03);
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
                    <p>Atur akses Admin, Kasir, Karyawan, dan pengguna lain dengan gaya macOS yang rapi.</p>
                </div>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                    + Tambah User dengan Role
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <p class="text-muted mb-1" style="font-size:.9rem;">
                <strong>Ringkasan hak akses:</strong><br>
                - <strong>Admin</strong> dapat mengakses semua menu dan fitur.<br>
                - <strong>Karyawan</strong> hanya dapat mengakses menu <strong>Layanan</strong>, <strong>Nota</strong>, dan <strong>Laporan</strong>.<br>
                - <strong>Kasir</strong> hanya dapat mengakses menu <strong>Nota</strong> saja.
            </p>

            <p class="text-muted mb-3">
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
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td><span class="badge bg-primary text-uppercase">{{ $user->role ?? '-' }}</span></td>
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
                                        <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('admin.roles.edit', $user) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form method="POST" action="{{ route('admin.roles.destroy', $user) }}" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
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
