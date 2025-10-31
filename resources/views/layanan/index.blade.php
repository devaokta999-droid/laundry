@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Daftar Layanan Deva Laundry</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="text-end mb-3">
        <a href="{{ route('layanan.create') }}" class="btn btn-primary">+ Tambah Layanan</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr class="text-center">
                <th>No</th>
                <th>Nama Layanan</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($services as $service)
            <tr class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $service->nama_layanan }}</td>
                <td>Rp {{ number_format($service->harga, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('layanan.edit', $service->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('layanan.destroy', $service->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus layanan ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada layanan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
