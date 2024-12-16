<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {        
        $request->validate([
            'film_id' => 'required',
            'user_id' => 'required',
            'rating' => 'required',
            'comment' => 'nullable|string',
        ]);

        $existingReview = Review::where('user_id', $request->user_id)
                            ->where('film_id', $request->film_id)
                            ->first();

    if ($existingReview) {
        return response()->json([
            'message' => 'You can only leave one review per film.',
            'hasExistingReview' => true,
        ], 422);
    } else{
        $review = Review::create($request->all());
        return response()->json($review, 201);
    }
        
    }

    public function show($id)
    {
        $review = Review::where('film_id', $id)
    ->with(['user' => function($query) {
        $query->select('id', 'name');  // Ambil hanya id dan name dari tabel users
    }])
    ->get();

        // Jika tidak ditemukan, kembalikan respons error
        if (!$review) {
            return response()->json(['error' => 'Review tidak ditemukan'], 404);
        }
    
        // Kembalikan data review sebagai JSON
        return response()->json($review);
    }
    

    public function update(Request $request, $id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['error' => 'Review tidak ditemukan'], 404);
        }

        $request->validate([
            'film_id' => 'required',
            'user_id' => 'required',
            'rating' => 'required',
            'comment' => 'nullable|string',
        ]);

        $review->update($request->all());
        return response()->json($review, 200);  // Return 200 for successful update
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['error' => 'Review tidak ditemukan'], 404);
        }

        $review->delete();
        return response()->json(['message' => 'Review berhasil dihapus']);
    }
}
