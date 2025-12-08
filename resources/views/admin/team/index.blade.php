@extends('layouts.app')

@section('content')
<style>
    .team-shell {
        max-width: 1920px;
        width: 100%;
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

    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 18px;
        margin-top: 1.1rem;
    }
    .team-card {
        border-radius: 20px;
        background: radial-gradient(circle at top left, #ffffff, #e5edff);
        border: 1px solid rgba(209,213,219,0.9);
        box-shadow: 0 20px 45px rgba(15,23,42,0.18);
        padding: 16px 16px 14px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        position: relative;
        overflow: hidden;
    }
    .team-card::before{
        content:"";
        position:absolute;
        inset:-30%;
        background: radial-gradient(circle at 0 0, rgba(239,246,255,0.9), transparent 60%);
        opacity: 0.6;
        pointer-events:none;
    }
    .team-card-inner{
        position:relative;
        z-index:1;
        display:flex;
        flex-direction:column;
        gap:10px;
        height:100%;
    }
    .team-card-header{
        display:flex;
        gap:12px;
        align-items:center;
    }
    .team-avatar {
        width: 60px;
        height: 60px;
        border-radius: 999px;
        object-fit: cover;
        box-shadow: 0 12px 28px rgba(15,23,42,0.26);
        border: 2px solid #ffffff;
        background: linear-gradient(135deg,#4f46e5,#0ea5e9);
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:700;
        color:#f9fafb;
        font-size:1.2rem;
    }
    .team-card-title {
        font-weight: 700;
        font-size: 1rem;
        color:#0f172a;
        margin-bottom: 2px;
    }
    .badge-role {
        display:inline-flex;
        align-items:center;
        padding: 0.2rem 0.7rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 600;
        background: rgba(59,130,246,0.08);
        color:#1d4ed8;
    }
    .team-card-desc{
        font-size: 0.85rem;
        color:#4b5563;
        line-height:1.5;
    }
    .team-card-footer{
        margin-top:auto;
        display:flex;
        justify-content:flex-end;
        gap:8px;
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
        background: rgba(255,255,255,0.8);
    }
    .btn-outline-secondary:hover{
        background-color:#e5e7eb;
        color:#111827;
    }
    .btn-outline-danger{
        border-color: rgba(248,113,113,0.9);
        color:#b91c1c;
        background: rgba(254,242,242,0.9);
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

    <div class="team-grid">
        @forelse($members as $m)
            <div class="team-card">
                <div class="team-card-inner">
                    <div class="team-card-header">
                        @php
                            $initial = strtoupper(mb_substr($m->name, 0, 1, 'UTF-8'));
                        @endphp
                        @if($m->photo)
                            <img src="{{ asset('images/'.$m->photo) }}" alt="{{ $m->name }}" class="team-avatar">
                        @else
                            <div class="team-avatar">{{ $initial }}</div>
                        @endif
                        <div>
                            <div class="team-card-title">{{ $m->name }}</div>
                            <span class="badge-role">{{ $m->position }}</span>
                        </div>
                    </div>
                    <div class="team-card-desc">
                        {{ \Illuminate\Support\Str::limit($m->description, 100) }}
                    </div>
                    <div class="team-card-footer">
                        <a href="{{ route('admin.team.edit', $m) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                        <form action="{{ route('admin.team.destroy', $m) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin ingin menghapus anggota tim ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="w-100 text-center text-muted py-4">
                Belum ada anggota tim.
            </div>
        @endforelse
    </div>
        </div>
    </div>
</div>
@endsection
