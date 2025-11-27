@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h3 class="mb-4 text-primary fw-bold">Kelola Tim Profesional</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <p class="text-muted mb-0">
                    Hanya admin yang dapat menambah, mengedit, dan menghapus anggota tim yang tampil di halaman Tentang Kami.
                </p>
                <a href="{{ route('admin.team.create') }}" class="btn btn-sm btn-primary">+ Tambah Anggota Tim</a>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
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

