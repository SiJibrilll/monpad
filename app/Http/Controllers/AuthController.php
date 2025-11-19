<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    function login(Request $request) {
        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string']
        ]);

        if (!Auth::attempt($validated)) {
            return response()->json([
                'message' => 'Invalid email or password.'
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('api_token')->plainTextToken;


        return new AuthResource([
            'user' => $user,
            'token' => $token
        ]);
    }

    function logout(Request $request) {
        if (! $request->user()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        /** @var \Laravel\Sanctum\PersonalAccessToken|null $token */
        $token = $request->user()->currentAccessToken();

        if (! $token) {
            return response()->json(['message' => 'No active token'], 400);
        }

        $token->delete();

        return response()->json(['message' => 'Logged out successfully']);

    }
}
