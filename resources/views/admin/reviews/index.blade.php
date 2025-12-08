@extends('layouts.app')

@section('title', 'Kelola Rating & Ulasan')

@section('content')
<style>
    .reviews-admin-shell {
        margin-top: 32px;
    }

    .reviews-admin-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }

    .reviews-admin-title-wrap {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .reviews-admin-eyebrow {
        font-size: 0.78rem;
        letter-spacing: 0.18em;
        text-transform: uppercase;
        color: #9ca3af;
    }

    .reviews-admin-title {
        font-weight: 800;
        font-size: 1.25rem;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        color: #111827;
        margin: 0;
    }

    .reviews-admin-subtitle {
        font-size: 0.86rem;
        color: #6b7280;
        margin: 0;
    }

    .reviews-admin-summary {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 8px 14px;
        border-radius: 999px;
        background: rgba(15,23,42,0.02);
        border: 1px solid rgba(226,232,240,0.9);
        font-size: 0.82rem;
        color: #4b5563;
    }

    .reviews-admin-summary span.badge-dot {
        width: 7px;
        height: 7px;
        border-radius: 999px;
        background: #22c55e;
        box-shadow: 0 0 0 4px rgba(34,197,94,0.18);
    }

    .reviews-admin-window {
        border-radius: 24px;
        background: radial-gradient(circle at 0% 0%, rgba(255,255,255,0.98), rgba(241,245,255,0.95));
        box-shadow: 0 26px 70px rgba(15,23,42,0.16);
        border: 1px solid rgba(255,255,255,0.9);
        padding: 18px 18px 20px;
    }

    .reviews-admin-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 10px;
    }

    .reviews-admin-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        background: rgba(15,23,42,0.04);
        font-size: 0.78rem;
        color: #4b5563;
    }

    .reviews-admin-pill strong {
        font-weight: 600;
    }

    .reviews-admin-table-shell {
        margin-top: 6px;
        border-radius: 18px;
        border: 1px solid rgba(226,232,240,0.9);
        overflow: hidden;
        background: rgba(255,255,255,0.9);
    }

    .reviews-admin-table {
        margin-bottom: 0;
        font-size: 0.86rem;
    }

    .reviews-admin-table thead {
        background: linear-gradient(135deg, rgba(248,250,252,0.96), rgba(239,246,255,0.96));
    }

    .reviews-admin-table thead th {
        border-bottom-width: 1px;
        border-color: rgba(226,232,240,0.9);
        text-transform: uppercase;
        letter-spacing: 0.12em;
        font-size: 0.72rem;
        color: #9ca3af;
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .reviews-admin-table tbody td {
        vertical-align: middle;
    }

    .reviews-admin-rating {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 9px;
        border-radius: 999px;
        background: rgba(251,191,36,0.08);
        color: #92400e;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .reviews-admin-rating span.star {
        color: #f59e0b;
        font-size: 0.85rem;
    }

    .reviews-admin-visible-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 500;
    }

    .reviews-admin-visible-badge.on {
        background: rgba(34,197,94,0.08);
        color: #166534;
    }

    .reviews-admin-visible-badge.off {
        background: rgba(229,231,235,0.8);
        color: #6b7280;
    }

    .reviews-admin-visible-dot {
        width: 6px;
        height: 6px;
        border-radius: 999px;
    }

    .reviews-admin-visible-badge.on .reviews-admin-visible-dot {
        background: #22c55e;
    }

    .reviews-admin-visible-badge.off .reviews-admin-visible-dot {
        background: #9ca3af;
    }

    .reviews-admin-actions .btn {
        border-radius: 999px;
        font-size: 0.76rem;
        padding: 4px 10px;
    }

    .reviews-admin-empty {
        padding: 22px;
        text-align: center;
        color: #9ca3af;
        font-size: 0.9rem;
    }

    .reviews-admin-pagination {
        margin-top: 14px;
    }
</style>

<div class="container-fluid home-wide-container reviews-admin-shell">
    <div class="reviews-admin-header">
        <div class="reviews-admin-title-wrap">
            <div class="reviews-admin-eyebrow">Admin · Feedback</div>
            <h1 class="reviews-admin-title">Rating & Ulasan Pelanggan</h1>
            <p class="reviews-admin-subtitle">
                Lihat, kelola, dan kurasi ulasan yang tampil di halaman utama.
            </p>
        </div>
        <div class="reviews-admin-summary">
            <span class="badge-dot"></span>
            <span>
                Total ulasan: <strong>{{ $reviews->total() }}</strong>
            </span>
        </div>
    </div>

    <div class="reviews-admin-window">
        @if(session('success'))
            <div class="alert alert-success py-2 px-3 mb-3" style="border-radius: 12px;font-size:.82rem;">
                {{ session('success') }}
            </div>
        @endif

        <div class="reviews-admin-toolbar">
            <div class="reviews-admin-pill">
                <strong>Daftar Ulasan</strong>
                <span>Kelola visibilitas dan konten ulasan pelanggan.</span>
            </div>
            <a href="{{ route('reviews.create') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                + Tambah ulasan manual
            </a>
        </div>

        <div class="reviews-admin-table-shell">
            @if($reviews->count() > 0)
                <div class="table-responsive">
                    <table class="table align-middle reviews-admin-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Rating</th>
                                <th>Ulasan</th>
                                <th>Tampil</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                                <tr>
                                    <td style="width: 160px;">
                                        <div class="fw-semibold text-truncate" style="max-width: 160px;">
                                            {{ $review->name }}
                                        </div>
                                        <div class="text-muted" style="font-size:.78rem;">
                                            {{ $review->created_at?->format('d M Y, H:i') }}
                                        </div>
                                    </td>
                                    <td style="width: 120px;">
                                        <div class="reviews-admin-rating">
                                            <span class="star">★</span>
                                            <span>{{ number_format($review->rating, 1) }}/5</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="max-width: 460px;">
                                            <div style="font-size:.86rem;color:#111827;">
                                                {{ \Illuminate\Support\Str::limit($review->comment, 120) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td style="width: 130px;">
                                        <span class="reviews-admin-visible-badge {{ $review->is_visible ? 'on' : 'off' }}">
                                            <span class="reviews-admin-visible-dot"></span>
                                            {{ $review->is_visible ? 'Ditampilkan' : 'Disembunyikan' }}
                                        </span>
                                    </td>
                                    <td class="text-end reviews-admin-actions" style="width: 160px;">
                                        <a href="{{ route('admin.reviews.edit', $review) }}" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus ulasan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="reviews-admin-empty">
                    Belum ada rating & ulasan yang masuk.
                </div>
            @endif
        </div>

        <div class="reviews-admin-pagination">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection
