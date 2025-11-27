@extends('layouts.app')

@section('content')
<style>
    /* 🎨 MacOS-inspired clean style */
    .card-macos {
        background: #ffffff;
        border-radius: 18px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        padding: 2rem;
        transition: all 0.3s ease-in-out;
    }

    .card-macos:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    }

    label {
        font-weight: 500;
        color: #444;
    }

    input, textarea {
        border-radius: 10px !important;
        border: 1px solid #d1d5db !important;
        transition: 0.3s ease;
    }

    input:focus, textarea:focus {
        border-color: #007aff !important;
        box-shadow: 0 0 6px rgba(0, 122, 255, 0.4);
    }

    .btn-macos {
        background: linear-gradient(135deg, #007aff, #005ce6);
        color: white;
        font-weight: 600;
        border: none;
        border-radius: 12px;
        padding: 12px;
        width: 100%;
        transition: 0.3s ease;
    }

    .btn-macos:hover {
        background: linear-gradient(135deg, #339cff, #0070f3);
        transform: translateY(-2px);
    }

    .back-btn {
        color: #007aff;
        font-weight: 500;
        text-decoration: none;
        transition: 0.2s;
    }

    .back-btn:hover {
        text-decoration: underline;
    }
</style>

<div class="container py-5">
    <div class="col-lg-6 mx-auto">
        <div class="card-macos">
            <h3 class="text-center mb-4" style="color:#007aff;">
                Tambah Layanan Baru
            </h3>

            {{-- ✅ Pesan sukses --}}
            @if(session('success'))
                <div class="alert alert-success text-center">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ⚠️ Pesan error --}}
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

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Layanan</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-control @error('name') is-invalid @enderror" 
                        value="{{ old('name') }}" 
                        placeholder="Contoh: Cuci Kering, Setrika, Laundry Kilat"
                        required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea 
                        id="description" 
                        name="description" 
                        class="form-control @error('description') is-invalid @enderror" 
                        rows="3"
                        placeholder="Tambahkan deskripsi singkat (opsional)...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- 🚫 Kolom harga telah dihapus --}}

                <button type="submit" class="btn-macos mb-3">
                    Simpan Layanan
                </button>

                <div class="text-center">
                    <a href="{{ route('layanan.index') }}" class="back-btn">
                        ← Kembali ke Daftar Layanan
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
