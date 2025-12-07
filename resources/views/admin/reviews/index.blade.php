@extends('layouts.app')

@section('title', 'Kelola Rating & Ulasan')

@section('content')
<div class="container home-wide-container" style="margin-top: 80px;">
    <div class="mac-card" style="background:#fff;border-radius:20px;padding:24px;box-shadow:0 18px 40px rgba(15,23,42,0.08);border:1px solid #e5e7eb;">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0">Rating & Ulasan Pelanggan</h2>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Rating</th>
                        <th>Ulasan</th>
                        <th>Tampil</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>{{ $review->name }}</td>
                            <td>{{ $review->rating }} / 5</td>
                            <td>{{ \Illuminate\Support\Str::limit($review->comment, 80) }}</td>
                            <td>{{ $review->is_visible ? 'Ya' : 'Tidak' }}</td>
                            <td>
                                <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus ulasan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada rating & ulasan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection

