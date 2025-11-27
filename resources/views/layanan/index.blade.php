@extends('layouts.app')

@section('content')
<style>
    /* 🎨 Tampilan elegan macOS */
    .mac-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        border-radius: 22px;
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        padding: 2.2rem 2rem;
        transition: all 0.28s ease;
    }

    .mac-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.16);
    }

    .table {
        border-radius: 14px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.75);
    }

    th {
        background: linear-gradient(135deg, #007aff, #005ce6);
        color: #fff;
        font-weight: 600;
    }

    td {
        vertical-align: middle !important;
    }

    .btn-macos {
        background: linear-gradient(135deg, #0a84ff, #007aff);
        border: 1px solid rgba(255, 255, 255, 0.7);
        color: white;
        font-weight: 600;
        border-radius: 999px;
        padding: 9px 18px;
        box-shadow: 0 10px 24px rgba(10, 132, 255, 0.35);
        transition: all 0.18s ease-in-out;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .btn-macos:hover {
        background: linear-gradient(135deg, #3b9dff, #0a84ff);
        transform: translateY(-2px);
        box-shadow: 0 16px 30px rgba(10, 132, 255, 0.45);
    }

    .btn-edit {
        background: linear-gradient(135deg, #ffb13d, #ff9500);
        border: none;
        color: white;
        border-radius: 8px;
        padding: 6px 10px;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #ffc862, #ff9f0a);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ff3b30, #ff453a);
        border: none;
        color: white;
        border-radius: 8px;
        padding: 6px 10px;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #ff5e57, #ff3b30);
    }

    .empty-state {
        color: #94a3b8;
        text-align: center;
        padding: 1.75rem;
        font-size: 0.95rem;
    }
</style>

<div class="container py-5">
    <div class="mac-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <div class="text-uppercase text-muted" style="letter-spacing:.12em;font-size:.7rem;">Manajemen Layanan</div>
                <h3 class="fw-bold mb-1" style="color:#007aff;">Layanan Deva Laundry</h3>
                <p class="text-muted mb-0" style="font-size:.9rem;">Atur jenis layanan yang akan muncul untuk pelanggan.</p>
            </div>
            <a href="{{ route('layanan.create') }}" class="btn-macos d-inline-flex align-items-center gap-2">
                <span class="rounded-circle bg-white bg-opacity-25 d-inline-flex align-items-center justify-content-center" style="width:20px;height:20px;font-size:1rem;">+</span>
                <span>Layanan Baru</span>
            </a>
        </div>

        {{-- ✅ Notifikasi sukses --}}
        @if(session('success'))
            <div class="alert alert-success text-center fw-medium">
                {{ session('success') }}
            </div>
        @endif

        {{-- ⚠️ Jika ada error --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 📋 Tabel layanan --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="text-center">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Layanan</th>
                        <th>Deskripsi</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $service->name }}</td>
                        <td class="text-muted">{{ $service->description ?? '-' }}</td>
                        <td>
                            <a href="{{ route('layanan.edit', $service->id) }}" class="btn-edit me-2">
                                Edit
                            </a>
                            <form action="{{ route('layanan.destroy', $service->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn-delete" onclick="return confirm('Yakin ingin menghapus layanan ini?')">
                                    Hapus
                                </button>
                            </form>
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
@endsection
