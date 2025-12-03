@extends('layouts.app')

@section('content')
<style>
    .team-shell {
        max-width: 1680px;
        width: 94vw;
        margin: 40px auto 56px;
        padding: 0 12px;
    }
    .team-window {
        border-radius: 22px;
        background: radial-gradient(circle at top left, rgba(255,255,255,0.98), rgba(243,244,246,0.96));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        box-shadow: 0 26px 80px rgba(15,23,42,0.20);
        border: 1px solid rgba(255,255,255,0.9);
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
    .traffic-light.red { background: #ff0d00ff; }
    .traffic-light.yellow { background: #ffae00ff; }
    .traffic-light.green { background: #00ff26ff; }
    .team-window-title {
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: .03em;
        color: #6b7280;
    }
    .team-window-body {
        padding: 1.6rem 1.6rem 1.8rem;
        background: linear-gradient(180deg, #f9fafb 0%, #f3f4f6 60%, #e5e7eb 100%);
    }
    .team-header-main {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .team-header-main h3 {
        font-size: 1.6rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 2px;
    }
    .team-header-main p {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 0;
    }
    .team-header-main .btn-primary{
        border-radius: 999px;
        padding: 0.55rem 1.2rem;
        font-weight: 600;
        background: linear-gradient(135deg, #007aff, #0f9cf5);
        border: none;
        box-shadow: 0 14px 30px rgba(37,99,235,0.35);
    }
    .team-header-main .btn-primary:hover{
        filter: brightness(1.05);
        box-shadow: 0 18px 42px rgba(37,99,235,0.4);
    }
    .team-table thead th {
        border-bottom: none;
        background: linear-gradient(135deg, #f5f5f7, #e5e7eb);
        font-weight: 600;
        font-size: 0.85rem;
        color: #6b7280;
        border-top: none;
    }
    .team-table tbody td {
        border-top: 1px solid rgba(226,232,240,0.9);
        font-size: 0.9rem;
        vertical-align: middle;
    }
    .team-table tbody tr:hover {
        background-color: rgba(15,23,42,0.02);
    }
    .team-avatar {
        width: 64px;
        height: 64px;
        border-radius: 999px;
        object-fit: cover;
        box-shadow: 0 10px 25px rgba(15,23,42,0.25);
        border: 2px solid #ffffff;
    }
    .badge-role {
        display:inline-flex;
        align-items:center;
        padding: 0.25rem 0.6rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: rgba(15,23,42,0.04);
        color:#111827;
    }
    .btn-outline-secondary, .btn-outline-danger{
        border-radius: 999px;
        padding: 0.28rem 0.9rem;
        font-size: 0.8rem;
        font-weight: 600;
    }
    .btn-outline-secondary{
        border-color: rgba(148,163,184,0.8);
        color:#111827;
    }
    .btn-outline-secondary:hover{
        background-color:#e5e7eb;
        color:#111827;
    }
    .btn-outline-danger{
        border-color: rgba(248,113,113,0.9);
        color:#b91c1c;
    }
    .btn-outline-danger:hover{
        background-color:#fee2e2;
        color:#7f1d1d;
    }
    @media (max-width: 992px){
        .team-header-main{
            flex-direction: column;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .team-shell{
            width: 100vw;
            margin-top: 24px;
        }
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
                    <p>Atur anggota tim yang tampil di halaman Tentang Kami.</p>
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
