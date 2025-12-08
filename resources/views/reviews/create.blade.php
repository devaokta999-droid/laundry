@extends('layouts.app')

@section('title', 'Rating & Ulasan - Deva Laundry')

@section('content')
<style>
    .review-page-shell {
        margin-top: 40px;
    }

    .review-page-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .review-page-eyebrow {
        font-size: 0.78rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #9ca3af;
        margin-bottom: 6px;
    }

    .review-page-title {
        font-weight: 800;
        font-size: clamp(1.8rem, 2.6vw, 2.2rem);
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin-bottom: 6px;
        color: #111827;
    }

    .review-page-subtitle {
        max-width: 520px;
        margin: 0 auto;
        font-size: 0.98rem;
        color: #4b5563;
    }

    .review-layout {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        align-items: stretch;
        justify-content: center;
        max-width: 1200px;
        margin: 0 auto 40px;
    }

    .review-info-panel {
        flex: 1 1 320px;
        max-width: 460px;
        background: radial-gradient(circle at 0% 0%, #0a84ff 0, #2563eb 40%, #020617 100%);
        color: #e5e7eb;
        border-radius: 24px;
        padding: 26px 26px 22px;
        box-shadow: 0 26px 60px rgba(15,23,42,0.45);
        border: 1px solid rgba(148,163,184,0.35);
        position: relative;
        overflow: hidden;
    }

    .review-info-panel::before {
        content: "";
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 80% 0%, rgba(255,255,255,0.20), transparent 55%);
        opacity: 0.9;
        pointer-events: none;
    }

    .review-info-inner {
        position: relative;
        z-index: 1;
    }

    .review-info-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 11px;
        border-radius: 999px;
        background: rgba(15,23,42,0.45);
        color: #e5e7eb;
        font-size: 0.78rem;
        margin-bottom: 12px;
    }

    .review-info-badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34,197,94,0.25);
    }

    .review-info-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .review-info-text {
        font-size: 0.9rem;
        opacity: 0.88;
        margin-bottom: 18px;
    }

    .review-info-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        font-size: 0.83rem;
        opacity: 0.9;
    }

    .review-info-meta span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .review-info-meta span::before {
        content: "●";
        font-size: 0.46rem;
        opacity: 0.8;
    }

    .review-form-card {
        flex: 1 1 380px;
        max-width: 640px;
        background: #ffffff;
        border-radius: 24px;
        padding: 26px 24px 24px;
        box-shadow: 0 22px 50px rgba(15,23,42,0.12);
        border: 1px solid rgba(229,231,235,0.9);
    }

    .review-form-header {
        margin-bottom: 18px;
    }

    .review-form-subtitle {
        font-size: 0.9rem;
        color: #6b7280;
        margin-bottom: 0;
    }

    .review-form-step {
        font-size: 0.8rem;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: 0.16em;
    }

    .review-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e5e7eb, transparent);
        margin-bottom: 20px;
    }

    .review-form-card label.form-label {
        font-size: 0.9rem;
        font-weight: 600;
        color: #111827;
    }

    .review-form-card .form-control,
    .review-form-card .form-select,
    .review-form-card textarea {
        border-radius: 12px;
        border-color: #e5e7eb;
        padding: 10px 12px;
        font-size: 0.95rem;
    }

    .review-form-card .form-control:focus,
    .review-form-card .form-select:focus,
    .review-form-card textarea:focus {
        border-color: var(--mac-accent);
        box-shadow:
            0 0 0 1px rgba(0,122,255,0.15),
            0 0 0 4px rgba(191,219,254,0.8);
    }

    .review-alert {
        border-radius: 14px;
        padding: 10px 12px;
        font-size: 0.85rem;
    }

    .rating-stars-wrapper {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }

    .rating-stars {
        display: inline-flex;
        gap: 4px;
    }

    .rating-star {
        border: none;
        background: transparent;
        padding: 0;
        cursor: pointer;
        color: #d1d5db; /* abu-abu untuk bintang kosong */
        font-size: 1.5rem;
        line-height: 1;
        box-shadow: none;
    }

    .rating-star span {
        line-height: 1;
    }

    .rating-star.active {
        color: #facc15; /* kuning untuk bintang terisi */
    }

    .rating-star:hover {
        color: #fbbf24;
    }

    .rating-caption {
        font-size: 0.8rem;
        color: #6b7280;
    }

    .btn-primary.mac-btn {
        border-radius: 999px;
        padding: 7px 14px;
        font-weight: 600;
        font-size: 0.86rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        background: transparent;
        color: #0f172a;
        border: 1px solid rgba(148,163,184,0.75);
        box-shadow: none;
    }

    .btn-primary.mac-btn:hover {
        background: rgba(15,23,42,0.03);
        border-color: rgba(59,130,246,0.8);
        color: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(15,23,42,0.12);
    }

    .btn-primary.mac-btn:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(15,23,42,0.18);
        background: rgba(15,23,42,0.02);
    }

    @media (max-width: 992px) {
        .review-page-shell {
            margin-top: 8px;
        }

        .review-info-panel {
            order: 2;
        }

        .review-form-card {
            order: 1;
        }
    }
</style>

<div class="container-fluid home-wide-container review-page-shell">
    <div class="review-page-header">
        <div class="review-page-eyebrow">Rating & Ulasan</div>
        <div class="review-page-title">Bagikan Pengalaman Kamu</div>
        <p class="review-page-subtitle">
            Masukan jujurmu membantu kami merapikan proses, menyempurnakan layanan,
            dan menjaga kualitas Deva Laundry tetap setara standar premium.
        </p>
    </div>

    <div class="review-layout">
        <div class="review-info-panel">
            <div class="review-info-inner">
                <div class="review-info-badge">
                    <span class="review-info-badge-dot"></span>
                    Terima kasih sudah mempercayakan cucianmu
                </div>
                <div class="review-info-title">
                    Satu ulasan kecil, dampak besar.
                </div>
                <p class="review-info-text">
                    Setiap rating dan kalimat yang kamu tulis kami baca dengan serius.
                    Dari sana kami menyusun perbaikan—mulai dari kecepatan proses,
                    kerapian lipatan, sampai detail wangi dan pengemasan.
                </p>

                <div class="review-info-meta">
                    <span>Respon ulasan dimonitor langsung oleh tim</span>
                    <span>Fokus ke pengalaman yang jujur & apa adanya</span>
                    <span>Kurang dari 1 menit untuk mengisi</span>
                </div>
            </div>
        </div>

        <div class="review-form-card">
            <div class="review-form-header d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h2 class="h5 mb-1 fw-bold">Berikan rating & ulasan</h2>
                    <p class="review-form-subtitle">
                        Ceritakan apa yang paling kamu suka, dan apa yang bisa kami perbaiki.
                    </p>
                </div>
                <div class="text-end">
                    <div class="review-form-step">Langkah 1/1</div>
                </div>
            </div>

            <div class="review-divider"></div>

            @if(session('success'))
                <div class="alert alert-success review-alert mb-3">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger review-alert mb-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('reviews.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control"
                        placeholder="Tulis nama atau inisial kamu"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Rating</label>
                    <div class="rating-stars-wrapper">
                        <div class="rating-stars" data-selected="{{ old('rating', 5) }}">
                            @for($i = 1; $i <= 5; $i++)
                                <button
                                    type="button"
                                    class="rating-star {{ old('rating', 5) >= $i ? 'active' : '' }}"
                                    data-value="{{ $i }}"
                                    aria-label="Rating {{ $i }} bintang"
                                >
                                    <span>★</span>
                                </button>
                            @endfor
                        </div>
                        <div class="rating-caption">
                            Pilih jumlah bintang yang paling mewakili pengalamanmu.
                        </div>
                    </div>
                    <input type="hidden" id="rating" name="rating" value="{{ old('rating', 5) }}">
                </div>

                <div class="mb-3">
                    <label for="comment" class="form-label">Ulasan</label>
                    <textarea
                        id="comment"
                        name="comment"
                        rows="4"
                        class="form-control"
                        placeholder="Contoh: pakaian rapi, wangi pas, kurir ramah, atau hal lain yang kamu rasakan."
                        required
                    >{{ old('comment') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="image" class="form-label">Tambahkan gambar (opsional)</label>
                    <input
                        type="file"
                        id="image"
                        name="image"
                        class="form-control"
                        accept="image/*"
                    >
                    <div class="form-text">
                        Format gambar (JPG, PNG, dll), maks. 3 MB.
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <small class="text-muted">
                        Dengan mengirim ulasan, kamu membantu pelanggan lain memilih lebih yakin.
                    </small>
                    <button type="submit" class="btn btn-primary mac-btn">
                        Kirim rating & ulasan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const starsWrapper = document.querySelector('.rating-stars');
        if (!starsWrapper) return;

        const hiddenInput = document.getElementById('rating');
        const stars = starsWrapper.querySelectorAll('.rating-star');

        function setRating(value) {
            hiddenInput.value = value;
            stars.forEach(star => {
                const v = parseInt(star.getAttribute('data-value'));
                if (v <= value) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }

        stars.forEach(star => {
            star.addEventListener('click', function () {
                const value = parseInt(this.getAttribute('data-value'));
                setRating(value);
            });
        });
    });
</script>
@endsection
