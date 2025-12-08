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

        $ratingDistribution = Review::selectRaw('rating, COUNT(*) as total')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        $averageRating = Review::avg('rating');
        $totalReviews = Review::count();
        $visibleCount = Review::where('is_visible', true)->count();

        return view('admin.reviews.index', compact(
            'reviews',
            'ratingDistribution',
            'averageRating',
            'totalReviews',
            'visibleCount'
        ));
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
            'image' => ['nullable', 'image', 'max:3072'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews', 'public');
        }

        Review::create([
            'name' => $data['name'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
            'image_path' => $imagePath,
            'is_visible' => true,
        ]);

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
            'image' => ['nullable', 'image', 'max:3072'],
        ]);

        $updateData = [
            'name' => $data['name'],
            'rating' => $data['rating'],
            'comment' => $data['comment'],
            'is_visible' => $request->boolean('is_visible'),
        ];

        if ($request->hasFile('image')) {
            $updateData['image_path'] = $request->file('image')->store('reviews', 'public');
        }

        $review->update($updateData);

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
