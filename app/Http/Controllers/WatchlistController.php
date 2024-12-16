<?php

namespace App\Http\Controllers;

use App\Models\Watchlist;
use Illuminate\Http\Request;

class WatchlistController extends Controller
{
    // Menampilkan semua watchlist milik user tertentu
    public function index($id)
    {
        // Validasi ID user
        if (!is_numeric($id)) {
            return response()->json(['message' => 'Invalid user ID'], 400);
        }
        // Ambil data watchlist berdasarkan user_id
        $watchlists = Watchlist::where('user_id', $id)->pluck('film_id'); // Mengambil hanya kolom film_id
        // Periksa apakah watchlist kosong
        if ($watchlists->isEmpty()) {
            return response()->json(['message' => 'No watchlist found for this user'], 404);
        }
        return response()->json([
            'message' => 'Watchlist retrieved successfully',
            'data' => $watchlists
        ], 200);
    }

    // Menambah item baru ke watchlist
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id', // Validasi user ID
            'film_id' => 'required|integer',
        ]);
        

        $watchlist = Watchlist::create([
            'user_id' => $validatedData['user_id'],
            'film_id' => $validatedData['film_id'],
'created_at' => now(), // Waktu sekarang
    'updated_at' => now(),        ]);

        return response()->json(['message' => 'Watchlist item created', 'data' => $watchlist], 201);
    }

    // Mengedit item di watchlist
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id', // Validasi user ID
            'film_id' => 'nullable|integer',
        ]);

        $watchlist = Watchlist::where('id', $id)
            ->where('user_id', $validatedData['user_id'])
            ->firstOrFail();

        $watchlist->update($validatedData);

        return response()->json(['message' => 'Watchlist item updated', 'data' => $watchlist]);
    }

    // Menghapus item dari watchlist
    public function destroy($user_id, $film_id)
    {
        // Validasi user_id dan film_id
        if (!is_numeric($user_id) || !is_numeric($film_id)) {
            return response()->json(['message' => 'Invalid user ID or film ID'], 400);
        }
    
        // Cari dan hapus item watchlist berdasarkan user_id dan film_id
        $watchlist = Watchlist::where('user_id', $user_id)
            ->where('film_id', $film_id)
            ->first();
    
        // Jika item tidak ditemukan
        if (!$watchlist) {
            return response()->json(['message' => 'Watchlist item not found'], 404);
        }
    
        // Hapus item
        $watchlist->delete();
    
        return response()->json(['message' => 'Watchlist item deleted']);
    }
}
