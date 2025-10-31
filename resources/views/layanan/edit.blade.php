@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Edit Layanan</h2>

    <form action="{{ route('layanan.update', $service->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Layanan</label>
            <input type="text" name="nama_layanan" value="{{ $service->nama_layanan }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Harga</label>
            <input type="number" name="harga" value="{{ $service->harga }}" class="form-control" required>
        </div>

        <button class="btn btn-primary w-100">Perbarui</button>
    </form>
</div>
@endsection
