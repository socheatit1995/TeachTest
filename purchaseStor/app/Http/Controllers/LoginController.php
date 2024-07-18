<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]);

        if ($user) {
            return response()->json(['message' => 'User logged in successfully']);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
    }
}
