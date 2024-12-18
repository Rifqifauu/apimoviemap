<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    // Menampilkan semua favorite milik user tertentu
    public function index($id)
    {
        // Validasi ID user
        if (!is_numeric($id)) {
            return response()->json(['message' => 'Invalid user ID'], 400);
        }
        // Ambil data favorite berdasarkan user_id
        $favorites = Favorite::where('user_id', $id)->pluck('film_id'); // Mengambil hanya kolom film_id
        // Periksa apakah favorite kosong
        if ($favorites->isEmpty()) {
            return response()->json(['message' => 'No favorite found for this user'], 404);
        }
        return response()->json([
            'message' => 'Favorite retrieved successfully',
            'data' => $favorites
        ], 200);
    }

    // Menambah item baru ke favorite
    // Dalam FavoriteController.php
public function store(Request $request)
{
    $validatedData = $request->validate([
        'user_id' => 'required|integer|exists:users,id',
        'film_id' => 'required|integer',
    ]);

    // Cek apakah film sudah ada di favorite user
    $existingFavorite = Favorite::where('user_id', $validatedData['user_id'])
        ->where('film_id', $validatedData['film_id'])
        ->first();

    if ($existingFavorite) {
        return response()->json(['message' => 'Film already in favorites'], 400);
    }

    $favorite = Favorite::create([
        'user_id' => $validatedData['user_id'],
        'film_id' => $validatedData['film_id'],
        'created_at' => now(),
        'updated_at' => now()
    ]);

    return response()->json(['message' => 'Favorite item created', 'data' => $favorite], 201);
}

    // Mengedit item di favorite
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id', // Validasi user ID
            'film_id' => 'nullable|integer',
        ]);

        $favorite = Favorite::where('id', $id)
            ->where('user_id', $validatedData['user_id'])
            ->firstOrFail();

        $favorite->update($validatedData);

        return response()->json(['message' => 'Favorite item updated', 'data' => $favorite]);
    }

    // Menghapus item dari favorite
    public function destroy($user_id, $film_id)
    {
        // Validasi user_id dan film_id
        if (!is_numeric($user_id) || !is_numeric($film_id)) {
            return response()->json(['message' => 'Invalid user ID or film ID'], 400);
        }
    
        // Cari dan hapus item favorite berdasarkan user_id dan film_id
        $favorite = Favorite::where('user_id', $user_id)
            ->where('film_id', $film_id)
            ->first();
    
        // Jika item tidak ditemukan
        if (!$favorite) {
            return response()->json(['message' => 'Favorite item not found'], 404);
        }
    
        // Hapus item
        $favorite->delete();
    
        return response()->json(['message' => 'Favorite item deleted']);
    }
}
