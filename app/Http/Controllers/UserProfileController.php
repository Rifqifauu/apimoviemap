<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return response()->json($user);
    }

    public function update(Request $request)
    {
        try {
            // Validate input
            $validatedData = $request->validate([
                'location' => 'nullable|string|max:255',
                'bio' => 'nullable|string|max:500',
            ]);

            // Get authenticated user
            $user = Auth::user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Perform direct database update
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'location' => $request->input('location'),
                    'bio' => $request->input('bio')
                ]);

            // Refresh user data
            $updatedUser = DB::table('users')->where('id', $user->id)->first();

            return response()->json([
                'message' => 'Profile updated successfully', 
                'user' => $updatedUser
            ]);

        } catch (\Exception $e) {
            Log::error('Profile update error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}