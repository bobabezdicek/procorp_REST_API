<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = DB::table('users')->where('email', '=', $request->email)->first();

        if (!Hash::check($request->password, $user->password) || !$user) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = Str::random(50);

        $user->api_token = $token;
        DB::table('users')
            ->where('email', '=',$request->email)
            ->update(['api_token' => $token]);

        return response()->json([
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->api_token = null;
            $user->save();

            return response()->json([
                'message' => 'Logout successful'
            ]);
        }

        return response()->json([
            'message' => 'Already logged out or invalid token'
        ], 401);
    }
}
