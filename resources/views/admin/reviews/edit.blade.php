@extends('layouts.app')

@section('title', 'Edit Rating & Ulasan')

@section('content')
<div class="container home-wide-container" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mac-card" style="background:#fff;border-radius:20px;padding:28px;box-shadow:0 18px 40px rgba(15,23,42,0.08);border:1px solid #e5e7eb;">
                <h2 class="mb-3 fw-bold text-center">Edit Rating & Ulasan</h2>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.reviews.update', $review) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $review->name) }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <select id="rating" name="rating" class="form-select" required>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ old('rating', $review->rating) == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ $i === 1 ? 'bintang' : 'bintang' }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="comment" class="form-label">Ulasan</label>
                        <textarea id="comment" name="comment" rows="4" class="form-control" required>{{ old('comment', $review->comment) }}</textarea>
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="is_visible" name="is_visible" value="1" {{ old('is_visible', $review->is_visible) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_visible">Tampilkan di halaman beranda</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary mac-btn">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

