@extends('layouts.app')

@section('content')
<style>
    /* 🎨 Tampilan elegan macOS */
    .mac-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(18px);
        border-radius: 18px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        transition: all 0.3s ease;
    }

    .mac-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 36px rgba(0, 0, 0, 0.1);
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
        background: linear-gradient(135deg, #007aff, #005ce6);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 10px;
        padding: 8px 14px;
        transition: all 0.2s ease-in-out;
    }

    .btn-macos:hover {
        background: linear-gradient(135deg, #339cff, #0070f3);
        transform: translateY(-2px);
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
        color: #777;
        text-align: center;
        padding: 1.5rem;
    }
</style>

<div class="container py-5">
    <div class="mac-card">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold" style="color:#007aff;">Daftar Layanan Deva Laundry</h3>
            <a href="{{ route('layanan.create') }}" class="btn-macos">
                + Tambah Layanan
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
                        <th>Harga</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($services as $service)
                    <tr class="text-center">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $service->name }}</td>
                        <td class="text-muted">{{ $service->description ?? '-' }}</td>
                        <td><strong>Rp {{ number_format($service->price, 0, ',', '.') }}</strong></td>
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
                        <td colspan="5" class="empty-state">
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
