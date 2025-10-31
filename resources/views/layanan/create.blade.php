@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Tambah Layanan Baru</h2>

    <form action="{{ route('layanan.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Layanan</label>
            <input type="text" name="nama_layanan" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" required>
        </div>
        <button class="btn btn-success w-100">Simpan</button>
    </form>
</div>
@endsection
