<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengelola ulasan.');
        }

        $reviews = Review::orderByDesc('created_at')->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        return view('reviews.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:2000'],
        ]);

        Review::create($data + ['is_visible' => true]);

        return redirect()->route('reviews.create')
            ->with('success', 'Terima kasih, ulasan Anda berhasil dikirim.');
    }

    public function edit(Request $request, Review $review)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengelola ulasan.');
        }

        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengelola ulasan.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:2000'],
            'is_visible' => ['sometimes', 'boolean'],
        ]);

        $review->update([
            'name' => $data['name'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
            'is_visible' => $request->boolean('is_visible'),
        ]);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Ulasan berhasil diperbarui.');
    }

    public function destroy(Request $request, Review $review)
    {
        $user = $request->user();
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengelola ulasan.');
        }

        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Ulasan berhasil dihapus.');
    }
}
