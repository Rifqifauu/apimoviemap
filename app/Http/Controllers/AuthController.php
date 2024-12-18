<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller{
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (empty($credentials['email']) || empty($credentials['password'])) {
            return response()->json(['error' => 'Email dan password tidak boleh kosong'], 422);
        }
        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
        $user = Auth::user();
        try {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'location' => $user->location,
                    'bio' => $user->bio,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal membuat token'], 500);
        }
    }
    public function logout(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'User  tidak ditemukan'], 404);
    }

    try {
        $request->user()->tokens()->where('id', $request->user()->currentAccessToken()->id)->delete();
        return response()->json(['message' => 'Berhasil logout']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Gagal logout'], 500);
    }
}
}