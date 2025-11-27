@extends('layouts.app')

@section('content')
<style>
    .team-shell {
        max-width: 1100px;
        margin: 32px auto 40px;
    }
    .team-window {
        border-radius: 22px;
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 24px 60px rgba(15,23,42,0.16);
        border: 1px solid rgba(255,255,255,0.8);
        overflow: hidden;
    }
    .team-window-header {
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
    .team-window-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #4b5563;
    }
    .team-window-body {
        padding: 1.4rem 1.4rem 1.6rem;
    }
    .team-header-main {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .team-header-main h3 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #007aff;
        margin-bottom: 2px;
    }
    .team-header-main p {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 0;
    }
    .team-table thead th {
        border-bottom: none;
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        font-weight: 600;
        font-size: 0.85rem;
        color: #4b5563;
    }
    .team-table tbody tr:hover {
        background-color: rgba(0,122,255,0.03);
    }
</style>
<div class="team-shell">
    <div class="team-window">
        <div class="team-window-header">
            <div class="traffic-lights">
                <span class="traffic-light red"></span>
                <span class="traffic-light yellow"></span>
                <span class="traffic-light green"></span>
            </div>
            <div class="team-window-title">Tim Profesional · Deva Laundry</div>
        </div>
        <div class="team-window-body">
            <div class="team-header-main">
                <div>
                    <h3>Kelola Tim Profesional</h3>
                    <p>Atur anggota tim yang tampil di halaman Tentang Kami dengan tampilan ala macOS.</p>
                </div>
                <a href="{{ route('admin.team.create') }}" class="btn btn-sm btn-primary">+ Tambah Anggota Tim</a>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <p class="text-muted mb-3">
                Hanya admin yang dapat menambah, mengedit, dan menghapus anggota tim yang tampil di halaman Tentang Kami.
            </p>

            <div class="table-responsive">
                <table class="table align-middle team-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Deskripsi Singkat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($members as $m)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($m->photo)
                                        <img src="{{ asset('images/'.$m->photo) }}" alt="{{ $m->name }}" width="60" height="60" class="rounded-circle" style="object-fit:cover;">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $m->name }}</td>
                                <td>{{ $m->position }}</td>
                                <td style="max-width: 260px;">{{ Str::limit($m->description, 80) }}</td>
                                <td>
                                    <a href="{{ route('admin.team.edit', $m) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form action="{{ route('admin.team.destroy', $m) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus anggota tim ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-muted text-center">Belum ada anggota tim.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
