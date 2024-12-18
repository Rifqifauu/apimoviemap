<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $message = "Pendaftaran Berhasil";
        return response()->json($message, 201);
    }
    public function update(Request $request, User $user)
{
    // Validasi input termasuk location dan bio
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'sometimes|nullable|min:6',
        'location' => 'nullable|string|max:255',
        'bio' => 'nullable|string|max:255',
    ]);

    // Perbarui data user
    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password ? bcrypt($request->password) : $user->password,
        'location' => $request->location,
        'bio' => $request->bio,
    ]);

    // Kembalikan data terbaru user
    return response()->json([
        'message' => 'Profil Berhasil Diubah',
        'user' => $user  // Kirim kembali data terbaru ke frontend
    ], 200);
}

}
