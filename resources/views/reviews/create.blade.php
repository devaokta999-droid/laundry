@extends('layouts.app')

@section('title', 'Rating & Ulasan - Deva Laundry')

@section('content')
<div class="container home-wide-container" style="margin-top: 80px;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mac-card" style="background:#fff;border-radius:20px;padding:28px;box-shadow:0 18px 40px rgba(15,23,42,0.08);border:1px solid #e5e7eb;">
                <h2 class="mb-3 fw-bold text-center">Berikan Rating & Ulasan</h2>
                <p class="text-muted text-center mb-4">
                    Ceritakan pengalaman kamu menggunakan Deva Laundry. Masukanmu membantu kami meningkatkan kualitas layanan.
                </p>

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

                <form method="POST" action="{{ route('reviews.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <select id="rating" name="rating" class="form-select" required>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ old('rating', 5) == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ $i === 1 ? 'bintang' : 'bintang' }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="comment" class="form-label">Ulasan</label>
                        <textarea id="comment" name="comment" rows="4" class="form-control" required>{{ old('comment') }}</textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary mac-btn">
                            Kirim Rating & Ulasan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

