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
        $review = Review::create($request->all());
        return response()->json($review, 201);
    }

    public function show($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['error' => 'Review tidak ditemukan'], 404);
        }
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
