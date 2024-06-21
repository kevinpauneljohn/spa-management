<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function authenticate(Request $request): \Illuminate\Http\JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            return response()->json([
                'success' => true,
                'token' => Auth::user()->createToken('authToken')->plainTextToken,
                'user' => Auth::user(),
                'message' => 'Login success'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Login failed'
        ]);
    }
}
